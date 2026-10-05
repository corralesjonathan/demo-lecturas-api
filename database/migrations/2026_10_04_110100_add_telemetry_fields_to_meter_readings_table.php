<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {
            // Makes webhook ingestion idempotent: ChirpStack may deliver the
            // same uplink more than once, always under the same id.
            $table->uuid('deduplication_id')
                ->nullable()
                ->unique()
                ->comment('ChirpStack deduplicationId');

            $table->decimal('volume_forward_m3', 12, 3)->nullable();
            $table->decimal('volume_reverse_m3', 12, 3)->nullable();
            $table->decimal('flow_rate_lh', 10, 3)->nullable();
            $table->decimal('water_temperature_c', 5, 2)->nullable();

            $table->string('message_type', 50)->nullable();

            $table->integer('alarms_raw')->nullable()->comment('Raw alarm bitmask');
            $table->jsonb('alarms')->nullable()->comment('Decoded alarm flags');

            // Radio metadata, useful to diagnose coverage problems.
            $table->bigInteger('f_cnt')->nullable()->comment('LoRaWAN frame counter');
            $table->integer('rssi')->nullable();
            $table->decimal('snr', 4, 1)->nullable();
            $table->string('gateway_id', 16)->nullable();

            $table->index(['meter_id', 'recorded_at']);
        });

        DB::statement("comment on column meter_readings.reading_m3 is 'Net volume (volumeNet_m3): the billable totalizer reading'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meter_readings', function (Blueprint $table) {
            $table->dropIndex(['meter_id', 'recorded_at']);

            $table->dropColumn([
                'deduplication_id',
                'volume_forward_m3',
                'volume_reverse_m3',
                'flow_rate_lh',
                'water_temperature_c',
                'message_type',
                'alarms_raw',
                'alarms',
                'f_cnt',
                'rssi',
                'snr',
                'gateway_id',
            ]);
        });

        DB::statement('comment on column meter_readings.reading_m3 is null');
    }
};
