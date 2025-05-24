<?php

namespace App\Actions\Admin\PartOption;

use App\Models\PartOption;

class UpdatePartOptionAction
{
    /**
     * Handle updating an option.
     *
     * @param PartOption $option The option to update
     * @param array $data The validated option data
     * @return array The updated option and success message
     */
    public function handle(PartOption $option, array $data): array
    {
        $option->update($data);
        
        return [
            'message' => 'Option updated successfully.',
            'option' => $option
        ];
    }
}