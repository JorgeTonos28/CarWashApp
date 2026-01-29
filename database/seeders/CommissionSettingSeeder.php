<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommissionSetting;

class CommissionSettingSeeder extends Seeder
{
    public function run(): void
    {
        CommissionSetting::firstOrCreate([], [
            'default_amount' => 100,
        ]);
    }
}
