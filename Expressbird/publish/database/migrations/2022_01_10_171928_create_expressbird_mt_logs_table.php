<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpressbirdMtLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expressbird_mt_logs', function (Blueprint $table) {
            
            $table->string('shop_order_id',255)->nullable()->comment('商城订单号');
            $table->string('mt_peisong_id',255)->nullable()->comment('美团订单号');
            $table->string('delivery_id',255)->nullable()->comment('配送标识');
            
            $table->integer('status')->default(0)->comment('订单状态');
            
            $table->string('operator',255)->nullable()->comment('操作');
            $table->dateTime('operate_time')->nullable()->comment('变更时间');
            $table->string('courier_name',255)->nullable()->comment('快递员');
            $table->string('courier_phone',255)->nullable()->comment('快递员手机');
            $table->string('predict_delivery_time',255)->nullable()->comment('预计送达时间');

            $table->integer('cancel_reason_id')->default(0)->comment('取消原因ID');
            $table->string('cancel_reason',255)->nullable()->comment('取消原因');
            $table->integer('delivery_distance')->default(0)->comment('配送距离');
            $table->integer('delivery_fee')->default(0)->comment('配送价格');
            $table->integer('base_delivery_fee')->default(0)->comment('配送价格');

            $table->integer('pay_amount')->default(0)->comment('实际支付价格');
            $table->integer('coupons_id')->default(0)->comment('优惠券ID');
            $table->integer('coupons_amount')->default(0)->comment('优惠券金额');
            $table->tinyInteger('settlement_mode_code')->default(1);

            $table->text('content')->nullable()->comment('内容');
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
        Schema::dropIfExists('expressbird_mt_logs');
    }
}
