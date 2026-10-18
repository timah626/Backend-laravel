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
    Schema::table('attendance_days', function (Blueprint $table) {
        $table->enum('source', ['clock', 'marked_present'])->default('clock');
        $table->foreignUlid('reviewed_by')->nullable()->constrained('users')->restrictOnDelete();
        $table->timestampTz('reviewed_at')->nullable();
        $table->text('review_note')->nullable();
    });
}

public function down(): void
{
    Schema::table('attendance_days', function (Blueprint $table) {
        $table->dropForeign(['reviewed_by']);
        $table->dropColumn(['source', 'reviewed_by', 'reviewed_at', 'review_note']);
    });
}
};
