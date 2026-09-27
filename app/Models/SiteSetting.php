<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Get a setting by key with a fallback default.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        return Cache::remember("site_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value and bust cache.
     */
    public static function set(string $key, ?string $value, string $group = 'general'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("site_setting_{$key}");

        return $setting;
    }

    /**
     * Get the active brand name.
     */
    public static function brandName(): string
    {
        return static::get('brand_name', 'ReviewBooster') ?: 'ReviewBooster';
    }

    /**
     * Get the active brand tagline or portal subtext.
     */
    public static function brandTagline(): string
    {
        return static::get('brand_tagline', 'Merchant Portal') ?: 'Merchant Portal';
    }

    /**
     * Get the resolved logo URL, if customized.
     */
    public static function logoUrl(): ?string
    {
        $logo = static::get('site_logo');
        if (! $logo) {
            return null;
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://') || str_starts_with($logo, '/')) {
            return $logo;
        }

        return asset('storage/' . ltrim($logo, '/'));
    }

    /**
     * Get the resolved favicon URL, if customized.
     */
    public static function faviconUrl(): ?string
    {
        $favicon = static::get('site_favicon');
        if (! $favicon) {
            return null;
        }

        if (str_starts_with($favicon, 'http://') || str_starts_with($favicon, 'https://') || str_starts_with($favicon, '/')) {
            return $favicon;
        }

        return asset('storage/' . ltrim($favicon, '/'));
    }
}
