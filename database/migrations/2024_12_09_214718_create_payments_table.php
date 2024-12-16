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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id'); // Foreign key to reservations table
            $table->decimal('amount', 8, 2)->default(5); // Payment amount
            $table->enum('payment_method', ['carte_bancaire', 'espèce', 'paypal'])->default('espèce');
            $table->enum('payment_status', ['en_attente', 'payé', 'échoué', 'annulé'])->default('en_attente');
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraint linking payments to reservations
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
