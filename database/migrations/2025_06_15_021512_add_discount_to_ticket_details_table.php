<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_details', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->after('unit_price');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_details', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};
