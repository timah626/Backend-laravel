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
        Schema::create('onboarding_drafts', function (Blueprint $table) {
       $table->ulid('id')->primary();


       $table->foreignUlid('created_by')->constrained('users')->restrictOnDelete();

       $table->char('link_token_hash', 64)->unique();

       $table->timestampTz('link_expires_at');


       $table->enum('status', ['invited', 'reading', 'ready', 'failed', 'confirmed']);


       $table->jsonb('fields');


       $table->string('photo_path', 255)->nullable();

       
      $table->foreignUlid('user_id')->nullable()->constrained('users')->restrictOnDelete();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_drafts');
    }
};
