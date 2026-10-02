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
       Schema::create('intern_documents', function (Blueprint $table) {
    $table->ulid('id')
    ->primary();


    $table->foreignUlid('draft_id')->constrained('onboarding_drafts')->cascadeOnDelete();


    $table->string('file_path', 255);


    $table->string('mime_type', 100);


    $table->enum('ocr_status', ['queued', 'done', 'failed']);

    
    $table->jsonb('ocr_result')->nullable();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_documents');
    }
};
