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
        Schema::create('applications', function (Blueprint $table) {
          $table->ulid('id')->primary();
          $table->foreignUlid('user_id')->nullable()->constrained('users');
          $table->string('first_name');
          $table->string('last_name');
          $table->string('email');
          $table->string('school');
          $table->string('level');
          $table->string('major');
          $table->foreignUlid('suggested_department_id')
           ->constrained('departments');

       

          $table->date('suggested_start_date');

          $table->date('suggested_end_date');

          $table->string('cv');

          $table->string('handwritten_application')->nullable();


          $table->string('recommendation_letter')->nullable();


          $table->string('attestation_of_attendance')->nullable();


          $table->string('profile_picture')->nullable();

          $table->enum('status', ['rejected',  'pending', 'approved']);


          $table->timestampTz('submitted_at');

          $table->timestampTz('rejected_at')-> nullable();










































        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');


    }
};
