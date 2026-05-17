<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'original_price',
        'image',
        'is_active',
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

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
