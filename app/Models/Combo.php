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

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
