<?php
namespace App\Extensions\Expressbird\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as HttpClient;

use Illuminate\Support\Arr;


use App\Extensions\Expressbird\Models\MtOrder;
use App\Extensions\Expressbird\Models\MtLog;
use App\Extensions\Expressbird\Services\MeituanLogService;


class MeituanTestService
{

    private $host = 'https://peisongopen.meituan.com/api/';

    

    public $ship;
    public $msg;
    public $logService;

    public function __construct()
    {
        

        $this->logService = new MeituanLogService;
        $this->mtService = app('expressbird')->channel('meituan');
    }

    public function testSendOrder($order_id,$delivery_id)
    {
        
    }
   

    // 模拟接单
    public function testOrderArrange($delivery_id,$mt_peisong_id, &$msg='')
    {
        //shop/query
        
        $post = [
            'delivery_id' => $delivery_id,
            'mt_peisong_id' => $mt_peisong_id,
        ];
        $sign = $this->mtService->makeSign($post);
        $url = $this->host.'test/order/arrange';

        $result = $this->requestPostForm($url,$post);

        $tips = '[美团]模拟接单';

        Log::info($tips,['post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info($tips,['post'=>$post]);

            $msg = '模拟接单失败';
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            // 模拟接单成功
            return true;

        }else{
            Log::info($tips,['result'=>$result]);

            $msg = '模拟接单失败: '.$result['code'].$result['message'];
            return false;
        }

        $msg = '意外错误';
        return false;

    }
    
    // 模拟取货
    public function testOrderPickup($delivery_id,$mt_peisong_id)
    {
        //shop/query
        
        $post = [
            'delivery_id' => $delivery_id,
            'mt_peisong_id' => $mt_peisong_id,
        ];
        $sign = $this->mtService->makeSign($post);
        $url = $this->host.'test/order/pickup';

        $result = $this->requestPostForm($url,$post);

        $tips = '[美团]模拟取货';

        Log::info($tips,['post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info($tips,['post'=>$post]);
            
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            

            // 模拟接单成功
            return true;

        }else{
            Log::info($tips,['result'=>$result]);

            return false;
        }

        $msg = '意外错误';
        return false;

    }

    // 模拟送达
    public function testOrderDeliver($delivery_id,$mt_peisong_id)
    {
        //shop/query
        
        $post = [
            'delivery_id' => $delivery_id,
            'mt_peisong_id' => $mt_peisong_id,
        ];
        $sign = $this->mtService->makeSign($post);
        $url = $this->host.'test/order/deliver';

        $result = $this->requestPostForm($url,$post);

        $tips = '[美团]模拟送达';

        Log::info($tips,['post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info($tips,['post'=>$post]);
            
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            
            return true;

        }else{
            Log::info($tips,['result'=>$result]);

            return false;
        }

        $msg = '意外错误';
        return false;

    }

    // 模拟改派
    public function testOrderRearrange($delivery_id,$mt_peisong_id)
    {
        //shop/query
        
        $post = [
            'delivery_id' => $delivery_id,
            'mt_peisong_id' => $mt_peisong_id,
        ];
        $sign = $this->mtService->makeSign($post);
        $url = $this->host.'test/order/rearrange';

        $result = $this->requestPostForm($url,$post);

        $tips = '[美团]模拟改派';

        Log::info($tips,['post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info($tips,['post'=>$post]);
            
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            
            return true;

        }else{
            Log::info($tips,['result'=>$result]);

            return false;
        }

        $msg = '意外错误';
        return false;

    }

    // 模拟上传异常
    public function testOrderReportException($delivery_id,$mt_peisong_id)
    {
        //shop/query
        
        $post = [
            'delivery_id' => $delivery_id,
            'mt_peisong_id' => $mt_peisong_id,
        ];
        $sign = $this->mtService->makeSign($post);
        $url = $this->host.'test/order/reportException';

        $result = $this->requestPostForm($url,$post);

        $tips = '[美团]模拟上传异常';

        Log::info($tips,['post'=>$post,'result'=>$result]);

        if(!$result)
        {
            Log::info($tips,['post'=>$post]);
            
            return false;
        }

        if($result && intval($result['code'])==0)
        {
            
            return true;

        }else{
            Log::info($tips,['result'=>$result]);

            return false;
        }

        $msg = '意外错误';
        return false;

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