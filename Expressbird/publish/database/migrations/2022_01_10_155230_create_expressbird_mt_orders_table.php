<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressbirdMtOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expressbird_mt_orders', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('shop_id',255)->nullable()->comment('门店ID');
            $table->integer('shop_type')->default(1)->comment('店铺类型');//1 美团没用到
            
            $table->string('shop_order_id',255)->nullable()->comment('商家订单号');
            $table->string('delivery_id',255)->nullable()->comment('商家配送单号');

            $table->string('outer_order_source_desc',255)->nullable()->comment('订单来源');
            
            $table->string('outer_order_source_no',255)->nullable()->comment('源平台订单号');

            
            $table->integer('delivery_service_code')->default(0)->comment('配送服务代码');
            // 飞速达:4002
            // 快速达:4011
            // 及时达:4012
            // 集中送:4013
            // 跑腿-帮送:4031
            // 光速达-55

            $table->string('receiver_name',255)->nullable()->comment('收件人');
            $table->string('receiver_address',255)->nullable()->comment('收件人地址');
            $table->string('receiver_phone',255)->nullable()->comment('收件人手机号');
            $table->string('receiver_phone_spare',255)->nullable()->comment('备用手机号');

            $table->integer('receiver_lng')->nullable()->comment('收件人经度');
            $table->integer('receiver_lat')->nullable()->comment('收件人纬度');
            $table->tinyInteger('coordinate_type')->default(0)->comment('坐标类型');//0火星坐标1百度坐标

            $table->string('delivery_qr_code',255)->nullable()->comment('配送码');


            $table->integer('goods_value')->default(0)->comment('货物价格');
            $table->integer('goods_height')->default(0)->comment('货物高度');
            $table->integer('goods_width')->default(0)->comment('货物宽度');
            $table->integer('goods_length')->default(0)->comment('货物长度');
            $table->integer('goods_weight')->default(0)->comment('货物重量');

            $table->string('goods_pickup_info',255)->nullable()->comment('取货信息');
            $table->string('goods_delivery_info',255)->nullable()->comment('交付信息');

            $table->dateTime('expected_pickup_time')->nullable()->comment('期望取货时间');
            $table->dateTime('expected_delivery_time')->nullable()->comment('期望派送时间');

            
            $table->tinyInteger('order_type')->default(0)->comment('订单类型');//0即时单，1预约单
            
            $table->string('poi_seq')->nullable()->comment('取货序列');//门店订单流水号

            $table->text('note')->nullable()->comment('备注');

            $table->integer('cash_on_delivery')->default(0)->comment('骑手应付金额');//分，预留字段
            $table->integer('cash_on_pickup')->default(0)->comment('骑手应收金额');//分，预留字段
            $table->string('invoice_title')->nullable()->comment('发票抬头');

            $table->integer('tip_amount')->default(0)->comment('小费');//加消费

            $table->integer('receive_user_money')->default(0)->comment('代收金额');
            $table->integer('order_time')->default(0)->comment('下单时间');
            $table->integer('is_appoint')->default(0)->comment('是否预约单');//0非，1预约单
            $table->integer('expect_time')->default(0)->comment('期望送达时间');
            $table->integer('is_insured')->default(0)->comment('是否保价');
            $table->integer('declared_value')->default(0)->comment('保价金额');
            
            
            $table->tinyInteger('goods_code_switch')->default(0)->comment('收货码开关');//0关闭 1开启
            $table->tinyInteger('pay_type_code')->default(0)->comment('运单支付方式');//0账期支付 1余额支付

            
            $table->string('coupons_id')->nullable()->comment('优惠券ID');
            
            $table->string('mt_peisong_id')->nullable()->comment('美团配送内部订单ID');
            
            $table->integer('delivery_distance')->default(0)->comment('配送距离');
            $table->integer('delivery_fee')->default(0)->comment('配送费用');
            $table->integer('base_delivery_fee')->default(0)->comment('优惠可用金额');
            $table->integer('pay_amount')->default(0)->comment('实际支付金额');

            $table->tinyInteger('settlement_mode_code')->default(0)->comment('结算方式');//1实时结算 2账期结算
            $table->integer('coupons_amount')->default(0)->comment('优惠券金额');

            $table->integer('status')->default(0)->comment('状态');

            $table->tinyInteger('enable')->default(1);
            $table->timestamps();

            $table->index('shop_id');
            $table->index('shop_order_id');
            $table->index('mt_peisong_id');
            $table->index('delivery_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expressbird_mt_orders');
    }
}
