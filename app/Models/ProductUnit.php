<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'unit_name',
        'conversion_factor',
        'sell_price',
        'is_base_unit',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
        'sell_price' => 'decimal:2',
        'is_base_unit' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
