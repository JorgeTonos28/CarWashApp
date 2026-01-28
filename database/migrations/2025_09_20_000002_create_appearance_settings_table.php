<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appearance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('CarWash App');
            $table->timestamp('logo_updated_at')->nullable();
            $table->timestamp('login_logo_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appearance_settings');
    }
};
