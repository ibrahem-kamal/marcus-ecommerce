<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionCombination extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_type_id',
        'if_option_id',
        'then_option_id',
        'then_part_id',
        'rule_type',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    /**
     * Get the product type that this combination belongs to.
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the "if" option for this combination.
     */
    public function ifOption(): BelongsTo
    {
        return $this->belongsTo(PartOption::class, 'if_option_id');
    }

    /**
     * Get the "then" option for this combination.
     */
    public function thenOption(): BelongsTo
    {
        return $this->belongsTo(PartOption::class, 'then_option_id');
    }

    /**
     * Get the "then" part for this combination.
     */
    public function thenPart(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'then_part_id');
    }
}
