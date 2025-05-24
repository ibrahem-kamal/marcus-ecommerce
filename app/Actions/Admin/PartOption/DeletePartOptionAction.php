<?php

namespace App\Actions\Admin\PartOption;

use App\Models\PartOption;

class DeletePartOptionAction
{
    /**
     * Handle deleting an option.
     *
     * @param PartOption $option The option to delete
     * @return array The success message
     */
    public function handle(PartOption $option): array
    {
        $option->delete();
        
        return [
            'message' => 'Option deleted successfully.'
        ];
    }
}