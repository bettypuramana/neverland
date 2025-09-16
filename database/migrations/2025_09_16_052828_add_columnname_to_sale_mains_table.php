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
        Schema::table('sale_mains', function (Blueprint $table) {
            $table->integer('item_return_status')->default(1)->after('payment_method')->comment('0 = return pending ,1 = returned,');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_mains', function (Blueprint $table) {
            $table->dropColumn('item_return_status');
        });
    }
};
