<?php

namespace App\Extensions\Expressbird\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class MtOrder extends Model
{
    protected $table = 'expressbird_mt_orders';
    
    protected $fillable = [
        "id","shop_id","shop_type","shop_order_id","order_id","delivery_id",
        "outer_order_source_desc","outer_order_source_no"
        ,"delivery_service_code","receiver_name","receiver_address","receiver_phone","receiver_phone_spare",
        "receiver_lng","receiver_lat","coordinate_type","delivery_qr_code","goods_value","goods_height","goods_width","goods_length","goods_weight",
        "goods_pickup_info","goods_delivery_info","expected_pickup_time","expected_delivery_time","order_type","poi_seq","note","cash_on_delivery","cash_on_pickup",
        "invoice_title","tip_amount","receive_user_money","order_time","is_appoint","expect_time","is_insured","declared_value","goods_code_switch","pay_type_code",
        "coupons_id","mt_peisong_id","delivery_distance","delivery_fee","base_delivery_fee","pay_amount","settlement_mode_code","coupons_amount","status","enable",
        "created_at","updated_at"

    ];

    protected $appends = ['status_text'];

    // status
    // 0:待调度
    // 20:已接单
    // 30:已取货
    // 50:已送达
    // 99:已取消

    public function getShopOrderIdAttribute($value)
    {
        return $this->order_id?$this->order_id:$value;
    }

    // public function order()
    // {
    //     // 对应你的应用，关联订单
    //     return $this->hasOne('App\Extensions\Store\Models\Order','order_id','order_id');
    // }
    
    public function logs()
    {
        return $this->hasMany('App\Extensions\Expressbird\Models\MtLog','order_id','order_id')->orderBy('created_at','desc');
    }
    
    public function getStatusTextAttribute()
    {
        $text = $this->status;
        switch($this->status)
        {
            case 0:
                $text = '待调度';
            break;
            case 20:
                $text = '已接单';
            break;
            case 30:
                $text = '已取货';
            break;
            case 50:
                $text = '已送达';
            break;
            case 99:
                $text = '已取消';
            break;
            case 100:
                $text = '异常';
            break;

            case 10001:
                $text = '顾客电话关机';
            break;
            case 10002:
                $text = '顾客电话已停机';
            break;
            case 10003:
                $text = '顾客电话无人接听';
            break;
            case 10004:
                $text = '顾客电话为空号';
            break;
            case 10005:
                $text = '顾客留错电话';
            break;
            case 10006:
                $text = '联系不上顾客其他原因';
            break;
                
            case 10101:
                $text = '顾客更改收货地址';
            break;
            case 10201:
                $text = '送货地址超区';
            break;
            case 10202:
                $text = '顾客拒收货品';
            break;
            case 10203:
                $text = '顾客要求延迟配送';
            break;
            case 10301:
                $text = '商家出餐慢';
            break;
            case 10401:
                $text = '商家关店/未营业';
            break;
            case 10601:
                $text = '联系不上商家';
            break;
            case 10701:
                $text = '商家定位错误';
            break;
        }

        return $text;

    }
}
