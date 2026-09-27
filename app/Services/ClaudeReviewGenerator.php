<?php

namespace App\Services;

use App\Models\Business;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeReviewGenerator
{
    /**
     * Generate a natural-sounding customer review.
     *
     * @param  array<string>  $tags
     */
    public function generate(Business $business, int $rating, array $tags): string
    {
        $apiKey = config('services.anthropic.key');

        if (! empty($apiKey)) {
            try {
                $reviewText = $this->callClaudeApi($apiKey, $business, $rating, $tags);
                if (! empty($reviewText)) {
                    return $reviewText;
                }
            } catch (\Throwable $e) {
                Log::warning('Claude API generation failed, falling back to local generator: '.$e->getMessage());
            }
        }

        return $this->generateFallbackReview($business, $rating, $tags);
    }

    /**
     * Call Anthropic Claude API messages endpoint.
     */
    protected function callClaudeApi(string $apiKey, Business $business, int $rating, array $tags): ?string
    {
        $model = config('services.anthropic.model', 'claude-3-5-haiku-20241022');
        $tagList = implode(', ', $tags);
        $language = strtolower($business->language_preference ?? 'hinglish');

        $systemPrompt = <<<PROMPT
You are a real, genuine customer who just visited "{$business->name}".
Write a natural, conversational first-person Google review (2-3 sentences, maximum 45 words).
Rules:
1. Sound completely authentic, human, and casual — not like promotional marketing or AI copy.
2. Incorporate the experience details indicated by the tags: [{$tagList}] and star rating: {$rating}/5.
3. Language style: {$language} (if Hinglish, use natural spoken Indian Hindi-English mix like 'Food bohot badhiya tha', 'staff was very polite', 'worth visiting again'). If English, use natural conversational modern English.
4. Vary sentence structure, openers, and vocabulary every time. Do not follow fixed formulas.
5. Output ONLY the review text itself. No quotation marks, no greetings, no rating scores, no emojis spam.
PROMPT;

        $userPrompt = "Write a {$rating}-star review for {$business->name} highlighting: {$tagList}. Language style: {$language}. Make it sound authentic and spontaneous.";

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(12)->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'max_tokens' => 150,
            'temperature' => 0.88,
            'system' => $systemPrompt,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userPrompt,
                ],
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['content'][0]['text'] ?? null;
            if ($text) {
                // Clean up any outer quotes or extra whitespaces
                return trim(trim($text, " \"'\n\r"));
            }
        } else {
            Log::error('Claude API returned error response', [
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
