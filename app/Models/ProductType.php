<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'active',
        'image_path',
    ];
    protected $casts = [
        'active' => 'boolean',
    ];
    /**
     * Get the parts for this product type.
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    /**
     * Get the option combinations for this product type.
     */
    public function optionCombinations(): HasMany
    {
        return $this->hasMany(OptionCombination::class);
    }

    /**
     * Get the price rules for this product type.
     */
    public function priceRules(): HasMany
    {
        return $this->hasMany(PriceRule::class);
    }
}
