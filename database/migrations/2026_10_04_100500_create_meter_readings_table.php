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
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->comment('Telemetry received from ChirpStack.');

            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->foreignUuid('meter_id')->constrained('meters');

            $table->decimal('reading_m3', 12, 3);
            $table->integer('battery_percentage')->nullable();

            $table->timestampTz('recorded_at', 6);

            $table->timestampTz('created_at', 6)->default(DB::raw('now()'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
