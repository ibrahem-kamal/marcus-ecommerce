<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_type_id',
        'primary_option_id',
        'dependent_option_id',
        'price_adjustment',
        'adjustment_type',
        'description',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Get the product type that this price rule belongs to.
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the primary option for this price rule.
     */
    public function primaryOption(): BelongsTo
    {
        return $this->belongsTo(PartOption::class, 'primary_option_id');
    }

    /**
     * Get the dependent option for this price rule.
     */
    public function dependentOption(): BelongsTo
    {
        return $this->belongsTo(PartOption::class, 'dependent_option_id');
    }
}
