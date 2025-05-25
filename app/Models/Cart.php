<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'selected_options' => 'array',
        'price_adjustments' => 'array',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product in the cart.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'product_id');
    }
}
