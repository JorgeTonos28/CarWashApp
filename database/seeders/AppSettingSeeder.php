<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        AppSetting::firstOrCreate([], [
            'allow_mobile_access' => false,
            'default_low_stock' => 5,
        ]);
    }
}
