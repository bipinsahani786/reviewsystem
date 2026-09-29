<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use App\Models\Business;
use App\Models\SiteSetting;
use App\Services\GeminiReviewGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiLogController extends Controller
{
    /**
     * Display AI generation telemetry, error diagnostics, and rate limit logs.
     */
    public function index(Request $request): View
    {
        $this->authorizeSuperAdmin();

        $query = AiLog::with('business')->latest();

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'rate_limit') {
                $query->where(function ($q) {
                    $q->where('status', 'rate_limit')->orWhere('http_status', 429);
                });
            } elseif ($request->status === 'fallback') {
                $query->where(function ($q) {
                    $q->where('is_fallback', true)->orWhere('status', 'fallback');
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        // Business Filter
        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('generated_text', 'like', "%{$search}%")
                    ->orWhere('error_message', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        // Platform-wide AI Metrics
        $totalCalls = AiLog::count();
        $successCount = AiLog::where('status', 'success')->count();
        $rateLimitCount = AiLog::where('status', 'rate_limit')->orWhere('http_status', 429)->count();
        $fallbackCount = AiLog::where('is_fallback', true)->orWhere('status', 'fallback')->count();
        $errorCount = AiLog::whereIn('status', ['error', 'timeout', 'unavailable'])->count();

        $successRate = $totalCalls > 0 ? round(($successCount / $totalCalls) * 100, 1) : 100.0;
        $avgLatency = (int) AiLog::where('status', 'success')->avg('latency_ms');

        // Recent 24 Hours Statistics
        $last24hTotal = AiLog::where('created_at', '>=', now()->subDay())->count();
        $last24hRateLimits = AiLog::where('created_at', '>=', now()->subDay())
            ->where(function ($q) {
                $q->where('status', 'rate_limit')->orWhere('http_status', 429);
            })->count();

        $businesses = Business::orderBy('name')->get();
        $configuredApiKey = SiteSetting::get('gemini_api_key') ?: config('services.gemini.key');
        $maskedKey = $configuredApiKey ? substr($configuredApiKey, 0, 8).'••••••••'.substr($configuredApiKey, -4) : null;

        return view('admin.ai-logs.index', compact(
            'logs',
            'totalCalls',
            'successCount',
            'rateLimitCount',
            'fallbackCount',
            'errorCount',
            'successRate',
            'avgLatency',
            'last24hTotal',
            'last24hRateLimits',
            'businesses',
            'maskedKey'
        ));
    }

    /**
     * Run an instant diagnostic test of the Gemini AI API.
     */
    public function testConnection(Request $request, GeminiReviewGenerator $generator): JsonResponse
    {
        $this->authorizeSuperAdmin();

        $business = Business::first() ?? new Business([
            'name' => 'ReviewBooster Bistro',
            'language_preference' => 'hinglish',
        ]);

        $startTime = microtime(true);
        $tags = ['Cozy Ambience', 'Fast Friendly Service', 'Delicious Food'];
        $review = $generator->generate($business, 5, $tags, $request->ip());
        $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

        $latestLog = AiLog::latest()->first();

        return response()->json([
            'success' => true,
            'review' => $review,
            'latency_ms' => $latencyMs,
            'model' => $latestLog?->model ?? 'gemini-3.5-flash-lite',
            'status' => $latestLog?->status ?? 'success',
            'is_fallback' => $latestLog?->is_fallback ?? false,
            'http_status' => $latestLog?->http_status ?? 200,
        ]);
    }

    /**
     * Clear all telemetry logs.
     */
    public function clear(): RedirectResponse
    {
        $this->authorizeSuperAdmin();

        AiLog::truncate();

        return back()->with('success', 'AI telemetry logs cleared successfully.');
    }

    protected function authorizeSuperAdmin(): void
    {
        if (! Auth::user()?->isSuperAdmin()) {
            abort(403, 'Unauthorized access. Super admin required.');
        }
    }
}
