<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'employee_id', 'order_number', 'customer_name', 'customer_email', 'customer_phone',
        'customer_address', 'total_amount', 'status', 'notes'
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
