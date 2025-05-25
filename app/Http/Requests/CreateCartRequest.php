<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow anyone to create a cart (guest or authenticated)
    }

    public function rules(): array
    {
        return [
            'cart_id' => ['required', 'string'],
            'product_id' => ['required', 'exists:product_types,id'],
            'selected_options' => ['required', 'array'],
            'selected_options.options' => ['required', 'array'],
            'selected_options.price_adjustments' => ['nullable', 'array'],
            'total_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}