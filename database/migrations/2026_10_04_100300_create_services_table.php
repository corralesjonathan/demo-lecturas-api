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
        Schema::create('services', function (Blueprint $table) {
            $table->comment('Water service. A subscriber may have multiple services. Each service can have only one active meter.');

            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->foreignUuid('subscriber_id')->constrained('subscribers');

            $table->string('service_number', 30)->unique();
            $table->string('address', 250);

            $table->string('status', 20)->default('ACTIVE');

            $table->timestampTz('created_at', 6)->default(DB::raw('now()'));
            $table->timestampTz('updated_at', 6)->default(DB::raw('now()'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
