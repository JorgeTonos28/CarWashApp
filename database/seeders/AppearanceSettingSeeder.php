<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppearanceSetting;

class AppearanceSettingSeeder extends Seeder
{
    public function run(): void
    {
        AppearanceSetting::firstOrCreate([], [
            'business_name' => 'CarWash App',
        ]);
    }
}
