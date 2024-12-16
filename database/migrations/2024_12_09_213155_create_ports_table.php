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
        Schema::create('ports', function (Blueprint $table) {
          $table->id();
            $table->unsignedBigInteger('port_number');
            $table->unsignedBigInteger('charging_station_id'); // Foreign key to charging_stations
            $table->enum('status', ['disponible', 'indisponible','sous maintenance'])->default('disponible');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraint linking ports to charging stations
            $table->foreign('charging_station_id')->references('id')->on('charging_stations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
