<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressbirdMtShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expressbird_mt_shops', function (Blueprint $table) {
            $table->increments('id');

            $table->string('shop_id',255)->nullable()->comment('美团门店ID');
            $table->string('shop_name',255)->nullable()->comment('门店名称');
            $table->string('delivery_id',255)->nullable()->comment('配送标识');

            
            $table->integer('category')->default(0)->comment('一级品类');
            $table->integer('second_category')->default(0)->comment('二级品类');

            $table->string('contact_name',255)->nullable()->comment('联系人');
            $table->string('contact_phone',255)->nullable()->comment('联系电话');
            $table->string('contact_email',255)->nullable()->comment('联系邮箱');
            $table->string('shop_address',255)->nullable()->comment('门店地址');
            $table->string('shop_address_detail',255)->nullable()->comment('门牌号');

            
            $table->integer('shop_lng')->default(0)->comment('门店经度');
            $table->integer('shop_lat')->default(0)->comment('门店经度');
            $table->tinyInteger('coordinate_type')->default(0);//坐标类型0火星1百度

            $table->string('delivery_service_codes',255)->nullable()->comment('配送服务代码');

            $table->string('begin_time')->nullable()->comment('营业开始时间');
            $table->string('end_time')->nullable()->comment('营业结束时间');
            
            $table->tinyInteger('prebook')->default(0)->comment('是否支持预约单');//0不支持1支持
            $table->tinyInteger('prebook_out_of_biz_time')->default(0)->comment('是否支持预约单');//0不支持1支持

            $table->string('prebook_period')->nullable()->comment('预约单时间段');//格式为{"start": "0", "end": "2"}，单位为天

            $table->string('pay_type_codes')->nullable()->comment('结算方式');//门店当前可支持的结算方式下的支付方式，支付方式，0、账期支付，1、余额支付；
            
            $table->tinyInteger('status')->default(0);//门店状态
            // 10-创建审核驳回
            // 20-创建审核通过
            // 30-创建成功
            // 40-上线可发单
            // 50-修改审核驳回
            // 60-修改审核通过

            $table->string('reject_message')->nullable()->comment('驳回原因');
            
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
        Schema::dropIfExists('expressbird_mt_shops');
    }
}
