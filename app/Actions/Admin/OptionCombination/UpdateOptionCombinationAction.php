<?php

namespace App\Actions\Admin\OptionCombination;

use App\Models\OptionCombination;

class UpdateOptionCombinationAction
{
    /**
     * Handle updating an option combination.
     *
     * @param OptionCombination $combination The combination to update
     * @param array $data The validated combination data
     * @return array The updated combination and success message
     */
    public function handle(OptionCombination $combination, array $data): array
    {
        $combination->update($data);
        
        return [
            'message' => 'Option combination updated successfully.',
            'combination' => $combination
        ];
    }
}