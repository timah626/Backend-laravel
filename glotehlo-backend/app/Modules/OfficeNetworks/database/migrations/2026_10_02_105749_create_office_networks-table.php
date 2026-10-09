<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {


    Schema::create('office_networks', function (Blueprint $table) {


    $table->ulid('id')->primary();


    $table -> string ('network');

    $table->foreignUlid('site_id')->constrained('sites')->cascadeOnDelete();

    $table->string('label', 100);

    $table->enum('strength', ['strong', 'weak']);

    $table->enum('status', ['suggested', 'trusted', 'stale']);

    $table->enum('source', ['setup_walk', 'crowd']);

    $table->timestampTz('last_seen_at')->nullable();

    $table->foreignUlid('confirmed_by')->nullable()->constrained('users')->nullOnDelete();

    $table->timestampTz('confirmed_at')->nullable();



});



DB::statement(
        "ALTER TABLE office_networks
         ALTER COLUMN network TYPE cidr
         USING network::cidr"
    );










    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_networks');
    }
};
