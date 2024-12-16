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
        Schema::create('students', function (Blueprint $table) {
         $table->id(); // Auto-increment primary key
            $table->unsignedBigInteger('user_id'); // Foreign key to the users table
            $table->string('cne')->unique(); // Unique student identifier (e.g., university roll number)
            $table->string('sector'); // Department of the student
            $table->integer('year_of_study');
            $table->enum('university',['FPSB','FLSHJ','ESEF']); // Year of study (e.g., 1st year, 2nd year, etc.)
            $table->string('phone_number')->nullable(); // Phone number (nullable)
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes();
            // Foreign key constraint linking students to users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
