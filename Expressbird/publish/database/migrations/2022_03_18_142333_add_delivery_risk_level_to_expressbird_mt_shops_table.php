<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryRiskLevelToExpressbirdMtShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expressbird_mt_shops', function (Blueprint $table) {
            
            $table->tinyInteger('delivery_risk_level')->default(0)->after('delivery_service_codes')->comment('配送风险等级');
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
