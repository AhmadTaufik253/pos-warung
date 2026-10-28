<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'current_debt_balance',
    ];

    protected $casts = [
        'current_debt_balance' => 'decimal:2',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function debtRecords(): HasMany
    {
        return $this->hasMany(DebtRecord::class);
    }
}
