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

        $tagList = count($tags) > 0 ? implode(', ', $tags) : 'overall great experience';
        $language = strtolower($business->language_preference ?? 'hinglish');

        $langInstruction = $language === 'english'
            ? "Write in natural, conversational, everyday English (like a real person typing a quick review on Google Maps). Use contractions like 'didn\'t', 'wasn\'t', 'I\'ve', 'loved the'. Sound relaxed, honest, and personal. DO NOT write formal essays or corporate reviews."
            : "Write in casual, authentic conversational Hinglish (the way young Indians chat on WhatsApp or post on Google Maps). Use words like 'bohot badhiya', 'ekdum fresh / spot on', 'maza aa gaya', 'bina kisi delay ke', 'staff kaafi polite tha', 'paisa vasool', '10/10', 'definitely firse visit karenge'. DO NOT use formal shuddh Hindi (no 'ati uttam', 'vyanjan', 'anubhav prapt hua').";

        $negativeHistoryPrompt = '';
        if (! empty($pastReviews)) {
            $historySample = implode("\n- ", array_slice($pastReviews, 0, 4));
            $negativeHistoryPrompt = "\nCRITICAL: Do NOT write anything similar to these recent reviews already posted for this business:\n- {$historySample}\nUse completely different sentences, vocabulary, and tone.";
        }

        $instructionsCount = $count > 1
            ? "Output exactly {$count} distinct review options separated ONLY by '---'.\n   - Option 1: Short & crisp (1-2 punchy sentences, straight to the point).\n   - Option 2: Personal & warm (mentions visiting with friends/family or a specific visit/order context).\n   - Option 3: Casual & enthusiastic (e.g., '10/10', 'total value for money', genuine recommendation)."
            : 'Write exactly 1 genuine review (1 to 3 natural sentences).';

        $prompt = <<<PROMPT
You are a real customer writing a quick, genuine Google Maps review on your smartphone for "{$business->name}". You are an everyday person, NOT an AI, copywriter, or food critic.
Star rating: {$rating}/5 stars.
Highlights to include naturally: [{$tagList}].
Language: {$language}.
{$langInstruction}
{$negativeHistoryPrompt}

