<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'sku',
        'description',
        'price',
        'buying_price',
        'package_cost',
        'stock',
        'image',
        'is_active',
        'discount_type',
        'discount_value',
        'remarks',
        'brand_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'discounted_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function gallery()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

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

    public function getDiscountedPriceAttribute()
    {
        if (!$this->discount_type) {
            return $this->price;
        }

        if ($this->discount_type === 'percentage') {
            return $this->price * (1 - $this->discount_value / 100);
        }

        return max(0, $this->price - $this->discount_value);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_active', true)->latest();
    }
}
