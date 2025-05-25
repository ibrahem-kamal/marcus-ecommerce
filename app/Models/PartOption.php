<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PartOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'name',
        'description',
        'base_price',
        'display_order',
        'in_stock',
        'active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'in_stock' => 'boolean',
        'active' => 'boolean',
    ];
    /**
     * Get the part that this option belongs to.
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    /**
     * Get the option combinations where this option is the "if" option.
     */
    public function ifCombinations(): HasMany
    {
        return $this->hasMany(OptionCombination::class, 'if_option_id');
    }

    /**
     * Get the option combinations where this option is the "then" option.
     */
    public function thenCombinations(): HasMany
    {
        return $this->hasMany(OptionCombination::class, 'then_option_id');
    }

    /**
     * Get the price rules where this option is the primary option.
     */
    public function primaryPriceRules(): HasMany
    {
        return $this->hasMany(PriceRule::class, 'primary_option_id');
    }

    /**
     * Get the price rules where this option is the dependent option.
     */
    public function dependentPriceRules(): HasMany
    {
        return $this->hasMany(PriceRule::class, 'dependent_option_id');
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('in_stock', true);
    }
}
