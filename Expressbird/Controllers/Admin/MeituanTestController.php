<?php

 
namespace App\Extensions\Expressbird\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

use App\Extensions\Expressbird\Controllers\AdminController;

use GuzzleHttp\Client as HttpClient;


use App\Extensions\Expressbird\Models\MtOrder;
use App\Extensions\Expressbird\Models\MtLog;

use App\Extensions\Expressbird\Contracts\ExpressbirdFactory;
use App\Extensions\Expressbird\Services\MeituanTestService;

class MeituanTestController extends AdminController{
    
    public $self_url = 'extension/expressbird';


    public function testOrderView(Request $request,$action)
    {
        $this->setMenu('meituan_menu','test');

        $this->title('模拟测试');

        $mt_peisong_id = $request->mt_peisong_id;
        $delivery_id = $request->delivery_id;
        
        $msg = '';
        if($mt_peisong_id && $delivery_id)
        {
            
            $mtService = new MeituanTestService;

            switch($action)
            {
                case 'arrange':
                    // 模拟接单
                    $result = $mtService->testOrderArrange($delivery_id,$mt_peisong_id,$msg);
                    if($result)
                    {

                        $this->setData('test_success',1);
                    }
                break;

                case 'pickup':
                    // 模拟取货
                    $result = $mtService->testOrderPickup($delivery_id,$mt_peisong_id,$msg);
                    if($result)
                    {

                        $this->setData('test_success',1);
                    }
                break;

                case 'deliver':
                    // 模拟送达
                    $result = $mtService->testOrderDeliver($delivery_id,$mt_peisong_id,$msg);
                    if($result)
                    {

                        $this->setData('test_success',1);
                    }
                break;

                case 'rearrange':
                    // 模拟改派
                    $result = $mtService->testOrderRearrange($delivery_id,$mt_peisong_id,$msg);
                    if($result)
                    {

                        $this->setData('test_success',1);
                    }
                break;

                case 'reportException':
                    // 模拟上传异常
                    $result = $mtService->testOrderReportException($delivery_id,$mt_peisong_id,$msg);
                    if($result)
                    {

                        $this->setData('test_success',1);
                    }
                break;

            }

            

            

            $this->setData('mt_peisong_id',$mt_peisong_id);
            $this->setData('delivery_id',$delivery_id);
        }
        $this->setData('test_fail',$msg);
        
        $this->setData('action',$action);
        
        return $this->display('mt_test.test_view');
    }

    
    public function testOrder(Request $request)
    {
        $action = $request->action;
        
        $search = [];
        $search['mt_peisong_id'] = $request->mt_peisong_id;
        $search['delivery_id'] = $request->delivery_id;
        
        $values = http_build_query($search, null, '&', PHP_QUERY_RFC3986);

        if($values){
            $url = admin_url('extension/expressbird/meituan/test-order-'.$action.'?'.$values);
        }else{
            $url = admin_url('extension/expressbird/meituan/test-order-'.$action);
        }
        return redirect($url);
    }

    
    

}

