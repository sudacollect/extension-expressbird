<?php

namespace App\Extensions\Expressbird\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Traits\HasTaxonomies;

use Gtd\Suda\Traits\MediaTrait;
use Gtd\Suda\Models\Setting;
use Illuminate\Support\Carbon;

class MtLog extends Model
{
    // use HasTaxonomies;
    // use MediaTrait;
    
    protected $table = 'expressbird_mt_logs';

    protected $fillable = [
        "shop_order_id","order_id","mt_peisong_id","delivery_id",
        "status","content","operator","operate_time",
        "courier_name","courier_phone","predict_delivery_time",
        "cancel_reason_id","cancel_reason",
        "base_delivery_fee","coupons_amount","delivery_distance","delivery_fee",
        "pay_amount","coupons_id","settlement_mode_code"
    ];
    
    protected $appends = ['status_text'];

    

    public function mtorder()
    {
        return $this->belongsTo('App\Extensions\Expressbird\Models\MtOrder','order_id','order_id');
    }


    public function getStatusTextAttribute()
    {
        $status = [
            '0'=>'订单创建,待调度',
            
            '20'=>'配送员接单',
            '30'=>'已取货',
            '50'=>'已送达',
            '99'=>'订单取消',
            
            '1' =>	'系统错误 三次重试，每次间隔10S',
            '2' =>	'缺少系统参数',
            '3' =>	'缺少参数，数据不完整',
            '4' =>	'参数格式错误',
            '5' =>	'app_key不存在或合作方不合作',
            '6' =>	'签名验证错误',
            '7' =>	'没有权限操作这条数据	检查是否正式账号操作测试账号数据或测试账号操作正式账号数据',
            '8' =>	'api版本号不支持',
            '9' =>	'请求时间超出最大时限	检查入参的timestamp是否满足：推单时间 - timestamp < 600秒',
            '10' =>	'当前ip不在可访问列表中	联系技术人员排查',
            '11' =>	'接口流控	三次重试，每次间隔10S',
            '13' =>	'输入参数内容不符合规范，请检查修改	请检查参数的内容，修改后重试',
            '101' =>	'订单id正在创建配送订单	三次重试，每次间隔10S',
            '102' =>	'订单不存在	检查入参delivery_id、mt_peisong_id',
            '103' =>	'货品信息有误，金额过大，体积过大，重量过沉等',
            '104' =>	'订单已完成，不能取消',
            '105' =>	'订单预计完成时间不在可接受范围',
            '106' =>	'所选城市未合作	联系技术人员排查',
            '107' =>	'所选配送服务类型无效',
            '108' =>	'非合作时间',
            '109' =>	'取货地址超区',
            '110' =>	'送货地址超区',
            '111' =>	'不在美团配送站点营业时间内',
            '112' =>	'门店不存在',
            '113' =>	'不在门店营业时间内',
            '114' =>	'门店非营业状态',
            '115' =>	'门店未开通所选服务包',
            '116' =>	'超过所选服务包允许的时效限制',
            '117' =>	'预约时间超出范围',
            '118' =>	'订单类型不符合要求',
            '119' =>	'门店不支持预约单',
            '120' =>	'预约时间超出范围',
            '121' =>	'门店不支持营业时间外发预订单',
            '122' =>	'保价服务异常	三次重试，每次间隔10S',
            '123' =>	'价格缺失，无法投保',
            '124' =>	'模拟操作失败',
            '125' =>	'订单尚未完成，不允许进行评价操作',
            '126' =>	'订单已评价，不允许重复评价',
            '127' =>	'坐标方式发出的订单，不允许评价操作',
            '128' =>	'所选位置暂未开通服务',
            '129' =>	'订单未调度，请分配骑手后重试',
            '130' =>	'订单已送达，无法获取位置信息',
            '131' =>	'订单已取消，无法获取位置信息',
            '132' =>	'骑手目前没有位置信息，请稍后重试',
            '133' =>	'创建订单接口禁用中，请咨询您的销售经理',
            '144' =>	'当前区域运力紧张，无法创建订单',
            '145' =>	'坐标没有落在区域内',
            '152' =>	'因配送范围管控，取货/送货地址超区',
            '163' =>	'收件坐标不合法',
            '164' =>	'预约取件时间不合法',
            '190' =>	'接口已降级&【具体原因】根据具体原因更换调用的相应入参',
            '200' =>	'不支持查询跑腿B服务包配送范围，跑腿B帮送服务包配送范围支持导航距离70km以内的订单',
            '201' =>	'参数值错误：%s',
            '202' =>	'已有骑手接单，不用加小费啦',
            '203' =>	'加小费金额已至上限，无法继续添加',
            '204' =>	'加小费次数已至上限，无法继续追加小费',
            '205' =>	'加小费异常，请稍后重试',
            '210' =>	'请核对标准计量单位code码',
            '211' =>	'优惠券无效或不符合可用条件	建议使用预发单接口查询可支持的优惠券信息',
            '212' =>	'账户余额不足或扣款失败	建议账户充值后重新下单，或更换其他支付方式重新下单',
            '214' =>	'不支持该支付方式	建议重新查询门店中可支持的支付方式',
            '215' =>	'定价方案异常	请联系销售、运营人员查看定价方案是否维护',
            '216' =>	'获取合同异常	请联系销售、运营人员检查合同状态',
            '217' =>	'服务产品不可用	请联系销售、运营人员检查服务产品是否有效',
        ];

        return isset($status[$this->status])?$status[$this->status]:'异常'.$this->status;
    }
    
}
