<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users")->onDelete("cascade");
            $table->foreignId('client_id')->constrained("clients")->onDelete("cascade");
            $table->decimal('total_price',20,2)->default(0);
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->decimal('remaining_amount', 20, 2)->default(0);
            $table->enum("status", ["مدفوع", "غير مدفوع", "متبقي"]);
                     $table->boolean("check");
            $table->date("check_date")->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sells');
    }
}
