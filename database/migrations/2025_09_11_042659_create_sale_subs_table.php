<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_subs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_main_id');
            $table->foreign('sale_main_id')->references('id')->on('sale_mains');
            $table->integer('item_id');
            $table->integer('movement_id');
            $table->string('item_type');
            $table->integer('quantity');
            $table->decimal('item_price', 10, 2);
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('sale_subs');
    }
};
