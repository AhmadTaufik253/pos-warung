<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'barcode',
        'name',
        'base_unit',
        'base_buy_price',
        'base_sell_price',
        'current_stock',
        'min_stock_alert',
        'allow_nominal_buy',
        'is_active',
    ];

    protected $casts = [
        'base_buy_price' => 'decimal:2',
        'base_sell_price' => 'decimal:2',
        'current_stock' => 'decimal:3',
        'min_stock_alert' => 'decimal:3',
        'allow_nominal_buy' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
