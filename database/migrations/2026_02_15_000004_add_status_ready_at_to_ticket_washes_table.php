<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_washes', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('washer_id');
            $table->timestamp('ready_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_washes', function (Blueprint $table) {
            $table->dropColumn(['status', 'ready_at']);
        });
    }
};
