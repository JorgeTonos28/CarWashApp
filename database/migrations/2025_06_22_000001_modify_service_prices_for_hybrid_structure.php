<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_prices', function (Blueprint $table) {
            $table->string('label')->nullable()->after('service_id');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropUnique('service_prices_service_id_vehicle_type_id_unique');
            $table->foreignId('vehicle_type_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_prices', function (Blueprint $table) {
            $table->dropColumn('label');
        });

        Schema::table('service_prices', function (Blueprint $table) {
            $table->foreignId('vehicle_type_id')->nullable(false)->change();
            $table->unique(['service_id', 'vehicle_type_id']);
        });
    }
};
