<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'description',
        'is_active',
    ];

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
