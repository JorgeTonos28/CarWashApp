<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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

        if ($this->indexExists('service_prices', 'service_prices_service_id_vehicle_type_id_unique')) {
            if (!$this->indexExists('service_prices', 'service_prices_vehicle_type_id_index')) {
                Schema::table('service_prices', function (Blueprint $table) {
                    $table->index('vehicle_type_id');
                });
            }

            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropUnique('service_prices_service_id_vehicle_type_id_unique');
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

        if (!$this->indexExists('service_prices', 'service_prices_service_id_vehicle_type_id_unique')) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->unique(['service_id', 'vehicle_type_id']);
            });
        }

        if ($this->indexExists('service_prices', 'service_prices_vehicle_type_id_index')) {
            Schema::table('service_prices', function (Blueprint $table) {
                $table->dropIndex('service_prices_vehicle_type_id_index');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $result = DB::select(
            'SELECT COUNT(1) AS count FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
            [$table, $indexName]
        );

        return ($result[0]->count ?? 0) > 0;
    }
};
