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
        Schema::create('devices', function (Blueprint $table) {
            $table->Ulid('id')->primary();

            $table->foreignUlid('user_id')->constrained('users')->CasCadeOnDelete();
            $table->char('token_hash', 64)->unique();

            $table->string('label', 100)->nullable();

            $table->timestampTz('first_seen_at');

            $table->timestampTz('last_seen_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
