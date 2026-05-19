<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerMessage extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'admin_id',
        'is_from_admin',
        'message',
    ];

    protected $casts = [
        'is_from_admin' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
