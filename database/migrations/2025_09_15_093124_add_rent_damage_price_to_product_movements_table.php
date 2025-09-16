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
        Schema::table('product_movements', function (Blueprint $table) {
            $table->decimal('rent_damage_price', 10, 2)->nullable()->after('sale_price');
        });
    }

    public function down()
    {
        Schema::table('product_movements', function (Blueprint $table) {
            $table->dropColumn('rent_damage_price');
        });
    }

};
