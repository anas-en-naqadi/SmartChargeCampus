<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");

                $table->foreignId("category_id")->constrained("categories")->onDelete("cascade");
                $table->unsignedBigInteger('stock_quantity');
                $table->date("expiration_date")->nullable();
                $table->string('image'); // New column for image
                $table->timestamps();
                $table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

