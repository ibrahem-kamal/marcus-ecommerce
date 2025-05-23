<?php

namespace Database\Seeders;

use App\Models\OptionCombination;
use App\Models\Part;
use App\Models\PartOption;
use App\Models\PriceRule;
use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if Bicycle product type already exists
        $bicycle = ProductType::query()->where('name', 'Bicycle')->first();

        if (!$bicycle) {
            // Create Bicycle product type
            $bicycle = ProductType::create([
                'name' => 'Bicycle',
                'description' => 'Customize your own bicycle with various options.',
                'active' => true,
            ]);
        } 

        // Create or retrieve parts for Bicycle
        $framePart = Part::query()->where('product_type_id', $bicycle->id)
            ->where('name', 'Frame Type')
            ->first();

        if (!$framePart) {
            $framePart = Part::create([
                'product_type_id' => $bicycle->id,
                'name' => 'Frame Type',
                'description' => 'The type of frame for your bicycle.',
                'display_order' => 1,
                'required' => true,
                'active' => true,
            ]);
        }

        $frameFinishPart = Part::query()->where('product_type_id', $bicycle->id)
            ->where('name', 'Frame Finish')
            ->first();

        if (!$frameFinishPart) {
            $frameFinishPart = Part::create([
                'product_type_id' => $bicycle->id,
                'name' => 'Frame Finish',
                'description' => 'The finish of your bicycle frame.',
                'display_order' => 2,
                'required' => true,
                'active' => true,
            ]);
        }

        $wheelsPart = Part::query()->where('product_type_id', $bicycle->id)
            ->where('name', 'Wheels')
            ->first();

        if (!$wheelsPart) {
            $wheelsPart = Part::create([
                'product_type_id' => $bicycle->id,
                'name' => 'Wheels',
                'description' => 'The type of wheels for your bicycle.',
                'display_order' => 3,
                'required' => true,
                'active' => true,
            ]);
        }

        $rimColorPart = Part::query()->where('product_type_id', $bicycle->id)
            ->where('name', 'Rim Color')
            ->first();

        if (!$rimColorPart) {
            $rimColorPart = Part::create([
                'product_type_id' => $bicycle->id,
                'name' => 'Rim Color',
                'description' => 'The color of your bicycle rims.',
                'display_order' => 4,
                'required' => true,
                'active' => true,
            ]);
        }

        $chainPart = Part::query()->where('product_type_id', $bicycle->id)
            ->where('name', 'Chain')
            ->first();

        if (!$chainPart) {
            $chainPart = Part::create([
                'product_type_id' => $bicycle->id,
                'name' => 'Chain',
                'description' => 'The type of chain for your bicycle.',
                'display_order' => 5,
                'required' => true,
                'active' => true,
            ]);
        }

        // Create a new part for additional customization
        $accessoriesPart = Part::query()->where('product_type_id', $bicycle->id)
            ->where('name', 'Accessories')
            ->first();

        if (!$accessoriesPart) {
            $accessoriesPart = Part::create([
                'product_type_id' => $bicycle->id,
                'name' => 'Accessories',
                'description' => 'Additional accessories for your bicycle.',
                'display_order' => 6,
                'required' => false,
                'active' => true,
            ]);
        }

        // Create or retrieve options for Frame Type
        $fullSuspensionFrame = PartOption::query()->where('part_id', $framePart->id)
            ->where('name', 'Full-suspension')
            ->first();

        if (!$fullSuspensionFrame) {
            $fullSuspensionFrame = PartOption::create([
                'part_id' => $framePart->id,
                'name' => 'Full-suspension',
                'description' => 'A frame with front and rear suspension.',
                'base_price' => 130.00,
                'display_order' => 1,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $diamondFrame = PartOption::query()->where('part_id', $framePart->id)
            ->where('name', 'Diamond')
            ->first();

        if (!$diamondFrame) {
            $diamondFrame = PartOption::create([
                'part_id' => $framePart->id,
                'name' => 'Diamond',
                'description' => 'A traditional diamond-shaped frame.',
                'base_price' => 100.00,
                'display_order' => 2,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $stepThroughFrame = PartOption::query()->where('part_id', $framePart->id)
            ->where('name', 'Step-through')
            ->first();

        if (!$stepThroughFrame) {
            $stepThroughFrame = PartOption::create([
                'part_id' => $framePart->id,
                'name' => 'Step-through',
                'description' => 'A frame with a low or absent top tube.',
                'base_price' => 110.00,
                'display_order' => 3,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Create or retrieve options for Frame Finish
        $matteFinish = PartOption::query()->where('part_id', $frameFinishPart->id)
            ->where('name', 'Matte')
            ->first();

        if (!$matteFinish) {
            $matteFinish = PartOption::create([
                'part_id' => $frameFinishPart->id,
                'name' => 'Matte',
                'description' => 'A non-reflective finish.',
                'base_price' => 35.00, // Base price for diamond frame
                'display_order' => 1,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $shinyFinish = PartOption::query()->where('part_id', $frameFinishPart->id)
            ->where('name', 'Shiny')
            ->first();

        if (!$shinyFinish) {
            $shinyFinish = PartOption::create([
                'part_id' => $frameFinishPart->id,
                'name' => 'Shiny',
                'description' => 'A glossy, reflective finish.',
                'base_price' => 30.00,
                'display_order' => 2,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Add a new finish option
        $brushedFinish = PartOption::query()->where('part_id', $frameFinishPart->id)
            ->where('name', 'Brushed')
            ->first();

        if (!$brushedFinish) {
            $brushedFinish = PartOption::create([
                'part_id' => $frameFinishPart->id,
                'name' => 'Brushed',
                'description' => 'A brushed metal finish.',
                'base_price' => 40.00,
                'display_order' => 3,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Create or retrieve options for Wheels
        $roadWheels = PartOption::query()->where('part_id', $wheelsPart->id)
            ->where('name', 'Road Wheels')
            ->first();

        if (!$roadWheels) {
            $roadWheels = PartOption::create([
                'part_id' => $wheelsPart->id,
                'name' => 'Road Wheels',
                'description' => 'Lightweight wheels for paved roads.',
                'base_price' => 80.00,
                'display_order' => 1,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $mountainWheels = PartOption::query()->where('part_id', $wheelsPart->id)
            ->where('name', 'Mountain Wheels')
            ->first();

        if (!$mountainWheels) {
            $mountainWheels = PartOption::create([
                'part_id' => $wheelsPart->id,
                'name' => 'Mountain Wheels',
                'description' => 'Durable wheels for off-road trails.',
                'base_price' => 95.00,
                'display_order' => 2,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $fatBikeWheels = PartOption::query()->where('part_id', $wheelsPart->id)
            ->where('name', 'Fat Bike Wheels')
            ->first();

        if (!$fatBikeWheels) {
            $fatBikeWheels = PartOption::create([
                'part_id' => $wheelsPart->id,
                'name' => 'Fat Bike Wheels',
                'description' => 'Extra-wide wheels for snow and sand.',
                'base_price' => 120.00,
                'display_order' => 3,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Add a new wheels option
        $hybridWheels = PartOption::query()->where('part_id', $wheelsPart->id)
            ->where('name', 'Hybrid Wheels')
            ->first();

        if (!$hybridWheels) {
            $hybridWheels = PartOption::create([
                'part_id' => $wheelsPart->id,
                'name' => 'Hybrid Wheels',
                'description' => 'Versatile wheels for both road and light trails.',
                'base_price' => 90.00,
                'display_order' => 4,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Create or retrieve options for Rim Color
        $redRimColor = PartOption::query()->where('part_id', $rimColorPart->id)
            ->where('name', 'Red')
            ->first();

        if (!$redRimColor) {
            $redRimColor = PartOption::create([
                'part_id' => $rimColorPart->id,
                'name' => 'Red',
                'description' => 'Bright red rims.',
                'base_price' => 25.00,
                'display_order' => 1,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $blackRimColor = PartOption::query()->where('part_id', $rimColorPart->id)
            ->where('name', 'Black')
            ->first();

        if (!$blackRimColor) {
            $blackRimColor = PartOption::create([
                'part_id' => $rimColorPart->id,
                'name' => 'Black',
                'description' => 'Classic black rims.',
                'base_price' => 15.00,
                'display_order' => 2,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $blueRimColor = PartOption::query()->where('part_id', $rimColorPart->id)
            ->where('name', 'Blue')
            ->first();

        if (!$blueRimColor) {
            $blueRimColor = PartOption::create([
                'part_id' => $rimColorPart->id,
                'name' => 'Blue',
                'description' => 'Stylish blue rims.',
                'base_price' => 20.00,
                'display_order' => 3,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Add a new rim color option
        $silverRimColor = PartOption::query()->where('part_id', $rimColorPart->id)
            ->where('name', 'Silver')
            ->first();

        if (!$silverRimColor) {
            $silverRimColor = PartOption::create([
                'part_id' => $rimColorPart->id,
                'name' => 'Silver',
                'description' => 'Elegant silver rims.',
                'base_price' => 18.00,
                'display_order' => 4,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Create or retrieve options for Chain
        $singleSpeedChain = PartOption::query()->where('part_id', $chainPart->id)
            ->where('name', 'Single-speed Chain')
            ->first();

        if (!$singleSpeedChain) {
            $singleSpeedChain = PartOption::create([
                'part_id' => $chainPart->id,
                'name' => 'Single-speed Chain',
                'description' => 'A simple chain for bikes with one gear.',
                'base_price' => 43.00,
                'display_order' => 1,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $eightSpeedChain = PartOption::query()->where('part_id', $chainPart->id)
            ->where('name', '8-speed Chain')
            ->first();

        if (!$eightSpeedChain) {
            $eightSpeedChain = PartOption::create([
                'part_id' => $chainPart->id,
                'name' => '8-speed Chain',
                'description' => 'A chain for bikes with 8 gears.',
                'base_price' => 55.00,
                'display_order' => 2,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Add a new chain option
        $tenSpeedChain = PartOption::query()->where('part_id', $chainPart->id)
            ->where('name', '10-speed Chain')
            ->first();

        if (!$tenSpeedChain) {
            $tenSpeedChain = PartOption::create([
                'part_id' => $chainPart->id,
                'name' => '10-speed Chain',
                'description' => 'A chain for bikes with 10 gears.',
                'base_price' => 65.00,
                'display_order' => 3,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Create options for Accessories
        $bikeLight = PartOption::query()->where('part_id', $accessoriesPart->id)
            ->where('name', 'Bike Light')
            ->first();

        if (!$bikeLight) {
            $bikeLight = PartOption::create([
                'part_id' => $accessoriesPart->id,
                'name' => 'Bike Light',
                'description' => 'LED light for night riding.',
                'base_price' => 25.00,
                'display_order' => 1,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $waterBottleHolder = PartOption::query()->where('part_id', $accessoriesPart->id)
            ->where('name', 'Water Bottle Holder')
            ->first();

        if (!$waterBottleHolder) {
            $waterBottleHolder = PartOption::create([
                'part_id' => $accessoriesPart->id,
                'name' => 'Water Bottle Holder',
                'description' => 'Holder for your water bottle.',
                'base_price' => 15.00,
                'display_order' => 2,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        $bikeBell = PartOption::query()->where('part_id', $accessoriesPart->id)
            ->where('name', 'Bike Bell')
            ->first();

        if (!$bikeBell) {
            $bikeBell = PartOption::create([
                'part_id' => $accessoriesPart->id,
                'name' => 'Bike Bell',
                'description' => 'Bell to alert pedestrians.',
                'base_price' => 10.00,
                'display_order' => 3,
                'in_stock' => true,
                'active' => true,
            ]);
        }

        // Create or retrieve option combinations (rules)

        // If mountain wheels are selected, then only full-suspension frame is available
        $mountainWheelsRule = OptionCombination::query()
            ->where('product_type_id', $bicycle->id)
            ->where('if_option_id', $mountainWheels->id)
            ->where('then_option_id', $fullSuspensionFrame->id)
            ->where('rule_type', 'required')
            ->first();

        if (!$mountainWheelsRule) {
            $mountainWheelsRule = OptionCombination::create([
                'product_type_id' => $bicycle->id,
                'if_option_id' => $mountainWheels->id,
                'then_option_id' => $fullSuspensionFrame->id,
                'then_part_id' => $framePart->id, // Set to the part ID of the option
                'rule_type' => 'required',
                'description' => 'Mountain wheels require a full-suspension frame for optimal performance.',
                'active' => true,
            ]);
        }

        // If fat bike wheels are selected, then red rim color is unavailable
        $fatBikeWheelsRule = OptionCombination::query()
            ->where('product_type_id', $bicycle->id)
            ->where('if_option_id', $fatBikeWheels->id)
            ->where('then_option_id', $redRimColor->id)
            ->where('rule_type', 'prohibited')
            ->first();

        if (!$fatBikeWheelsRule) {
            $fatBikeWheelsRule = OptionCombination::create([
                'product_type_id' => $bicycle->id,
                'if_option_id' => $fatBikeWheels->id,
                'then_option_id' => $redRimColor->id,
                'then_part_id' => $rimColorPart->id, // Set to the part ID of the option
                'rule_type' => 'prohibited',
                'description' => 'Red rim color is not available for fat bike wheels.',
                'active' => true,
            ]);
        }

        // Add a new rule: If hybrid wheels are selected, then brushed finish is required
        $hybridWheelsRule = OptionCombination::query()
            ->where('product_type_id', $bicycle->id)
            ->where('if_option_id', $hybridWheels->id)
            ->where('then_option_id', $brushedFinish->id)
            ->where('rule_type', 'required')
            ->first();

        if (!$hybridWheelsRule) {
            $hybridWheelsRule = OptionCombination::create([
                'product_type_id' => $bicycle->id,
                'if_option_id' => $hybridWheels->id,
                'then_option_id' => $brushedFinish->id,
                'then_part_id' => $frameFinishPart->id, // Set to the part ID of the option
                'rule_type' => 'required',
                'description' => 'Hybrid wheels look best with a brushed finish.',
                'active' => true,
            ]);
        }

        // Add a new rule: If 10-speed chain is selected, then bike bell is prohibited
        $tenSpeedChainRule = OptionCombination::query()
            ->where('product_type_id', $bicycle->id)
            ->where('if_option_id', $tenSpeedChain->id)
            ->where('then_option_id', $bikeBell->id)
            ->where('rule_type', 'prohibited')
            ->first();

        if (!$tenSpeedChainRule) {
            $tenSpeedChainRule = OptionCombination::create([
                'product_type_id' => $bicycle->id,
                'if_option_id' => $tenSpeedChain->id,
                'then_option_id' => $bikeBell->id,
                'then_part_id' => $accessoriesPart->id, // Set to the part ID of the option
                'rule_type' => 'prohibited',
                'description' => '10-speed chains are not compatible with bike bells.',
                'active' => true,
            ]);
        }

        // Create or retrieve price rules

        // Matte finish on full-suspension frame costs more
        $matteOnFullSuspensionRule = PriceRule::query()
            ->where('product_type_id', $bicycle->id)
            ->where('primary_option_id', $fullSuspensionFrame->id)
            ->where('dependent_option_id', $matteFinish->id)
            ->first();

        if (!$matteOnFullSuspensionRule) {
            $matteOnFullSuspensionRule = PriceRule::create([
                'product_type_id' => $bicycle->id,
                'primary_option_id' => $fullSuspensionFrame->id,
                'dependent_option_id' => $matteFinish->id,
                'price_adjustment' => 15.00, // Additional cost
                'adjustment_type' => 'fixed',
                'description' => 'Matte finish costs more on a full-suspension frame due to larger surface area.',
                'active' => true,
            ]);
        }

        // Matte finish on step-through frame costs more
        $matteOnStepThroughRule = PriceRule::query()
            ->where('product_type_id', $bicycle->id)
            ->where('primary_option_id', $stepThroughFrame->id)
            ->where('dependent_option_id', $matteFinish->id)
            ->first();

        if (!$matteOnStepThroughRule) {
            $matteOnStepThroughRule = PriceRule::create([
                'product_type_id' => $bicycle->id,
                'primary_option_id' => $stepThroughFrame->id,
                'dependent_option_id' => $matteFinish->id,
                'price_adjustment' => 10.00, // Additional cost
                'adjustment_type' => 'fixed',
                'description' => 'Matte finish costs more on a step-through frame due to larger surface area.',
                'active' => true,
            ]);
        }

        // Add new price rules for the new options

        // Brushed finish on hybrid wheels costs more
        $brushedOnHybridRule = PriceRule::query()
            ->where('product_type_id', $bicycle->id)
            ->where('primary_option_id', $hybridWheels->id)
            ->where('dependent_option_id', $brushedFinish->id)
            ->first();

        if (!$brushedOnHybridRule) {
            $brushedOnHybridRule = PriceRule::create([
                'product_type_id' => $bicycle->id,
                'primary_option_id' => $hybridWheels->id,
                'dependent_option_id' => $brushedFinish->id,
                'price_adjustment' => 20.00, // Additional cost
                'adjustment_type' => 'fixed',
                'description' => 'Brushed finish costs more on hybrid wheels due to the special application process.',
                'active' => true,
            ]);
        }

        // Silver rim color on 10-speed chain costs less
        $silverOn10SpeedRule = PriceRule::query()
            ->where('product_type_id', $bicycle->id)
            ->where('primary_option_id', $tenSpeedChain->id)
            ->where('dependent_option_id', $silverRimColor->id)
            ->first();

        if (!$silverOn10SpeedRule) {
            $silverOn10SpeedRule = PriceRule::create([
                'product_type_id' => $bicycle->id,
                'primary_option_id' => $tenSpeedChain->id,
                'dependent_option_id' => $silverRimColor->id,
                'price_adjustment' => -5.00, // Discount
                'adjustment_type' => 'fixed',
                'description' => 'Silver rim color is discounted when paired with a 10-speed chain.',
                'active' => true,
            ]);
        }
    }
}
