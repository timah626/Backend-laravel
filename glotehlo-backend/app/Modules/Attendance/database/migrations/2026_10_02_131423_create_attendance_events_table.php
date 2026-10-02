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
        Schema::create('attendance_events', function (Blueprint $table) {
            $table->ulid('id')->primary();


            $table->foreignUlid('user_id')->constrained('users')->restrictOnDelete();


             $table->foreignUlid('site_id')->constrained('sites')->restrictOnDelete();

             $table->foreignUlid('department_id')->constrained('departments')->restrictOnDelete();
            
              $table -> enum ('kind', ['in', 'out']);

              $table->timestampTz('server_time')->useCurrent();

              $table -> ipAddress('source_ip');
             
             $table->foreignUlid('device_id')->nullable()->constrained('devices')->nullOnDelete();


             $table->foreignUlid('matched_network_id')->nullable()->constrained('office_networks')->nullOnDelete();


             $table->enum('state', ['clean', 'unverified', 'pending', 'confirmed', 'rejected']);


             $table->uuid('idempotency_key')->unique();


             $table->foreignUlid('reviewed_by')->nullable()->constrained('users')->restrictOnDelete();


             $table->timestampTz('reviewed_at')->nullable();


            $table->text('review_note')->nullable();
        });



        DB::statement
        ("ALTER TABLE attendance_events ADD COLUMN flags text[] NOT NULL DEFAULT '{}'");

    }


    
    public function down(): void
    {
        Schema::dropIfExists('attendance_events');
    }
};
