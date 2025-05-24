<?php

namespace App\Http\Requests\Admin\PriceRule;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'primary_option_id' => 'required|exists:part_options,id',
            'dependent_option_id' => 'required|exists:part_options,id',
            'price_adjustment' => 'required|numeric',
            'adjustment_type' => 'required|string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
}