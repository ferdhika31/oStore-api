<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->integer("stock");
            $table->integer("prev_stock")->comment("previous stock");
            $table->integer("cur_stock")->comment("current stock");
            $table->string("type", 5)->default("IN");
            $table->unsignedBigInteger("ref_id")->nullable();
            $table->string("ref_type", 40)->nullable();
            $table->boolean("status")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}
