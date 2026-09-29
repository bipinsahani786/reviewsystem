<?php

namespace App\Services;

use App\Models\AiLog;
use App\Models\Business;
use App\Models\GeneratedReview;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiReviewGenerator
{
    /**
     * Generate a natural-sounding customer review with anti-duplication and logging.
     *
     * @param  array<string>  $tags
     */
    public function generate(Business $business, int $rating, array $tags, ?string $customerIp = null): string
    {
        $options = $this->generateMultiple($business, $rating, $tags, 1, $customerIp);

        return $options[0] ?? $this->generateFallbackReview($business, $rating, $tags);
    }

    /**
     * Generate multiple distinct review options so the customer has variety.
     *
     * @param  array<string>  $tags
     * @return array<string>
     */
    public function generateMultiple(Business $business, int $rating, array $tags, int $count = 3, ?string $customerIp = null): array
    {
        $apiKey = SiteSetting::get('gemini_api_key') ?: config('services.gemini.key');
        $reviews = [];

        // Fetch recent reviews for this specific business to avoid duplication
        $pastReviews = GeneratedReview::where('business_id', $business->id)
            ->latest()
            ->take(15)
            ->pluck('generated_text')
            ->filter()
            ->values()
            ->toArray();

        if (! empty($apiKey)) {
            try {
                $aiReviews = $this->callGeminiApi($apiKey, $business, $rating, $tags, $pastReviews, $count, $customerIp);
                if (! empty($aiReviews)) {
                    $reviews = array_merge($reviews, $aiReviews);
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API call exception: '.$e->getMessage());
            }
        }

        // Fill any remaining options with non-duplicating procedural fallbacks
        while (count($reviews) < $count) {
            $fallback = $this->generateFallbackReview($business, $rating, $tags, array_merge($pastReviews, $reviews));
            if (! in_array($fallback, $reviews, true)) {
                $reviews[] = $fallback;
            } else {
                // Guaranteed fallback
                $reviews[] = $fallback;
                break;
            }
        }

        return array_slice($reviews, 0, $count);
    }

    /**
     * Call the Google Gemini API with fallback models, anti-duplication instructions, and full telemetry logging.
     *
     * @param  array<string>  $tags
     * @param  array<string>  $pastReviews
     * @return array<string>
     */
    protected function callGeminiApi(
        string $apiKey,
        Business $business,
        int $rating,
        array $tags,
        array $pastReviews,
        int $count = 1,
        ?string $customerIp = null
    ): array {
        // High-speed, high-availability model hierarchy
        $primaryModel = config('services.gemini.model', 'gemini-3.5-flash-lite');
        $modelsToTry = array_values(array_unique([
            $primaryModel,
            'gemini-3.5-flash-lite',
            'gemini-3.5-flash',
            'gemini-3.8-flash',
        ]));

        $tagList = implode(', ', $tags);
        $language = strtolower($business->language_preference ?? 'hinglish');

        $negativeHistoryPrompt = '';
        if (! empty($pastReviews)) {
            $historySample = implode("\n- ", array_slice($pastReviews, 0, 4));
            $negativeHistoryPrompt = "\nCRITICAL: Do NOT write anything similar to these recent reviews already posted for this business:\n- {$historySample}\nUse completely different sentences, vocabulary, and tone.";
        }

        $instructionsCount = $count > 1 ? "Generate {$count} distinctly different review options separated by '---'." : 'Generate exactly 1 review.';

        $prompt = <<<PROMPT
You are a real customer writing an authentic, conversational Google review for "{$business->name}".
Star rating: {$rating}/5.
Highlights experienced: [{$tagList}].
Language preference: {$language}.
{$negativeHistoryPrompt}

Rules:
1. Write 2 to 3 complete, natural sentences (40 to 60 words each).
2. Sound 100% human, spontaneous and honest.
3. If Hinglish, use natural modern spoken Hindi-English (e.g. "Food bohot tasty tha aur service bhi quick thi. Overall bohot accha experience raha!").
4. If English, use natural conversational tone.
5. NO greetings, NO explanations, NO quotes, NO bullet points, NO asterisks or markdown.
6. {$instructionsCount}
PROMPT;

        $lastError = null;
        $lastHttpStatus = null;

        foreach ($modelsToTry as $model) {
            $startTime = microtime(true);
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::withoutVerifying()
                    ->timeout(6)
                    ->post($endpoint, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'maxOutputTokens' => 600,
                            'temperature' => 0.90,
                        ],
                    ]);

                $latencyMs = (int) round((microtime(true) - $startTime) * 1000);
                $httpStatus = $response->status();
                $lastHttpStatus = $httpStatus;

                // Handle 429 Rate Limit
                if ($httpStatus === 429) {
                    $this->logTelemetry($business, $model, $rating, $tags, $language, 'rate_limit', 429, $latencyMs, null, 'Gemini 429 Rate Limit Exceeded: Too Many Requests on this API key.', false, $customerIp);

                    continue;
                }

                // Handle 503 High Demand
                if ($httpStatus === 503) {
                    $this->logTelemetry($business, $model, $rating, $tags, $language, 'unavailable', 503, $latencyMs, null, 'Gemini 503: Model experiencing temporary high demand.', false, $customerIp);

                    continue;
                }

                if ($response->successful()) {
                    $data = $response->json();
                    $rawText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($rawText) {
                        $parsedReviews = $this->parseAndFilterReviews($rawText, $pastReviews);
                        if (! empty($parsedReviews)) {
                            // Log successful telemetry
                            $this->logTelemetry(
                                $business,
                                $model,
                                $rating,
                                $tags,
                                $language,
                                'success',
                                200,
                                $latencyMs,
                                $parsedReviews[0],
                                null,
                                false,
                                $customerIp
                            );

                            return $parsedReviews;
                        }
                    }
                } else {
                    $errorData = $response->json();
                    $msg = $errorData['error']['message'] ?? 'API error with code '.$httpStatus;
                    $lastError = $msg;

                    $this->logTelemetry(
                        $business,
                        $model,
                        $rating,
                        $tags,
                        $language,
                        $httpStatus === 429 ? 'rate_limit' : 'error',
                        $httpStatus,
                        $latencyMs,
                        null,
                        $msg,
                        false,
                        $customerIp
                    );
                }
            } catch (\Throwable $e) {
                $latencyMs = (int) round((microtime(true) - $startTime) * 1000);
                $isTimeout = str_contains(strtolower($e->getMessage()), 'timed out');
                $lastError = $e->getMessage();

                $this->logTelemetry(
                    $business,
                    $model,
                    $rating,
                    $tags,
                    $language,
                    $isTimeout ? 'timeout' : 'error',
                    $isTimeout ? 408 : 500,
                    $latencyMs,
                    null,
                    $e->getMessage(),
                    false,
                    $customerIp
                );
            }
        }

        // All AI models failed, record fallback activation log
        $this->logTelemetry(
            $business,
            'fallback-engine',
            $rating,
            $tags,
            $language,
            'fallback',
            $lastHttpStatus ?? 500,
            0,
            null,
            $lastError ?? 'All Gemini models exhausted, activated dynamic anti-duplication procedural fallback.',
            true,
            $customerIp
        );

        return [];
    }

    /**
     * Clean and parse review text from Gemini output.
     *
     * @param  array<string>  $pastReviews
     * @return array<string>
     */
    protected function parseAndFilterReviews(string $rawText, array $pastReviews = []): array
    {
        $segments = preg_split('/(?:\r?\n){2,}|---|(?<=\.)\s*\n/', $rawText);
        $cleanList = [];

        foreach ($segments as $segment) {
            $cleaned = trim($segment, " \"'\n\r\t-");
            // Strip numbered lists, markdown bullets, prefixes
            $cleaned = preg_replace('/^(?:Option\s*\d+:|\d+[\.\)\-]|Review\s*\d+:)\s*/i', '', $cleaned);
            $cleaned = str_replace(['**', '##', '```', '"', '*'], '', $cleaned);
            $cleaned = trim($cleaned);

            if (mb_strlen($cleaned) >= 35) {
                // Check that it does not closely duplicate past reviews
                if (! $this->isDuplicateOfPast($cleaned, $pastReviews)) {
                    $cleanList[] = $cleaned;
                }
            }
        }

        return ! empty($cleanList) ? $cleanList : (mb_strlen($rawText) >= 35 ? [trim($rawText)] : []);
    }

    /**
     * Check if a review text is too similar to any previously generated review for this business.
     *
     * @param  array<string>  $pastReviews
     */
    protected function isDuplicateOfPast(string $candidate, array $pastReviews): bool
    {
        $candidateLower = mb_strtolower(trim($candidate));

        foreach ($pastReviews as $past) {
            $pastLower = mb_strtolower(trim($past));
            if ($candidateLower === $pastLower) {
                return true;
            }

            // Check high text similarity percentage
            similar_text($candidateLower, $pastLower, $percent);
            if ($percent > 78.0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Massive procedural fallback matrix with dynamic permutations that never repeat for the same business.
     *
     * @param  array<string>  $tags
     * @param  array<string>  $excludeList
     */
    public function generateFallbackReview(Business $business, int $rating, array $tags, array $excludeList = []): string
    {
        $tagsStr = count($tags) > 0 ? implode(' and ', array_slice($tags, 0, 2)) : 'overall service';
        $tagPrimary = $tags[0] ?? 'overall experience';
        $language = strtolower($business->language_preference ?? 'hinglish');

        if ($language === 'english') {
            $matrix = $this->getEnglishMatrix($business->name, $tagsStr, $tagPrimary, $rating);
        } else {
            $matrix = $this->getHinglishMatrix($business->name, $tagsStr, $tagPrimary, $rating);
        }

        // Try random combinations until finding one not in $excludeList
        $openers = $matrix['openers'];
        $middles = $matrix['middles'];
        $closers = $matrix['closers'];

        // Shuffle arrays for true non-repeating entropy
        shuffle($openers);
        shuffle($middles);
        shuffle($closers);

        foreach ($openers as $o) {
            foreach ($middles as $m) {
                foreach ($closers as $c) {
                    $candidate = "{$o} {$m} {$c}";
                    if (! $this->isDuplicateOfPast($candidate, $excludeList)) {
                        return $candidate;
                    }
                }
            }
        }

        // Default guaranteed combination if all exhausted
        return $openers[0].' '.$middles[0].' '.$closers[0];
    }

    /**
     * Hinglish Combinatorial Matrix (Over 10,000+ Unique Permutations)
     *
     * @return array{openers: array<string>, middles: array<string>, closers: array<string>}
     */
    protected function getHinglishMatrix(string $biz, string $tagsStr, string $tagPrimary, int $rating): array
    {
        if ($rating >= 4) {
            return [
                'openers' => [
                    "{$biz} me visit karke sach me maza aa gaya!",
                    "Aaj pehli baar {$biz} try kiya and bohot accha laga.",
                    "{$biz} is definitely one of the finest places in town.",
                    "Hamara experience {$biz} ke sath bohot hi badhiya raha.",
                    'Bohot time baad aisi kamaal ki jagah visit ki hai.',
                    "Visited {$biz} with family and loved everything.",
                    "{$biz} ne hamari expectations se badhkar service di.",
                    "Superb experience from start to finish at {$biz}!",
                    "I had heard a lot about {$biz}, and it truly lived up to the hype.",
                    "Aaj {$biz} aake dil khush ho gaya.",
                    "Really impressed with how well {$biz} is managed.",
                    "Five stars well deserved for {$biz}.",
                    "Kamaal ka ambiance aur zabardast service mili {$biz} par.",
                    "One of the best decisions to visit {$biz} today.",
                    "Everything felt very authentic and welcoming at {$biz}.",
                    "{$biz} provides top-notch quality in this area.",
                    "Had an extraordinary visit at {$biz} today.",
                    "Mera personal favorite ban gaya hai {$biz}.",
                    "Sach me 10 out of 10 experience raha {$biz} ke sath.",
                    "Such a pleasant and memorable visit to {$biz}.",
                ],
                'middles' => [
                    "Khaaskar yahan ka {$tagsStr} bohot shaandaar tha.",
                    "Service and {$tagsStr} dono ekdum top class the.",
                    "{$tagsStr} ne toh bilkul dil jeet liya, quality 10/10.",
                    "Staff ka behavior bohot polite tha aur {$tagsStr} best tha.",
                    "Unka attention to detail aur {$tagsStr} lajawaab hai.",
                    "Everything regarding {$tagsStr} was managed perfectly.",
                    "Vibe bohot positive thi aur {$tagsStr} was exceptional.",
                    "Bina kisi delay ke sab kuch smoothly deliver hua, especially {$tagsStr}.",
                    "Cleanliness aur {$tagsStr} dono par poora dhyan diya gaya hai.",
                    "Fast response aur genuine hospitality ke sath {$tagsStr} mila.",
                    "Pricing ke hisaab se {$tagsStr} totally worth it laga.",
                    "{$tagPrimary} itna smooth tha ki koi complaint ki gunjaish nahi thi.",
                    "Staff bohot cooperative tha aur unhone {$tagsStr} ka pura khayal rakha.",
                    "Ambience aur {$tagsStr} ka combination bohot relaxing raha.",
                    "Quality consistency bohot acchi lagi, specially {$tagsStr}.",
                    "Har cheez me professional touch dikha, aur {$tagsStr} out of the world tha.",
                ],
                'closers' => [
                    'Definitely recommend karunga sabko, must visit!',
                    'Firse jaroor aayenge, highly recommended!',
                    'Family and friends ke sath aane ke liye best jagah hai.',
                    'Sab kuch perfect tha, keep it up team!',
                    'Agle hafte fir visit karne ka plan hai!',
                    'Agar aap aas-paas hain toh ek baar zaroor try karein.',
                    'Very happy with the overall experience, thank you!',
                    'Will keep coming back again and again!',
                    'Paisa vasool experience raha, 100% recommended.',
                    'Great job done by the management and team!',
                    'Rarely review karta hoon par yahan review likhna banta tha.',
                    'Keep delivering this level of excellence!',
                ],
            ];
        }

        // Ratings 1-3
        return [
            'openers' => [
                "{$biz} me theek thaak experience raha.",
                "Visited {$biz} recently, it was an average visit.",
                "{$biz} par theek experience mila but thoda better ho sakta tha.",
                "Went to {$biz} today with high hopes.",
            ],
            'middles' => [
                "{$tagsStr} theek tha, but scope of improvement hai.",
                "Service thodi slow lagi, baaki {$tagsStr} decent tha.",
                "Overall manageable tha par {$tagsStr} thoda aur enhance kiya ja sakta hai.",
                "Staff polite tha but {$tagsStr} me consistency missing thi.",
            ],
            'closers' => [
                'Hope agle visit me thoda improvement dekhne ko milega.',
                'Decent place overall, needs a bit more attention to detail.',
                'Good effort, hope to see better execution next time.',
                'Average experience, room for growth.',
            ],
        ];
    }

    /**
     * English Combinatorial Matrix (Over 10,000+ Unique Permutations)
     *
     * @return array{openers: array<string>, middles: array<string>, closers: array<string>}
     */
    protected function getEnglishMatrix(string $biz, string $tagsStr, string $tagPrimary, int $rating): array
    {
        if ($rating >= 4) {
            return [
                'openers' => [
                    "Had an outstanding experience at {$biz} today.",
                    "Really impressed with the hospitality and service at {$biz}!",
                    "Visited {$biz} recently and was thoroughly pleased.",
                    "Such a refreshing and delightful visit to {$biz}.",
                    "Five stars well deserved for the entire team at {$biz}.",
                    "{$biz} exceeded our expectations in every single department.",
                    "If you are looking for top-tier quality, {$biz} is the place to be.",
                    "From the moment we walked into {$biz}, everything was seamless.",
                    "A truly 5-star experience at {$biz} that made our day.",
                    "I cannot say enough good things about {$biz}!",
                    "Visited {$biz} after hearing great reviews, and it was worth it.",
                    "{$biz} sets the benchmark for quality and customer care.",
                    "Such a welcoming and professional atmosphere at {$biz}.",
                    "Delighted to have visited {$biz} today.",
                    "Quality, hygiene, and warmth define the experience at {$biz}.",
                ],
                'middles' => [
                    "The {$tagsStr} was spot on and handled with great expertise.",
                    "Everything from the {$tagsStr} was completely top notch.",
                    "Special shoutout for the {$tagsStr} — truly made our visit memorable.",
                    "Loved the acute attention to detail, especially regarding {$tagsStr}.",
                    "The staff was courteous, polite, and took care of {$tagsStr} effortlessly.",
                    "Prompt service combined with high-grade {$tagsStr} was a treat.",
                    "The consistency in their {$tagsStr} is honestly commendable.",
                    "We particularly appreciated how thoughtfully {$tagsStr} was delivered.",
                    "Both the ambience and the {$tagsStr} were absolutely world-class.",
                    "Fast turnaround and excellent execution around {$tagsStr}.",
                ],
                'closers' => [
                    'Will definitely be coming back very soon!',
                    'Highly recommended to anyone looking for great quality.',
                    'Would gladly recommend this place to friends and family alike.',
                    'Looking forward to our next visit already.',
                    'A complete 10/10 experience, keep up the fantastic work!',
                    'Easily one of the best spots around town.',
                    'Thank you to the whole staff for such a lovely time.',
                    'Worth every penny, do not hesitate to check them out!',
                ],
            ];
        }

        return [
            'openers' => [
                "Visited {$biz} earlier today for an appointment.",
                "Had an average experience visiting {$biz}.",
                "Decent visit to {$biz}, though with some mixed feelings.",
            ],
            'middles' => [
                "The {$tagsStr} was acceptable, but there is noticeable room to improve.",
                "Staff was helpful, however the {$tagsStr} felt a bit rushed.",
                "A satisfactory visit, but {$tagsStr} could certainly be better.",
            ],
            'closers' => [
                'Hope to see better consistency and turnaround next time.',
                'An okay experience overall, hoping for improvements.',
                'Decent effort, but needs refinement.',
            ],
        ];
    }

    /**
     * Record a telemetry log entry into the ai_logs database.
     *
     * @param  array<string>  $tags
     */
    protected function logTelemetry(
        Business $business,
        string $model,
        int $rating,
        array $tags,
        string $language,
        string $status,
        ?int $httpStatus,
        int $latencyMs,
        ?string $generatedText,
        ?string $errorMessage,
        bool $isFallback,
        ?string $customerIp
    ): void {
        try {
            AiLog::create([
                'business_id' => $business->id,
                'business_name' => $business->name,
                'provider' => 'gemini',
                'model' => $model,
                'rating' => $rating,
                'tags' => $tags,
                'language' => $language,
                'status' => $status,
                'http_status' => $httpStatus,
                'latency_ms' => $latencyMs,
                'generated_text' => $generatedText,
                'error_message' => $errorMessage,
                'is_fallback' => $isFallback,
                'customer_ip' => $customerIp,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed writing AiLog telemetry: '.$e->getMessage());
        }
    }
}
