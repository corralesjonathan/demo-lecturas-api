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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->comment('Subscriber/customer belonging to an organization.');

            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));

            $table->foreignUuid('organization_id')->constrained('organizations');

            $table->string('identification', 20);
            $table->string('full_name', 150);
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();

            $table->timestampTz('created_at', 6)->default(DB::raw('now()'));
            $table->timestampTz('updated_at', 6)->default(DB::raw('now()'));

            $table->unique(['organization_id', 'identification']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
