<?php

namespace App\Actions\Admin\OptionCombination;

use App\Models\OptionCombination;

class DeleteOptionCombinationAction
{
    /**
     * Handle deleting an option combination.
     *
     * @param OptionCombination $combination The combination to delete
     * @return array The success message
     */
    public function handle(OptionCombination $combination): array
    {
        $combination->delete();
        
        return [
            'message' => 'Option combination deleted successfully.'
        ];
    }
}