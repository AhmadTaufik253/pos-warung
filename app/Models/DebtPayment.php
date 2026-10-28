<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebtPayment extends Model
{
    protected $fillable = [
        'debt_record_id',
        'payment_amount',
        'payment_method',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function debtRecord(): BelongsTo
    {
        return $this->belongsTo(DebtRecord::class);
    }
}
