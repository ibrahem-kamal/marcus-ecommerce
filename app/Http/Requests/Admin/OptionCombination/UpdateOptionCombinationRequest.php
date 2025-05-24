<?php

namespace App\Http\Requests\Admin\OptionCombination;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOptionCombinationRequest extends FormRequest
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
            'if_option_id' => 'required|exists:part_options,id',
            'then_part_id' => 'required|exists:parts,id',
            'then_option_id' => 'required|exists:part_options,id',
            'rule_type' => 'required|string',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ];
    }
}