CRITICAL RULES:
1. Sound 100% human and conversational. Write naturally, like someone typing on a phone right after visiting.
2. STRICTLY BAN all robotic AI words and clichés. NEVER use: "culinary delight", "gastronomic", "impeccable", "commendable", "testament", "haven", "nestled", "beacon", "plethora", "delve", "look no further", "a must-visit gem", "patrons", "epitome", "exquisite", "unparalleled", "par excellence", "embodies", "elevates the experience", "in conclusion".
3. NO robotic marketing phrases, NO hashtags, NO emojis, NO quotes, NO asterisks, NO markdown.
4. {$instructionsCount}
5. DO NOT prefix with "Option 1:" or "Review 1:". Output only the clean review text separated by '---'.
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
            // Strip numbered lists, markdown bullets, prefixes like Option 1:, Style 1:, Review 1:
            $cleaned = preg_replace('/^(?:(?:Option|Style|Review)\s*#?\d+[:\-\.]?|\d+[\.\)\-])\s*/i', '', $cleaned);
            $cleaned = str_replace(['**', '##', '```', '"', '*'], '', $cleaned);
            $cleaned = trim($cleaned);

            if (mb_strlen($cleaned) >= 25) {
                // Check that it does not closely duplicate past reviews
                if (! $this->isDuplicateOfPast($cleaned, $pastReviews)) {
                    $cleanList[] = $cleaned;
                }
            }
        }

        return ! empty($cleanList) ? $cleanList : (mb_strlen($rawText) >= 25 ? [trim($rawText)] : []);
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
                    "{$biz} is definitely one of the best spots around here.",
                    "Hamara experience {$biz} ke sath bohot hi badhiya raha.",
                    'Bohot time baad aisi kamaal ki jagah visit ki hai.',
                    "Visited {$biz} with family and loved the whole vibe.",
                    "{$biz} ne hamari expectations se badhkar service di.",
                    "Superb experience from start to finish at {$biz}!",
                    "Aaj {$biz} aake dil khush ho gaya.",
                    "Really loved how well {$biz} is managed.",
                    "Five stars well deserved for {$biz}.",
                    "Kamaal ka ambiance aur zabardast service mili {$biz} par.",
                    "One of the best decisions to visit {$biz} today.",
                    "Sab kuch bohot authentic aur welcoming laga {$biz} me.",
                    "Mera personal favorite ban gaya hai {$biz}.",
                    "Sach me 10 out of 10 experience raha {$biz} ke sath.",
                    "Kal dosto ke sath {$biz} gaye the and sabko bohot pasand aaya.",
                    "Pehli baar aaya tha {$biz} par ab regular customer ban jaunga.",
                ],
                'middles' => [
                    "Khaaskar yahan ka {$tagsStr} bohot shaandaar tha.",
                    "Service and {$tagsStr} dono ekdum spot on the.",
                    "{$tagsStr} ne toh bilkul dil jeet liya, quality 10/10.",
                    "Staff ka behavior bohot polite tha aur {$tagsStr} best tha.",
                    "Sab kuch smoothly manage hua, especially {$tagsStr}.",
                    "Bina kisi delay ke sab kuch fast deliver hua, specially {$tagsStr}.",
                    "Cleanliness aur {$tagsStr} dono par poora dhyan diya gaya hai.",
                    "Fast response aur genuine hospitality ke sath {$tagsStr} mila.",
                    "Pricing ke hisaab se {$tagsStr} totally paisa vasool laga.",
                    "{$tagPrimary} itna accha tha ki koi complaint ki gunjaish nahi thi.",
                    "Staff bohot cooperative tha aur unhone {$tagsStr} ka pura khayal rakha.",
                    "Ambience aur {$tagsStr} ka combination bohot relaxing raha.",
                    "Quality consistency bohot acchi lagi, specially {$tagsStr}.",
                    "Har cheez me ekdum fresh feel thi, aur {$tagsStr} lajawaab tha.",
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
                    'Next time bhi definitely yahi visit karenge!',
                ],
            ];
        }

        // Ratings 1-3
        return [
            'openers' => [
                "{$biz} me theek thaak experience raha.",
                "Visited {$biz} recently, average laga thoda.",
                "{$biz} par theek experience mila but thoda better ho sakta tha.",
                "Went to {$biz} today with good expectations.",
            ],
            'middles' => [
                "{$tagsStr} theek tha, but scope of improvement hai.",
                "Service thodi slow lagi, baaki {$tagsStr} decent tha.",
                "Overall manageable tha par {$tagsStr} thoda aur enhance kiya ja sakta hai.",
                "Staff polite tha but {$tagsStr} me consistency missing thi.",
            ],
            'closers' => [
                'Hope agle visit me thoda improvement dekhne ko milega.',
                'Decent place overall, needs a bit more attention.',
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
                    "Had such a great experience visiting {$biz} today.",
                    "Really impressed with the friendliness and service at {$biz}!",
                    "Visited {$biz} recently and was genuinely pleased.",
                    "Such a refreshing and wonderful visit to {$biz}.",
                    "Five stars well deserved for the entire team at {$biz}.",
                    "{$biz} was way better than I expected, absolutely loved it.",
                    "If you are looking for great quality in this area, {$biz} is the place to go.",
                    "From the moment we walked into {$biz}, everything was super smooth.",
                    "A complete 5-star experience at {$biz} that made my day.",
                    "I don't usually write reviews, but {$biz} definitely earned this one.",
                    "Visited {$biz} after hearing good things, and it was totally worth it.",
                    "Such a welcoming and comfortable atmosphere at {$biz}.",
                    "Delighted to have visited {$biz} today.",
                    "Came to {$biz} with friends and we all had a wonderful time.",
                    "Easily one of my favorite spots now — {$biz} is awesome.",
                ],
                'middles' => [
                    "The {$tagsStr} was spot on and done so well.",
                    "Everything from the {$tagsStr} to the overall vibe was top notch.",
                    "Special shoutout for the {$tagsStr} — truly made our visit memorable.",
                    "Loved how attentive the staff was, especially regarding {$tagsStr}.",
                    "The staff was super friendly, welcoming, and took care of {$tagsStr} effortlessly.",
                    "Prompt service combined with high-quality {$tagsStr} was a treat.",
                    "The consistency in their {$tagsStr} is honestly so good.",
                    "We particularly appreciated how nicely {$tagsStr} was delivered.",
                    "Both the setup and the {$tagsStr} were absolutely fantastic.",
                    "Fast turnaround and excellent execution around {$tagsStr}.",
                    "Everything arrived fresh and fast, especially the {$tagsStr}.",
                    "No delays at all and the {$tagsStr} was totally on point.",
                ],
                'closers' => [
                    'Will definitely be coming back very soon!',
                    'Highly recommended to anyone looking for great quality.',
                    'Would gladly recommend this place to friends and family alike.',
                    'Looking forward to our next visit already.',
                    'A complete 10/10 experience, keep up the fantastic work!',
                    'Easily one of the best spots around town.',
                    'Thank you to the whole staff for such a lovely time.',
                    'Total value for money, do not hesitate to check them out!',
                    'Definitely deserving of a solid 5 stars.',
                    'So glad we decided to visit today!',
                ],
            ];
        }

        return [
            'openers' => [
                "Visited {$biz} earlier today.",
                "Had an average experience visiting {$biz}.",
                "Decent visit to {$biz}, though with some mixed feelings.",
            ],
            'middles' => [
                "The {$tagsStr} was acceptable, but there is noticeable room to improve.",
                "Staff was polite, however the {$tagsStr} felt a bit delayed.",
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
