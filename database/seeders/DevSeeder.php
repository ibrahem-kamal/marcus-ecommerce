<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ProductConfigurationSeeder::class
        ]);
    }
}
