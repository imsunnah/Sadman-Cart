<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title_en',
        'title_bn',
        'slug',
        'description_en',
        'description_bn',
        'image',
        'content_en',
        'content_bn',
        'group',
        'is_active',
    ];

    protected $appends = [
        'title',
        'description',
        'content',
    ];

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'bn' ? ($this->title_bn ?: $this->title_en) : $this->title_en;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'bn' ? ($this->description_bn ?: $this->description_en) : $this->description_en;
    }

    public function getContentAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'bn' ? ($this->content_bn ?: $this->content_en) : $this->content_en;
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getImageAttribute($value)
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
