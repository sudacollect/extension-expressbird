<?php

namespace App\Extensions\Expressbird\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class MtShop extends Model
{
    
    protected $table = 'expressbird_mt_shops';

    protected $fillable = [
        "shop_id","shop_name","delivery_id","category","second_category","contact_name","contact_phone","contact_email","shop_address","shop_address_detail",
        "shop_lng","shop_lat","coordinate_type","delivery_service_codes",
        "begin_time","end_time","prebook","prebook_out_of_biz_time","prebook_period","pay_type_codes","status","reject_message",
        "scope",
    ];
    
    protected $appends = ['status_text'];

    public function getStatusTextAttribute()
    {
        $status = [
            '0'=>'待同步',
            '10' => '创建审核驳回',
            '20' => '创建审核通过',
            '30' => '创建成功',
            '40' => '上线可发单',
            '50' => '修改审核驳回',
            '60' => '修改审核通过',
        ];

        return isset($status[$this->status])?$status[$this->status]:'未同步'.$this->status;
    }
    
}
