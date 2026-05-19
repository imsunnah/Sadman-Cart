<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'district',
        'upazila',
        'village',
        'total_amount',
        'delivery_charge',
        'delivery_location',
        'payment_method',
        'status',
        'is_active',
        'customer_remarks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function generateUniqueId()
    {
        do {
            $uniqueId = rand(100000, 999999);
        } while (static::where('id', $uniqueId)->exists());

        return $uniqueId;
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->id) {
                $order->id = static::generateUniqueId();
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
