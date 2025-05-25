<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_type_id',
        'name',
        'description',
        'display_order',
        'required',
        'active',
    ];

    protected $casts = [
        'required' => 'boolean',
        'active' => 'boolean',
    ];
    /**
     * Get the product type that this part belongs to.
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the options for this part.
     */
    public function options(): HasMany
    {
        return $this->hasMany(PartOption::class);
    }

    /**
     * Get the option combinations where this part is the "then" part.
     */
    public function thenCombinations(): HasMany
    {
        return $this->hasMany(OptionCombination::class, 'then_part_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
