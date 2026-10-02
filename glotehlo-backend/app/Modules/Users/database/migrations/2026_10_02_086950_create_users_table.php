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
           Schema::create('users', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
          
            $table->string('password');
            //$table->rememberToken();
            $table->enum('role', ['intern_head', 'intern', 'admin'])->default('intern');
             $table->boolean('temp_password_active')->default(true);

             $table->string('school')->nullable();
             $table->string('level')->nullable();
             $table->string('major')->nullable();

             $table->foreignUlid('site_id')->nullable()->constrained('sites')->restrictOnDelete();
             $table->foreignUlid('department_id')->nullable()->constrained('departments')->restrictOnDelete();

             $table->date('start_date')->nullable();
             $table->date('end_date')->nullable();

             $table->string('photo_path', 255)->nullable();
             $table->boolean('active')->default(true);
            $table->timestamps();
        });





        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUlid('user_id')->nullable()->index();    
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
