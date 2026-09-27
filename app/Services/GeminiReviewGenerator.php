<?php

namespace App\Services;

use App\Models\Business;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiReviewGenerator
{
    /**
     * Generate a natural-sounding customer review.
     *
     * @param  array<string>  $tags
     */
    public function generate(Business $business, int $rating, array $tags): string
    {
        $apiKey = config('services.gemini.key');

        if (! empty($apiKey)) {
            try {
                $reviewText = $this->callGeminiApi($apiKey, $business, $rating, $tags);
                if (! empty($reviewText)) {
                    return $reviewText;
                }
            } catch (\Throwable $e) {
                Log::warning('Gemini API generation failed, falling back to local generator: '.$e->getMessage());
            }
        }

        return $this->generateFallbackReview($business, $rating, $tags);
    }

    /**
     * Call the Google Gemini API (generateContent endpoint).
     */
    protected function callGeminiApi(string $apiKey, Business $business, int $rating, array $tags): ?string
    {
        $model = config('services.gemini.model', 'gemini-1.5-flash');
        $tagList = implode(', ', $tags);
        $language = strtolower($business->language_preference ?? 'hinglish');

        $prompt = <<<PROMPT
You are a real customer who just visited "{$business->name}".
Write a natural, conversational first-person Google review (2–3 sentences, max 45 words).
Rules:
1. Sound completely authentic, human, and casual — not like AI or marketing copy.
2. Reflect the experience from these tags: [{$tagList}], star rating: {$rating}/5.
3. Language: {$language} — if Hinglish, mix natural spoken Hindi-English like "Food bohot badhiya tha", "staff was very polite". If English, use conversational modern English.
4. Vary openers and vocabulary. Never follow fixed templates.
5. Output ONLY the review text. No quotes, no greetings, no rating numbers, no emojis.
PROMPT;

        $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $response = Http::timeout(12)->post($endpoint, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'maxOutputTokens' => 120,
                'temperature' => 0.90,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($text) {
                return trim(trim($text, " \"'\n\r"));
            }
        } else {
            Log::error('Gemini API returned error response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return null;
    }

    /**
     * Procedural fallback review generator with high variety.
     */
    protected function generateFallbackReview(Business $business, int $rating, array $tags): string
    {
        $tagsStr = count($tags) > 0 ? implode(' and ', array_slice($tags, 0, 2)) : 'overall experience';
        $language = strtolower($business->language_preference ?? 'hinglish');

        if ($language === 'english') {
            if ($rating >= 4) {
                $openers = [
                    "Had a wonderful experience at {$business->name} today.",
                    "Really impressed with {$business->name}!",
                    "Visited {$business->name} recently and loved it.",
                    "Such a pleasant visit to {$business->name}.",
                    "Five stars well deserved for {$business->name}.",
                ];
                $middles = [
                    "The {$tagsStr} was spot on and exceeded expectations.",
                    "Everything from the {$tagsStr} was handled smoothly.",
                    "Special shoutout for the {$tagsStr} — truly made the visit memorable.",
                    "Loved the attention to detail, especially regarding {$tagsStr}.",
                ];
                $closers = [
                    'Will definitely come back soon!',
                    'Highly recommended to anyone looking for great quality.',
                    'Would gladly recommend this place to friends and family.',
                    'Looking forward to visiting again.',
                ];
            } else {
                $openers = [
                    "Visited {$business->name} earlier today.",
                    "Decent experience at {$business->name}.",
                ];
                $middles = [
                    "The {$tagsStr} was okay, though there is room for improvement.",
                    "Appreciated the {$tagsStr}, but overall could be slightly better.",
                ];
                $closers = [
                    'Hope to see better consistency next time.',
                    'An average experience overall.',
                ];
            }
        } else {
            // Hinglish / Hindi-English
            if ($rating >= 4) {
                $openers = [
                    "{$business->name} me visit karke maza aa gaya!",
                    "Really amazing experience raha {$business->name} ke sath.",
                    "Aaj {$business->name} visit kiya and truly satisfied!",
                    'Bohot hi badhiya experience raha yahan.',
                    "{$business->name} is definitely worth visiting.",
                ];
                $middles = [
                    "Khaaskar yahan ka {$tagsStr} bohot shaandaar tha.",
                    "Service and {$tagsStr} dono ekdum top class the.",
                    "Loved the overall vibe and especially {$tagsStr}.",
                    "{$tagsStr} ne toh bilkul dil jeet liya, quality 10/10.",
                ];
                $closers = [
                    'Definitely recommend karunga sabko, must visit!',
                    'Firse jaroor aayenge, highly recommended!',
                    'Family and friends ke sath aane ke liye best jagah hai.',
                    'Sab kuch perfect tha, keep it up team!',
                ];
            } else {
                $openers = [
                    "{$business->name} me theek thaak experience raha.",
                    "Average visit raha {$business->name} par.",
                ];
                $middles = [
                    "{$tagsStr} theek tha, but thoda aur better ho sakta hai.",
                    "Service thodi slow lagi, baaki {$tagsStr} decent tha.",
                ];
                $closers = [
                    'Hope next time thoda improvement dekhne ko milega.',
                    'Decent place but scope of improvement hai.',
                ];
            }
        }

        $o = $openers[array_rand($openers)];
        $m = $middles[array_rand($middles)];
        $c = $closers[array_rand($closers)];

        return "{$o} {$m} {$c}";
    }
}
