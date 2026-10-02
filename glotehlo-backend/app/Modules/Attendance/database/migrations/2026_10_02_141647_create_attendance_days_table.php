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
        Schema::create('attendance_days', function (Blueprint $table) {
            $table->ulid('id') -> primary ();

            $table->foreignUlid('user_id') -> constrained('users') -> restrictOnDelete();

            $table->foreignUlid('site_id') -> constrained('sites') -> restrictOnDelete();

             $table->foreignUlid('department_id') -> constrained('departments') -> restrictOnDelete();

             $table -> timestampTz ('first_in_at');

             $table -> timestampTz ('last_out_at') -> nullable();

             $table -> boolean ('late') -> default (false);
         
             $table->date('date');

             $table -> enum ('state', ['clean', 'unverified', 'pending', 'confirmed', 'rejected']) -> required ();




            $table->unique(['user_id', 'date']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_days');
    }
};
