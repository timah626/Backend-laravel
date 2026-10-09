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
        Schema::create('departments', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->foreignUlid('site_id')->constrained('sites')->restrictOnDelete();  //do not delete  site if  a valid department still belongs to it though . oh timah you forget like crazy
        $table->string('name', 100);
        $table->string('qr_slug', 40)->unique();
        $table->timestamps();

        $table->unique(['site_id', 'name']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
