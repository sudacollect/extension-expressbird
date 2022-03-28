<?php

 
namespace App\Extensions\Expressbird\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

use App\Extensions\Expressbird\Controllers\AdminController;

use GuzzleHttp\Client as HttpClient;


use App\Extensions\Expressbird\Models\MtLog;
use App\Extensions\Expressbird\Contracts\ExpressbirdFactory;


class MeituanOrderLogController extends AdminController{
    
    public $self_url = 'extension/expressbird';


    public function index(Request $request)
    {
        
        $this->title('日志列表');
        $page_size = 20;
        $page_no = $request->page?$request->page:1;
        $data = MtLog::where([])->with('mtorder')->orderBy('created_at','DESC')->paginate($page_size,['*'],'page',$page_no);

        $this->setData('data',$data);
        
        $this->setMenu('basic_menu','logs');
        return $this->display('order.log.list');
    }


    public function detailLogs(Request $request)
    {
        
        // $this->title('日志列表');
        // $page_size = 20;
        // $page_no = $request->page?$request->page:1;

        // $this->setData('data',$data);
        
        // $this->setMenu('basic_menu','detaillogs');
        // return $this->display('order.log.list');
    }
    

    //重新发单
    public function resend(Request $request,ExpressbirdFactory $express, $id)
    {
        $log = MtLog::where('id',$request->id)->first();

        
        
        $order_id = $log->shop_order_id;

        $pre = $express->preCreate($order_id);

        if(!$pre)
        {
            return $this->responseAjax('fail','预创建订单失败');
        }

        //预创建成功的话，才会直接创建订单信息
        if($pre)
        {
            $result = $express->toCreate($order_id);
        }
        

        return $this->responseAjax('success','重发成功','extension/expressbird/meituan/logs');

    }
}

