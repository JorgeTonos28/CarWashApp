<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('services') || !Schema::hasTable('service_prices') || !Schema::hasTable('generic_services') || !Schema::hasTable('generic_service_variants')) {
            return;
        }

        $services = DB::table('services')
            ->where('active', true)
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('service_prices')
                    ->whereColumn('service_prices.service_id', 'services.id')
                    ->whereNull('service_prices.vehicle_type_id');
            })
            ->get(['id', 'name']);

        foreach ($services as $service) {
            $genericService = DB::table('generic_services')->where('name', $service->name)->first();
            if (! $genericService) {
                $genericServiceId = DB::table('generic_services')->insertGetId([
                    'name' => $service->name,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $genericServiceId = $genericService->id;
            }

            $prices = DB::table('service_prices')
                ->where('service_id', $service->id)
                ->whereNull('vehicle_type_id')
                ->get(['id', 'label', 'price']);

            foreach ($prices as $price) {
                $variantName = $price->label ?: 'Precio';
                $exists = DB::table('generic_service_variants')
                    ->where('generic_service_id', $genericServiceId)
                    ->where('name', $variantName)
                    ->exists();

                if (! $exists) {
                    DB::table('generic_service_variants')->insert([
                        'generic_service_id' => $genericServiceId,
                        'name' => $variantName,
                        'price' => $price->price,
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // No-op: backfill only.
    }
};
