<?php
namespace App\Extensions\Expressbird\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as HttpClient;

use Illuminate\Support\Arr;

use Gtd\Suda\Models\Setting;

use App\Extensions\Expressbird\Traits\ExpressbirdTrait;
use App\Extensions\Expressbird\Contracts\ExpressbirdFactory;

use App\Extensions\Expressbird\Models\MtOrder;
use App\Extensions\Expressbird\Models\MtLog;
use App\Extensions\Expressbird\Models\MtShop;
use App\Extensions\Expressbird\Services\MeituanLogService;

use App\Extensions\Westore\Models\Order;
use App\Extensions\Westore\Models\DeliveryCorp;

use App\Extensions\Expressbird\Models\ExpressbirdCorp;
use App\Extensions\Westore\Services\Order\DeliveryService;

class MeituanService implements ExpressbirdFactory
{
    use ExpressbirdTrait;
    
    protected $corp_code;
    private $host = 'https://peisongopen.meituan.com/api/';

    public $conf = [];
    public $test_conf = [
        'app_id'=>'',
        'app_secret'=>'',
    ];

    public $ship;
    public $msg;
    public $logService;

    public function __construct($corp_code,$config)
    {
        $this->corp_code  = $corp_code;

        $this->getSettingConf();

        $this->logService = new MeituanLogService;
    }

    // 获取配送的信息
    public function getCrop()
    {
        $corp = ExpressbirdCorp::where(['corp_code'=>$this->corp_code])->first();
        
        return $corp;
    }

    //获取系统配置
    public function getSettingConf()
    {
        $express_code = 'meituan';
        $key_name = 'expressbird_'.$express_code.'_setting';
        $basic_config = Cache::store(config('sudaconf.admin_cache','file'))->get($key_name);

        if(!$basic_config)
        {
            $config = Setting::where(['key'=>$key_name,'group'=>'extension'])->first();
            if($config){
                $basic_config = unserialize($config->values);
            }

        }

        $this->conf = $basic_config;
        return $this->conf;
    }

    //发快递统一的入口
    public function send($order_id,$delivery_id,&$msg='')
    {
        $result = $this->preCreate($order_id,$delivery_id);
        
        if($result)
        {
            $result = $this->toCreate($order_id,$delivery_id,$msg);
            
            if($result)
            {
                return true;
            }
            
            $msg = '发单失败';
            return false;
        }

        $msg = '预发单失败';
        return false;
    }

    public function toCreate($order_id,$delivery_id,&$msg='')
    {
        
        
        $orderUpdateObj = new MtOrder;
        
        $post = $this->createExpressOrder($order_id,$delivery_id);
        if(!$post)
        {
            return false;
        }

        $sign = $this->makeSign($post);
        $url = $this->host.'order/createByShop';

        Log::info('[美团]发单',['order_id'=>$order_id,'post'=>$post]);

        $result = $this->requestPostForm($url,$post);

        

        if(!$result)
        {
            Log::info('[美团]发单失败',['order_id'=>$order_id,'post'=>$post]);
            $data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'status'=>'00',
                'content'=>'请求发单失败',
            ];
            $this->logService->saveLog($data);
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            Log::info('[美团]发单成功',['order_id'=>$order_id,'result'=>$result]);

            // {
                // "code":0,
                // "data":{
                //     "coupons_amount":0,
                //     "delivery_distance":1,
                //     "delivery_fee":0.01,
                //     "delivery_id":1647231269,
                //     "expected_delivery_time":1647234269,
                //     "mt_peisong_id":"1647231269321001456",
                //     "order_id":"B2203112047253867",
                //     "pay_amount":0.01,
                //     "settlement_mode_code":2
                // }
            // }

            //发单成功
            $result_data = $result['data'];
            $save_data = [];
            $save_data = $result_data;
            
            
            $save_data['coupons_amount'] = $save_data['coupons_amount']*100;
            $save_data['delivery_fee'] = $save_data['delivery_fee']*100;
            $save_data['pay_amount'] = $save_data['pay_amount']*100;
            
            //#1 增加订单记录
            $log_data = [
                'order_id'=>$order_id,
                'mt_peisong_id'=>$save_data['mt_peisong_id'],
                'delivery_id'=>$save_data['delivery_id'],
                'coupons_amount'=>$save_data['coupons_amount'],//优惠券金额，单位为元
                'delivery_distance'=>$save_data['delivery_distance'],
                'delivery_fee'=>$save_data['delivery_fee'],
                'pay_amount'=>$save_data['pay_amount'],
                'settlement_mode_code'=>$save_data['settlement_mode_code'],//结算方式，1实时，2账期
                'status'=>$result['code'],
                'content'=>'发单成功',
            ];
            $this->logService->saveLog($log_data);

            //#2 更新美团发货单据
            $save_data['expected_delivery_time'] = Carbon::createFromTimestamp($save_data['expected_delivery_time'])->toDateTimeString();
            $orderUpdateObj->where(['order_id'=>$order_id])->update($save_data);

            //=订单发货

            //to-send-orer 美团自动发货
            $corp = DeliveryCorp::where(['corp_code'=>'meituan'])->first();
            $corp_id = 0;
            if($corp)
            {
                $corp_id = $corp->id;
            }


            $dlyService = new DeliveryService;
            $from = 'system';
            $send_post = [
                'order_id'=>$order_id,
                'reason'=>'美团自动发货',
                'user_id'=>0,
                'dly_id'=>$delivery_id, //发货单编号
                'dly_company'=>$corp_id,
                'dly_code'=>isset($save_data['mt_peisong_id'])?$save_data['mt_peisong_id']:'',  //配送单号
            ];

            $msg = '';
            $result = $dlyService->autoSendOrder($send_post,$from,$msg);

            return true;

        }else{
            Log::info('[美团]发单失败',['order_id'=>$order_id,'post'=>$post,'result'=>$result]);
            
            $log_data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'status'=>$result['code'],
                'content'=>'[发单失败]'.$result['message'],
            ];
            $this->logService->saveLog($log_data);

