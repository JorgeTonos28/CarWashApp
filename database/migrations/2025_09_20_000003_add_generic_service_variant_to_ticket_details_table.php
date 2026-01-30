<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_details', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket_details', 'generic_service_variant_id')) {
                $table->foreignId('generic_service_variant_id')
                    ->nullable()
                    ->constrained('generic_service_variants')
                    ->nullOnDelete()
                    ->after('drink_id');
            }
        });

        if (Schema::hasColumn('ticket_details', 'type')) {
            DB::statement("ALTER TABLE ticket_details MODIFY type ENUM('service','product','drink','extra','generic_service')");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ticket_details', 'type')) {
            DB::statement("ALTER TABLE ticket_details MODIFY type ENUM('service','product','drink','extra')");
        }

        Schema::table('ticket_details', function (Blueprint $table) {
            if (Schema::hasColumn('ticket_details', 'generic_service_variant_id')) {
                $table->dropForeign(['generic_service_variant_id']);
                $table->dropColumn('generic_service_variant_id');
            }
        });
    }
};
