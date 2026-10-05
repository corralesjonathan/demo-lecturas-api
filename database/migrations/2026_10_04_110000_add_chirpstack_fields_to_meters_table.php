<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->uuid('chirpstack_device_profile_id')
                ->nullable()
                ->comment('Device profile the meter is provisioned against in ChirpStack');

            $table->string('chirpstack_device_name', 100)
                ->nullable()
                ->comment('Device name in ChirpStack, e.g. MDA-03-5');

            $table->unsignedBigInteger('device_serial_number')
                ->nullable()
                ->comment('Serial number the device itself reports in its uplinks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meters', function (Blueprint $table) {
            $table->dropColumn([
                'chirpstack_device_profile_id',
                'chirpstack_device_name',
                'device_serial_number',
            ]);
        });
    }
};
