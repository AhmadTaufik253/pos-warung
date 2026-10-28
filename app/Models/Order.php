<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'total_amount',
        'discount_amount',
        'grand_total',
        'payment_method',
        'cash_tendered',
        'change_amount',
        'status',
        'hold_label',
        'total_cogs',
        'cashier_note',
        'transaction_date',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'cash_tendered' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'total_cogs' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function debtRecord(): HasOne
    {
        return $this->hasOne(DebtRecord::class);
    }
}
