<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appearance_settings', function (Blueprint $table) {
            $table->text('business_address')->nullable()->after('business_name');
            $table->string('tax_id')->nullable()->after('business_address');
            $table->timestamp('qr_code_updated_at')->nullable();
            $table->string('qr_description')->nullable()->default('Escanéame');
        });
    }

    public function down(): void
    {
        Schema::table('appearance_settings', function (Blueprint $table) {
            $table->dropColumn(['business_address', 'tax_id', 'qr_code_updated_at', 'qr_description']);
        });
    }
};
