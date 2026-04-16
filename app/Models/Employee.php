<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'department',
        'hire_date',
        'salary',
        'is_active',
        'address',
        'avatar'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'is_active' => 'boolean',
        'salary' => 'decimal:2'
    ];

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
}
