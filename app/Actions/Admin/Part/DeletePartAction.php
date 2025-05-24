<?php

namespace App\Actions\Admin\Part;

use App\Models\Part;

class DeletePartAction
{
    /**
     * Handle deleting a part.
     *
     * @param Part $part The part to delete
     * @return array The success message
     */
    public function handle(Part $part): array
    {
        $part->delete();
        
        return [
            'message' => 'Part deleted successfully.'
        ];
    }
}