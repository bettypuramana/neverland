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
            $table->integer('exit_status')->default(0)->after('payment_method')->comment('0 = not exited ,1 = exited,');
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
            $table->dropColumn('exit_status');
        });
    }
};
