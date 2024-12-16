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
        Schema::create('reservations', function (Blueprint $table) {
           $table->id();
            $table->string('reservation_code')->unique();
            $table->unsignedBigInteger('student_id'); // Foreign key to students table
            $table->unsignedBigInteger('port_id'); // Foreign key to ports table
            $table->timestamps();
            $table->softDeletes();
            // Foreign key constraints
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('port_id')->references('id')->on('ports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
