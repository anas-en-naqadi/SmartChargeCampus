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
        Schema::create('charging_stations', function (Blueprint $table) {
             $table->id();
             $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name'); // Name of the charging station (e.g., "Library Station")
            $table->string('image')->nullable();
            $table->string('description')->nullable(); // Location description (e.g., "Near the library")
            $table->decimal('latitude', 10, 8); // Latitude of the station
            $table->decimal('longitude', 11, 8); // Longitude of the station
            $table->integer('total_ports'); // Total number of charging ports available at the station
            $table->softDeletes();
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charging_stations');
    }
};
