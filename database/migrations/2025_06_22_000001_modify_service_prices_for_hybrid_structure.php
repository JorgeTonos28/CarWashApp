<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('service_prices', 'label')) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->string('label')->nullable()->after('service_id');
            });
        }

        Schema::table('service_prices', function (Blueprint $table) {
            $table->foreignId('vehicle_type_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('service_prices', 'label')) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropColumn('label');
            });
        }

        Schema::table('service_prices', function (Blueprint $table) {
            $table->foreignId('vehicle_type_id')->nullable(false)->change();
        });

    }
};
