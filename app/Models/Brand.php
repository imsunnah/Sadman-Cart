<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name_en',
        'name_bn',
        'slug',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'name',
    ];

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'bn' ? ($this->name_bn ?: $this->name_en) : $this->name_en;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'brand_category');
    }
}
