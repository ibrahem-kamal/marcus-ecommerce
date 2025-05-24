<?php

namespace App\Actions\Admin\PartOption;

use App\Models\Part;
use App\Models\PartOption;

class CreatePartOptionAction
{
    /**
     * Handle creating a new option for a part.
     *
     * @param Part $part The part to add the option to
     * @param array $data The validated option data
     * @return array The created option and success message
     */
    public function handle(Part $part, array $data): array
    {
        $data['part_id'] = $part->id;
        $option = PartOption::create($data);
        
        return [
            'message' => 'Option added successfully.',
            'option' => $option
        ];
    }
}