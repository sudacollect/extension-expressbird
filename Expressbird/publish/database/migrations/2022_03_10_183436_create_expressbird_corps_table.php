<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressbirdCorpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expressbird_corps', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->integer('logo')->default(0)->comment('logo');

            $table->string('corp_code',255)->comment('物流公司编码');
            $table->string('corp_name',255)->comment('公司名称');
            
            $table->text('corp_rules',255)->nullable()->comment('配送算法');

            $table->tinyInteger('auto_rule')->default(0)->comment('配送算法');//0接口自动,1手动
            
            $table->tinyInteger('corp_type')->default(0)->comment('默认外卖配送'); //0外卖和套餐，1普通快递

            $table->tinyInteger('enable')->default(1)->comment('启用与否');
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
        Schema::dropIfExists('waimai_delivery_corps');
    }
}
