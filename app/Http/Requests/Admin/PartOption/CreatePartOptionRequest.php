<?php

namespace App\Http\Requests\Admin\PartOption;

use Illuminate\Foundation\Http\FormRequest;

class CreatePartOptionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'display_order' => 'integer',
            'in_stock' => 'boolean',
            'active' => 'boolean',
        ];
    }
}