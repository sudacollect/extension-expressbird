<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityToExpressbirdMtShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expressbird_mt_shops', function (Blueprint $table) {
            $table->string('city',255)->nullable()->after('second_category')->comment('城市');
            $table->string('delivery_hours',255)->nullable()->after('delivery_service_codes')->comment('配送时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