            return false;
        }

        $msg = '意外错误';
        return false;

    }

    //预创建订单验证
    public function preCreate($order_id,$delivery_id)
    {
        
        $post = $this->createExpressOrder($order_id,$delivery_id);
        if(!$post)
        {
            return false;
        }

        $sign = $this->makeSign($post);
        $url = $this->host.'order/preCreateByShop';

        Log::info('[美团]预发单',['order_id'=>$order_id,'post'=>$post]);

        $result = $this->requestPostForm($url,$post);

        if(!$result)
        {
            Log::info('[美团]预发单失败',['order_id'=>$order_id,'post'=>$post]);
            
            $log_data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'status'=>'00',
                'content'=>'请求发单失败',
            ];
            $this->logService->saveLog($log_data);

            return false;
        }

        if($result && intval($result['code'])==0)
        {
            // {"code":0,"data":{"base_delivery_fee":0.01,"coupons_amount":0,"delivery_distance":1,"delivery_fee":0.01},"message":"success"}

            // base_delivery_fee 优惠可用配送金额，单位为元；使用余额支付时有值；
            // 总配送费中可使用优惠券抵扣的部分配送费金额，金额受到不同计费因素以及优惠券使用规则影响

            Log::info('[美团]预发单成功',['order_id'=>$order_id,'result'=>$result]);

            //发单成功
            $result_data = $result['data'];
            $save_data = [];
            $save_data = $result_data;

            //单位转为分
            $save_data['base_delivery_fee'] = $save_data['base_delivery_fee']*100;
            $save_data['coupons_amount'] = $save_data['coupons_amount']*100;
            $save_data['delivery_fee'] = $save_data['delivery_fee']*100;

            $log_data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'status'=>$result['code'],
                'content'=>'预发单成功',
            ];
            $log_data = array_merge($save_data,$log_data);
            $this->logService->saveLog($log_data);

            return true;

        }else{
            Log::info('[美团]预发单失败',['order_id'=>$order_id,'post'=>$post,'result'=>$result]);
            
            $log_data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'status'=>$result['code'],
                'content'=>'[预发单失败]'.$result['message'],
            ];
            $this->logService->saveLog($log_data);

            return false;
        }

        $msg = '意外错误';
        return false;
        
    }

    // 重新发送
    public function reSend($order_id,$delivery_id,&$msg='')
    {

    }

    //获取订单配送码
    public function orderCreateDeliveryCode($shop_id,$order_id)
    {

    }

    //获取骑手位置H5
    public function orderRiderLocationH5url($mt_peisong_id)
    {

        $sforder = MtOrder::where(['order_id'=>$order_id])->first();
        if(!$sforder)
        {
            return false;
        }
        $post = [
            'order_id'=>$sforder->sf_order_id,
            'order_type'=>1,
            'push_time'=>time(),
        ];
        $sign = $this->makeSign($post);
        $url = $this->host.'open/api/external/riderviewv2?sign='.$sign;

        $result = $this->postRequest($url,$post);

        Log::info('[美团]轨迹',['data'=>$result]);
        if($result && intval($result['code'])==0)
        {
            return $result['result']['url'];
        }

        return false;
    }

    //获取骑手当前位置
    // 1. 骑手位置的更新频率为 30s；
    // 2. 接口返回的骑手坐标为火星坐标（高德地图和腾讯地图均采用火星坐标），而非百度坐标；
    // 3. 接口返回的坐标量级为真实坐标的基础上乘以 10 的 6 次方的整数，例如：骑手真实坐标为（116.255596, 40.029185），则接口返回结果为（116255596, 40029185）；
    // 4. 订单状态只有为“已接单”、“已取货”和“已送达”时，才会返回骑手位置信息，接口返回结果与订单状态的对应关系如下：
    public function riderLocation($order_id,&$msg='')
    {

        $order = MtOrder::where(['order_id'=>$order_id])->orderby('created_at','desc')->first();
        if(!$order)
        {
            return [];
        }

        $post = [
            'mt_peisong_id'=>$order->mt_peisong_id,
            'delivery_id'=>$order->delivery_id,
            
        ];
        $sign = $this->makeSign($post);
        $url = $this->host.'order/rider/location';

        $result = $this->requestPostForm($url,$post);

        if($result && intval($result['code'])==0)
        {
            // Log::info('坐标',['data'=>$result]);

            // $result['data'] = ['lat','lng']
            // 坐标需要除以10的6次方
            if($result['data']){
                
                
                $log_item = MtLog::where(['mt_peisong_id'=>$post['mt_peisong_id']])->whereIn('status',[20,30,50])->orderBy('created_at','desc')->first();

                $position_data['order_id'] = $order->order_id;
                if($log_item)
                {
                    $position_data['rider_name']=$log_item->courier_name;
                    $position_data['rider_phone']=$log_item->courier_phone;
                }
                
                $position_data['rider_lat'] = $result['data']['lat'] % pow(10,6);
                $position_data['rider_lng'] = $result['data']['lng'] % pow(10,6);

                return $position_data;

            }
            return [];
        }

        return false;
    }

    //下发取餐码给骑手
    // cabinet_info = []
    // type：取餐类型，int类型，包含0（存餐及更新）、1（撤餐），必填且必须为0或1；
    // cabinetNum：柜号，String类型，最长不超过100字符，非必填，同步撤餐状态时可为空，若存在多个时可以逗号隔开；
    // cabinetDoor：门号（取餐柜的格口号），String类型，最长不超过100字符，必填，同步撤餐状态时可为空，若存在多个时可以逗号隔开；
    // cabinetCode：取餐码，String类型，最长不超过12个字符，必填，同步撤餐状态时可为空，一个配送订单对应一个取餐码；
    // 取餐码信息会同步到骑手侧展示并将取餐码转换为二维码展示。
    public function saveMealCodeByPkgId($mt_peisong_id,$cabinet_info)
    {
        // $cabinet_info = [
        //     "type" => 0,
        //     "cabinetNum" =>  "xxxxxx",
        //     "cabinetDoor" =>  "xxxxx,xxxxx",
        //     "cabinetCode" =>  "xxxxxx"
        // ];

        

    }

    // 由退款发起的取消订单
    public function toCancelSend($order_id,$cancel_reason)
    {
        $order = MtOrder::where(['order_id'=>$order_id])->orderBy('created_at','desc')->first();
        if(!$order)
        {
            Log::info('[EB美团]取消订单',['order_id'=>$order_id,'msg'=>'订单不存在']);
            return false;
        }

        // 送达和已取消的不可取消
        if(in_array($order->status,[50,99]))
        {
            Log::info('[EB美团]取消订单',['order_id'=>$order_id,'msg'=>'订单状态不可取消']);
            return false;
        }

        $cancel_reason_id = '199';

        $msg = '';
        $result = $this->orderDelete($order->order_id,$order->delivery_id,$order->mt_peisong_id,$cancel_reason_id,$cancel_reason,$msg);
        
        if(!$result)
        {
            Log::info('[EB美团]取消订单',['order_id'=>$order_id,'msg'=>$msg?$msg:'取消失败']);
            return false;
        }

        return true;
    }

    // 取消发送
    public function cancelSend($order_id,$params,&$msg='')
    {
        $order = MtOrder::where(['order_id'=>$order_id])->orderBy('created_at','desc')->first();

        if(!$order)
        {
            Log::info('[EB美团]取消订单',['order_id'=>$order_id,'msg'=>'订单不存在']);
            return false;
        }

        // 送达和已取消的不可取消
        if(in_array($order->status,[50,99]))
        {
            Log::info('[EB美团]取消订单',['order_id'=>$order_id,'msg'=>'订单状态不可取消']);
            return false;
        }

        $msg = '';

        if($order->mt_peisong_id)
        {
            $result = $this->orderDelete($order->order_id,$order->delivery_id,$order->mt_peisong_id,$params['cancel_reason_id'],$params['cancel_reason'],$msg);
        
            if(!$result)
            {
                Log::info('[EB美团]取消订单',['order_id'=>$order_id,'msg'=>$msg?$msg:'取消失败']);
                return false;
            }
        }
        
        return true;
    }

    //取消订单
    public function orderDelete($order_id,$delivery_id,$mt_peisong_id,$cancel_reason_id,$cancel_reason='退单取消',&$msg='')
    {


        // 接入方原因
        // 101	顾客主动取消
        // 102	顾客更改配送时间/地址
        // 103	备货、包装、货品质量问题取消
        // 199	其他接入方原因
        // 美团配送原因
        // 201	配送服务态度问题取消
        // 202	骑手配送不及时
        // 203	骑手取货不及时
        // 299	其他美团配送原因
        // 其他原因
        // 399	其他原因

        $post = [
            'delivery_id'       => $delivery_id,
            'mt_peisong_id'     => $mt_peisong_id,
            'cancel_reason_id'  => $cancel_reason_id,
            'cancel_reason'     => $cancel_reason,
        ];
        $sign = $this->makeSign($post);
        $url = $this->host.'order/delete';

        $result = $this->requestPostForm($url,$post);

        if($result && intval($result['code'])==0)
        {
            
            // 生成日志
            $log_data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'mt_peisong_id'=>$mt_peisong_id,
                'status'=>'99',
                'content'=>'订单取消: '.$cancel_reason,
            ];
            $this->logService->saveLog($log_data);


            // 取消发单状态
            MtOrder::where(['mt_peisong_id'=>$mt_peisong_id])->update(['status'=>'99']);

            return true;

        }else{
            Log::info('[EB美团]取消失败',['mt_peisong_id'=>$mt_peisong_id,'code'=>$result['code'],'message'=>$result['message']]);
            $msg = $result['message']?$result['message']:'取消失败';
            return false;
        }

        $msg = '取消失败，请求接口失败';
        return false;
    }

    // 获取订单回调信息
    public function orderStatusQuery($order_id)
    {

        $order = MtOrder::where(['order_id'=>$order_id])->first();
        if(!$order)
        {
            return false;
        }
        $post = [
            'delivery_id'=>$order->delivery_id,
            'mt_peisong_id'=>$order->mt_peisong_id,
        ];
        $sign = $this->makeSign($post);
        $url = $this->host.'order/status/query';

        $result = $this->requestPostForm($url,$post);

        Log::info('[美团]订单查询',['data'=>$result]);

        if($result && intval($result['code'])==0)
        {
            return $result['data'];
        }

        return false;
    }
    

    // 格式化订单数据
    public function createExpressOrder($order_id,$delivery_id)
    {
        $order = Order::where(['order_id'=>$order_id,'status'=>1])->with('store')->with('items')->first();

        if(!$order)
        {
            return false;
        }
        
        if($order->delivery_type == 1)
        {
            Log::info('[美团]创建单据失败',['order_id'=>$order_id,'msg'=>'订单为自提订单']);
            return false;
        }

        if(!$order->store || !$order->store->meituan_code)
        {
            
            Log::info('[美团]创建单据失败',['order_id'=>$order_id,'msg'=>'没配置美团店铺代码']);

            $log_data = [
                'order_id'=>$order_id,
                'delivery_id'=>$delivery_id,
                'status'=>'00',
                'content'=>'['.$order->store->store_name.']没有配置美团店铺代码',
            ];
            $this->logService->saveLog($log_data);

            return false;
        }

        $order_data = [];

        $order_data['order_id'] = $order_id;
        $order_data['delivery_id'] = $delivery_id;
        // $order_id
        $order_data['outer_order_source_desc'] = 202; //商家小程序-微信

        // 订单来源：
        // 101.美团（外卖&闪购）
        // 102.饿了么
        // 103.京东到家
        // 201.商家web网站
        // 202.商家小程序-微信
        // 203.商家小程序-支付宝
        // 204.商家APP
        // 205.商家热线
        // 其他，请直接填写中文字符串，最长不超过20个字符
        // 非「其他」需传代码

        $order_data['outer_order_source_no'] = $order_id;
        $order_data['shop_id'] = $order->store->meituan_code;
        $order_data['delivery_service_code'] = 100003;//光速达-55

        $order_data['receiver_name'] = $order->ship_name;
        $order_data['receiver_address'] = $order->ship_address.$order->ship_room;
        $order_data['receiver_phone'] = $order->ship_phone;
        // $order_data['receiver_lng'] = number_format($order->ship_longitude,6,'.','');//$out = number_format($n, $precision ,'.','');
        // $order_data['receiver_lat'] = number_format($order->ship_latitude,6,'.','');

        $order_data['receiver_lng'] = number_format($order->ship_longitude,6,'.','') * pow(10,6);
        $order_data['receiver_lat'] = number_format($order->ship_latitude,6,'.','') * pow(10,6);

        

        $order_data['coordinate_type'] = 0;//0火星坐标,1百度坐标
        $order_data['pay_type_code'] = 0; //账期支付
        $order_data['goods_value'] = $order->price;

        //$goods_height = *cm;
        //$goods_width = *cm;
        //$goods_length = *cm;
        
        $order_data['goods_weight'] = round($order->weight,2);//四舍五入，保留两位小数
        if($order_data['goods_weight'] <= 0)
        {
            $order_data['goods_weight'] = 0.01;
        }

        // 商品详细信息
        //$goods_detail = []
        
        foreach($order->items as $item)
        {
            $goods_detail[] = [
                'goodName'      => $item->goods_name,
                'product_id'    => $item->goods_id,
                'goodCount'     => $item->number,
                'goodPrice'     => $item->price*100,
                'goodUnit'      => '个',
                'goodUnitCode'  => '10008'
            ];
        }
        $order_data['goods_detail'] = json_encode(['goods'=>$goods_detail]);

        $order_data['goods_pickup_info'] = '订单码 '.$order->sortcode;

        //$goods_delivery_info 货物交付信息

        $order_data['order_type'] = 0; //0即时单，1预约单
        if($order->order_type=='book')
        {
            if(Carbon::parse($order->pick_time)->subHour(1)->lt(now()))
            {
                // 改成发送即时单
                // 用来补单的规则
                $order_data['order_type'] = 0;

            }else{
                $order_data['expected_pickup_time'] = Carbon::parse($order->pick_time)->subHour(1)->timestamp;
                $order_data['expected_delivery_time'] = Carbon::parse($order->pick_time)->timestamp;
                $order_data['order_type'] = 1;
            }
            
            
        }

        // 门店订单流水单号
        $order_data['poi_seq'] = $order->sortcode;
        

        //测试test_0001数据
        if($order->store->meituan_code == 'test_0001')
        {
            // $order_data['delivery_id'] = time();
            $order_data['receiver_name'] = '测试收货人';
            $order_data['receiver_address'] = '新疆维吾尔自治区和田地区于田县1路100号';
            $order_data['receiver_phone'] = '18523657373';

            // [[81.512864,36.949802],[81.536245,36.941462],[81.53588,36.940201],[81.53563,36.937452],[81.535764,36.936058],[81.536532,36.933412],[81.535858,36.93168],[81.535481,36.929797],[81.533904,36.92444],[81.534757,36.922221],[81.522881,36.906136],[81.522906,36.905995],[81.526702,36.903466],[81.551927,36.896908],[81.555208,36.9006],[81.560463,36.897061],[81.560618,36.897137],[81.561399,36.911175],[81.56459,36.911955],[81.567733,36.913018],[81.570525,36.914218],[81.585787,36.945939],[81.58521,36.947938],[81.586767,36.951661],[81.587646,36.955744],[81.587713,36.959882],[81.524283,36.963992],[81.512864,36.949802]]
            $order_data['receiver_lng'] = '81512864';
            $order_data['receiver_lat'] = '36949802';
            $order_data['order_type'] = 0;
            if(isset($order_data['expected_pickup_time']))
            {
                unset($order_data['expected_pickup_time']);
            }
            if(isset($order_data['expected_delivery_time']))
            {
                unset($order_data['expected_delivery_time']);
            }
        }

        //存储订单信息
        DB::beginTransaction();

        $orderObj = new MtOrder;
        $save_order = $order_data;
        $save_order['shop_order_id'] = $order_id;

        unset($save_order['goods_detail']);
        
        if(isset($save_order['expected_pickup_time']))
        {
            $save_order['expected_pickup_time'] = Carbon::parse($save_order['expected_pickup_time'])->format('Y-m-d H:i:s');
        }
        if(isset($save_order['expected_delivery_time']))
        {
            $save_order['expected_delivery_time'] = Carbon::parse($save_order['expected_delivery_time'])->format('Y-m-d H:i:s');
        }
        if(MtOrder::where(['order_id'=>$order_id])->first())
        {
            MtOrder::where(['order_id'=>$order_id])->lockForUpdate()->update($save_order);
        }else{
            $orderObj->fill($save_order)->save();
        }

        DB::commit();

        return $order_data;
    }


    // 查询门店信息
    public function shopQuery($shop_id)
    {
        //shop/query
        
        $post = [
            'shop_id' => $shop_id,
        ];
        $sign = $this->makeSign($post);
        $url = $this->host.'shop/query';

        $result = $this->requestPostForm($url,$post);

        Log::info('[美团]门店查询',['shop_id'=>$shop_id,'post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info('[美团]门店查询失败',['shop_id'=>$shop_id,'post'=>$post]);
            
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            // 查询成功
            $result_data = $save_data = $result['data'];
            
            if(isset($save_data['pay_type_codes']))
            {
                $save_data['pay_type_codes'] = serialize($save_data['pay_type_codes']);
            }
            if( MtShop::where(['shop_id'=>$save_data['shop_id']])->first() )
            {
                MtShop::where(['shop_id'=>$save_data['shop_id']])->update($save_data);
            }else{
                (new MtShop)->fill($save_data)->save();
            }
            
            
            return $result_data;
            return true;

        }else{
            Log::info('[美团]门店查询失败',['shop_id'=>$shop_id,'result'=>$result]);

            return false;
        }

        $msg = '意外错误';
        return false;

    }

    // 查询门店配送范围
    public function shopAreaQuery($shop_id,$delivery_service_code)
    {
        //shop/query
        
        $post = [
            'shop_id' => $shop_id,
            'delivery_service_code' => $delivery_service_code,
        ];
        $sign = $this->makeSign($post);
        $url = $this->host.'shop/area/query';

        $result = $this->requestPostForm($url,$post);

        Log::info('[美团]门店配送范围查询',['shop_id'=>$shop_id,'post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info('[美团]门店配送范围失败',['shop_id'=>$shop_id,'post'=>$post]);
            
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            
            //发单成功
            $result_data = $result['data'];
            
            MtShop::where(['shop_id'=>$shop_id])->update([
                'scope' => $result_data['scope'],
            ]);

            return $result_data;
            return true;

        }else{

            Log::info('[美团]门店配送范围失败',['shop_id'=>$shop_id,'result'=>$result]);
            
            return false;
        }

        $msg = '意外错误';
        return false;

    }

    // 回调处理 START ==========================

    // 订单状态回调
    public function updateStatus($order_id,$params)
    {
        /**
         * 
         *   delivery_id
         *   mt_peisong_id
         *   order_id
         *   status
         *   
         *   0：待调度
         *   20：已接单
         *   30：已取货
         *   50：已送达
         *   99：已取消
         *   回调接口的订单状态改变可能会跳过中间状态，比如从待调度状态直接变为已取货状态。
         *   订单状态不会回流。即订单不会从已取货状态回到待调度状态。
         *   订单状态为“已接单”和“已取货”时，如果当前骑手不能完成配送，会出现改派操作，例如：将订单从骑手A改派给骑手B，由骑手B完成后续配送，因此会出现同一订单多次返回同一状态不同骑手信息的情况”。
         *   扫码配送模式下“已接单”状态不会进行回调。
         *
         *   courier_name
         *   courier_phone
         *
         *   cancel_reason_id
         *   cancel_reason
         *   predict_delivery_time
         */

        //#1 发单日志

        $log_data = [
            'order_id'=>$params['order_id'],
            'delivery_id'=>$params['delivery_id'],
            'mt_peisong_id'=>$params['mt_peisong_id'],
            'status'=>$params['status'],
            'content'=>'订单状态回调',
        ];
        if(isset($params['courier_name']))
        {
            $log_data['courier_name'] = $params['courier_name'];
        }
        if(isset($params['courier_phone']))
        {
            $log_data['courier_phone'] = $params['courier_phone'];
        }
        if(isset($params['cancel_reason_id']))
        {
            $log_data['cancel_reason_id'] = $params['cancel_reason_id'];
        }
        if(isset($params['cancel_reason']))
        {
            $log_data['cancel_reason'] = $params['cancel_reason'];
        }
        if(isset($params['predict_delivery_time']))
        {
            $log_data['predict_delivery_time'] = $params['predict_delivery_time'];
        }

        $this->logService->saveLog($log_data);

        //#2 更新发单数据

        MtOrder::where(['mt_peisong_id'=>$params['mt_peisong_id']])->update(['status'=>$params['status']]);

        //#3 更新订单状态
        
        // //更新订单状态
        if($params['delivery_id'] && $params['status']==30)
        {
            $send_post = [
                'order_id'=>$params['order_id'],
                'reason'=>'美团自动发货',
                'user_id'=>0,
                'status'=>$params['status'],
                'dly_id'=>$params['delivery_id'],
                'dly_code'=>$params['mt_peisong_id'],
            ];

            $dlyService = new DeliveryService;
            $msg = '';
            $result = $dlyService->autoSendOrder($send_post,'system',$msg);

            Log::info('[EB美团]自动发货',['msg'=>$msg,'send_post'=>$send_post]);

            return $result?true:false;
        }

        if($params['delivery_id'] && $params['status']==50)
        {
            $finish_from = 'system';
            $finish_post = [
                'order_id'=>$params['order_id'],
                'reason'=>'美团配送已送达',
                'user_id'=>0,
                'delivery_type'=>0,
                'dly_id'=>$params['delivery_id'],
                'dly_code'=>$params['mt_peisong_id'],
            ];

            $msg = '';
            $dlyService = new DeliveryService;
            $result = $dlyService->finishDelivery($finish_post,$finish_from,$msg);
        }

        return true;

    }


    // 订单异常回调
    public function updateError($params)
    {
        /**
         * 
         *   delivery_id
         *   mt_peisong_id
         *   order_id
         *   status
         *   exception_id 异常ID，用来唯一标识一个订单异常信息。接入方用此字段用保证接口调用的幂等性。
         *   exception_code
         *   10001：顾客电话关机
         *   10002：顾客电话已停机
         *   10003：顾客电话无人接听
         *   10004：顾客电话为空号
         *   10005：顾客留错电话
         *   10006：联系不上顾客其他原因
         *   10101：顾客更改收货地址
         *   10201：送货地址超区
         *   10202：顾客拒收货品
         *   10203：顾客要求延迟配送
         *   10301：商家出餐慢
         *   10401：商家关店/未营业
         *   10601：联系不上商家
         *   10701：商家定位错误
         * 
         *   exception_descr
         *   exception_time
         *   
         *   courier_name
         *   courier_phone
         *
         * 
         */

        //#1 发单日志

        $log_data = [
            'order_id'=>$params['order_id'],
            'delivery_id'=>$params['delivery_id'],
            'mt_peisong_id'=>$params['mt_peisong_id'],
            'status'=>$params['exception_code'],
            'content'=>'订单异常: '.$params['exception_id'].$params['exception_descr'].' time: '.$params['exception_time'],
        ];
        if(isset($params['courier_name']))
        {
            $log_data['courier_name'] = $params['courier_name'];
        }
        if(isset($params['courier_phone']))
        {
            $log_data['courier_phone'] = $params['courier_phone'];
        }

        $this->logService->saveLog($log_data);


        MtOrder::where(['mt_peisong_id'=>$params['mt_peisong_id']])->update(['status'=>$params['exception_id']]);

        return true;

    }

    // 门店配送风险等级回调
    public function updateShopRisk($params)
    {
        $params = $this->paramsProcess($params);
        if(!$params)
        {
            return falsle;
        }

        MtShop::where(['shop_id'=>$params['shop_id']])->update([
            'delivery_risk_level' => $params['delivery_risk_level'],
        ]);

        return true;

    }

    // 回调处理 END ==========================

    // 处理回调的参数
    public function paramsProcess(array $params)
    {
        $sign = '';
        if(isset($params['sign']))
        {
            $sign = $params['sign'];
            unset($params['sign']);
        }

        $sign_check = $this->checkSign($sign,$params);
        if(!$sign_check)
        {
            Log::info('[EB美团]回调失败',['msg'=>'签名验证失败','params'=>$params]);
            return false;
        }

        return $params;
    }

    //生成签名
    public function makeSign(&$params)
    {
        $params['appkey'] = $this->conf['app_id'];
        $params['timestamp'] = time();
        $params['version'] = '1.0';

        $secret = $this->conf['app_secret'];
        
        ksort($params);

        // $post_data = json_encode($params);
        // $post_data_str = str_replace('=', '', http_build_query($params, null, ''));
        // 由于http_build_query会对中文进行转码

        $post_data_str = '';
        foreach($params as $key=>$v)
        {
            $post_data_str .= $key.$v;
        }

        $sign_str = $secret.$post_data_str;

        // Log::info('签名字符串',[$sign_str]);

        $sign = sha1($sign_str);

        $params['sign'] = $sign;
        return $sign;
    }

    //检查签名
    public function checkSign($sign,$params)
    {

        //#1 验证 appkey
        if($params['appkey'] != $this->conf['app_id'])
        {
            return false;
        }

        $secret = $this->conf['app_secret'];

        ksort($params);

        $post_data_str = '';
        foreach($params as $key=>$v)
        {
            $post_data_str .= $key.$v;
        }

        $sign_str = $secret.$post_data_str;

        $new_sign = sha1($sign_str);
        if($new_sign != $sign)
        {
            return false;
        }
        return true;
    }

    public function postRequest($url,$http_params)
    {

        $httpClient = new HttpClient();
        $http_params = $http_params;

        try {

            $response = $httpClient->request('POST',$url,[
                \GuzzleHttp\RequestOptions::JSON => $http_params,
                'connect_timeout'=>10,
                'read_timeout'=>10,
                'timeout'=>10,
                'headers' => [
                    'Accept'     => 'application/json',
                ]
            ]);
            
            $status_code = $response->getStatusCode();
            
            if($status_code==200){
                
                $result =  $response->getBody();
                $check_result = (string) $result;
                $check_data = json_decode($check_result,true);
                
                return $check_data;
                
            }else{
                $result =  $response->getBody();
                $status_code = $response->getStatusCode();
                $check_result = (string) $result;
                $check_data = json_decode($check_result,true);
                
                Log::error('post error:', ['status_code' => $status_code,'url'=>$url]);
                $msg = '网络异常';
                return false;
            }
            
        }catch(RequestException $e) {

            $response = $e->getResponse();
            $result =  $response->getBody();
            $status_code = $response->getStatusCode();
            $stringBody = (string) $result;
            
            Log::error('post error:', ['status_code' => $status_code,'url'=>$url]);
            $msg = '网络异常';
            return false;
        }

    }

    public function requestPostForm($url,$data,&$msg='',$json=false)
    {
        $httpClient = new HttpClient();

        try {

            $response = $httpClient->request('POST',$url,[
                'form_params'=>$data,
                'connect_timeout'=>10,
                'read_timeout'=>10,
                'timeout'=>10,
                'headers' => [
                    'Accept' => 'application/x-www-form-urlencoded',
                ]
            ]);
            
            $status_code = $response->getStatusCode();
            
            if($status_code==200){
                
                $result =  $response->getBody();
                $check_result = (string) $result;

                if($json){
                    return $check_result;
                }
                $check_data = json_decode($check_result,true);
                
                return $check_data;
                
            }else{
                $result =  $response->getBody();
                $status_code = $response->getStatusCode();
                $check_result = (string) $result;
                if($json){
                    return $check_result;
                }
                $check_data = json_decode($check_result,true);
                
                Log::error('post error:', ['status_code' => $status_code,'url'=>$url]);
                $msg = '网络异常';
                return false;
            }
            
        }catch(RequestException $e) {

            $response = $e->getResponse();
            $result =  $response->getBody();
            $status_code = $response->getStatusCode();
            $stringBody = (string) $result;
            
            Log::error('post error:', ['status_code' => $status_code,'url'=>$url]);
            $msg = '网络异常';
            return false;
        }

    }
}