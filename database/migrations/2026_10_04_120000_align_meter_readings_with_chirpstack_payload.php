<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Line the column names up with the field names ChirpStack sends, so no
     * mental translation is needed between the uplink and the row it produces.
     */
    public function up(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {
            $table->renameColumn('reading_m3', 'volume_net_m3');
            $table->renameColumn('battery_percentage', 'battery_pct');
        });

        Schema::table('meter_readings', function (Blueprint $table) {
            $table->jsonb('active_alarms')
                ->nullable()
                ->comment('List of currently active alarm names');
        });

        // The name says it now.
        DB::statement('comment on column meter_readings.volume_net_m3 is null');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {
            $table->dropColumn('active_alarms');
        });

        Schema::table('meter_readings', function (Blueprint $table) {
            $table->renameColumn('volume_net_m3', 'reading_m3');
            $table->renameColumn('battery_pct', 'battery_percentage');
        });

        DB::statement("comment on column meter_readings.reading_m3 is 'Net volume (volumeNet_m3): the billable totalizer reading'");
    }
};
