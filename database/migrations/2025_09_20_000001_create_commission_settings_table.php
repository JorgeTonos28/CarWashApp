<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('default_amount', 10, 2)->default(100.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_settings');
    }
};
