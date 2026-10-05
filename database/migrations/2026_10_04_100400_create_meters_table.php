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
        Schema::create('meters', function (Blueprint $table) {
            $table->comment('Master inventory of authorized smart water meters. A meter must exist here before it can be provisioned in ChirpStack.');

            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->foreignUuid('organization_id')->nullable()->constrained('organizations');
            $table->foreignUuid('service_id')->nullable()->unique()->constrained('services');

            $table->string('serial_number', 50)->unique();
            $table->string('dev_eui', 16)->unique();

            $table->string('app_key', 255)->comment('Store encrypted in production');

            $table->string('model', 100)->nullable();

            $table->string('status', 30)->default('IN_STOCK')->comment('IN_STOCK, ASSIGNED, INSTALLED, ACTIVE, RETIRED');

            $table->boolean('chirpstack_registered')->default(false);

            $table->timestampTz('created_at', 6)->default(DB::raw('now()'));
            $table->timestampTz('updated_at', 6)->default(DB::raw('now()'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meters');
    }
};
