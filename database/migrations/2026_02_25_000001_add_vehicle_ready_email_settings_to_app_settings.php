<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->string('vehicle_ready_subject')->nullable()->after('default_low_stock');
            $table->string('vehicle_ready_greeting')->nullable()->after('vehicle_ready_subject');
            $table->text('vehicle_ready_body')->nullable()->after('vehicle_ready_greeting');
            $table->string('vehicle_ready_footer')->nullable()->after('vehicle_ready_body');
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn([
                'vehicle_ready_subject',
                'vehicle_ready_greeting',
                'vehicle_ready_body',
                'vehicle_ready_footer',
            ]);
        });
    }
};
