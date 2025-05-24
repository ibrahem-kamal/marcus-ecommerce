<?php

namespace App\Actions\Admin\Part;

use App\Models\Part;

class UpdatePartAction
{
    /**
     * Handle updating a part.
     *
     * @param Part $part The part to update
     * @param array $data The validated part data
     * @return array The updated part and success message
     */
    public function handle(Part $part, array $data): array
    {
        $part->update($data);
        
        return [
            'message' => 'Part updated successfully.',
            'part' => $part
        ];
    }
}