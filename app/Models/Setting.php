<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    public function getValueAttribute($value)
    {
        if (!$value) {
            return null;
        }

        $key = $this->attributes['key'] ?? null;
        if (in_array($key, ['site_logo', 'hero_static_image'])) {
            return $this->resolveImageUrl($value);
        }

        if ($key === 'slider_images') {
            $images = json_decode($value, true);
            if (is_array($images)) {
                return json_encode(array_map(function($img) {
                    return $this->resolveImageUrl($img);
                }, $images));
            }
        }

        return $value;
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
