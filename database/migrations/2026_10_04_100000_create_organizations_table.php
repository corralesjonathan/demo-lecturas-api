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
        Schema::create('organizations', function (Blueprint $table) {
            $table->comment('Water service provider: ASADA, municipality, public company or institution.');

            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->uuid('chirpstack_tenant_id')->unique();
            $table->uuid('chirpstack_application_id');

            $table->string('legal_id', 10)->unique()->comment('Cédula jurídica');
            $table->string('legal_name', 200);
            $table->string('display_name', 100);
            $table->string('contact_phone', 20)->nullable();

            $table->timestampTz('created_at', 6)->default(DB::raw('now()'));
            $table->timestampTz('updated_at', 6)->default(DB::raw('now()'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
