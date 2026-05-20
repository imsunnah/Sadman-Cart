<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value_en', 'value_bn', 'group'];

    protected $appends = ['value'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        // For backwards compatibility and setting defaults, set value_en
        return self::updateOrCreate(
            ['key' => $key],
            ['value_en' => $value, 'group' => $group]
        );
    }

    public function getValueAttribute()
    {
        $locale = app()->getLocale();
        $val = $locale === 'bn' ? ($this->value_bn ?: $this->value_en) : $this->value_en;
        
        if (!$val) {
            return null;
        }

        $key = $this->attributes['key'] ?? null;
        if (in_array($key, ['site_logo', 'hero_static_image', 'site_favicon'])) {
            return $this->resolveImageUrl($val);
        }

        if ($key === 'slider_images') {
            $images = json_decode($val, true);
            if (is_array($images)) {
                return json_encode(array_map(function($img) {
                    return $this->resolveImageUrl($img);
                }, $images));
            }
        }

        return $val;
    }

    private function resolveImageUrl($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            if (str_contains($value, 'http://localhost/')) {
                $value = str_replace('http://localhost/', '/', $value);
            } else {
                return $value;
            }
        }

        $rawPath = ltrim($value, '/');
        if (str_starts_with($rawPath, 'public/')) {
            $rawPath = substr($rawPath, 7);
        }
        if (str_starts_with($rawPath, 'storage/')) {
            $rawPath = substr($rawPath, 8);
        }

        $url = asset('storage/' . $rawPath);
        return parse_url($url, PHP_URL_PATH) ?? '/storage/' . $rawPath;
    }
}
