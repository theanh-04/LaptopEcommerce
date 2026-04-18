<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'loyalty_points',
        'tier',
        'total_spent',
        'total_orders',
        'last_purchase_at',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'total_spent' => 'decimal:2',
        'last_purchase_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Relationship with orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Get tier badge color
    public function getTierColorAttribute()
    {
        return match($this->tier) {
            'bronze' => 'bg-orange-900/20 text-orange-400',
            'silver' => 'bg-gray-400/20 text-gray-300',
            'gold' => 'bg-yellow-500/20 text-yellow-400',
            'platinum' => 'bg-cyan-400/20 text-cyan-400',
            default => 'bg-neutral-500/20 text-neutral-400'
        };
    }

    // Get tier name
    public function getTierNameAttribute()
    {
        return match($this->tier) {
            'bronze' => 'Đồng',
            'silver' => 'Bạc',
            'gold' => 'Vàng',
            'platinum' => 'Bạch Kim',
            default => 'Chưa xếp hạng'
        };
    }
}
