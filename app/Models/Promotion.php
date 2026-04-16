<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    // Check if promotion is valid
    public function isValid()
    {
        if (!$this->is_active) return false;
        
        $now = Carbon::now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) return false;
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        return true;
    }

    // Calculate discount amount
    public function calculateDiscount($orderAmount)
    {
        if ($orderAmount < $this->min_order_amount) return 0;
        
        if ($this->type === 'percentage') {
            $discount = $orderAmount * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }
        
        return $this->value;
    }
}
