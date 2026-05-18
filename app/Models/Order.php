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

    public static function generateUniqueDateTimeId()
    {
        do {
            // YYMMDDHHMMSS (12 digits) + 3 random digits = 15 digits total
            $timestamp = now()->format('ymdHis');
            $randomSuffix = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
            $uniqueId = (int) ($timestamp . $randomSuffix);
        } while (static::where('id', $uniqueId)->exists());

        return $uniqueId;
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->id) {
                $order->id = static::generateUniqueDateTimeId();
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
