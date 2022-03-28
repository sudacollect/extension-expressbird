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


class MeituanOrderController extends AdminController{
    
    public $self_url = 'extension/expressbird';


    public function index(Request $request)
    {
        
        $this->title('订单列表');

        $page_size = 20;
        $page_no = $request->page?$request->page:1;
        $data = MtOrder::where([])->orderBy('created_at','DESC')->paginate($page_size,['*'],'page',$page_no);

        $this->setData('data',$data);
        
        $this->setMenu('meituan_menu','mt_orders');
        return $this->display('mt_order.list');
    }
    
    
    // 所有日志
    public function showLogs(Request $request,$order_id)
    {
        $this->setData('modal_title',$order_id.' 日志');
        $page_size = 10;
        $page_no = $request->page?$request->page:1;

        $order = MtOrder::where(['order_id'=>$order_id])->first();
        $logs = MtLog::where(['order_id'=>$order_id]);
        
        $logs = $logs->orderBy('created_at','DESC')->paginate($page_size,['*'],'page',$page_no);

        $this->setData('data',$logs);
        $this->setData('order_id',$order_id);

        if($request->page)
        {
            return $this->display('mt_order.logs_item');    
        }

        return $this->display('mt_order.logs');
    }


    // 取消订单
    public function cancelOrder(Request $request, ExpressbirdFactory $express,$id)
    {
        $order = MtOrder::where(['id'=>$id])->orderBy('created_at','desc')->first();
        if(!$order)
        {
            return $this->responseAjax('fail','没有订单');
        }

        // 送达和已取消的不可取消
        if(in_array($order->status,[50,99]))
        {
            return $this->responseAjax('fail','不可取消');
        }


        $cancel_reason_id = '199';
        $cancel_reason = '手动取消订单';

        // 101	顾客主动取消
        // 102	顾客更改配送时间/地址
        // 103	备货、包装、货品质量问题取消
        // 199	其他接入方原因
        // 
        // 201	配送服务态度问题取消
        // 202	骑手配送不及时
        // 203	骑手取货不及时
        // 299	其他美团配送原因
        // 399	其他原因

        // 取消订单
        
        $msg = '';
        $params = [
            'delivery_id' => $order->delivery_id,
            'mt_peisong_id' => $order->mt_peisong_id,
            'cancel_reason_id' => $cancel_reason_id,
            'cancel_reason' => $cancel_reason,
        ];
        $result = $express->cancelSend($order->order_id,$params,$msg);
        
        if(!$result)
        {
            return $this->responseAjax('fail',$msg?$msg:'取消失败');
        }

        return $this->responseAjax('success','订单已取消');
    }

    // 重新发送订单
    public function resendOrder(Request $request,ExpressbirdFactory $express, $order_id)
    {
        $log_item = MtLog::where(['order_id'=>$order_id])->orderBy('created_at','desc')->first();
        if(!$log_item)
        {
            return $this->responseAjax('fail','日志异常');
        }

        if(in_array($log_item->status,[0,20,30,50,99]))
        {
            return $this->responseAjax('fail','最新日志状态正常，无需重新发送');
        }

        //检查是否有正常的日志
        if(MtLog::where(['order_id'=>$order_id])->whereIn('status',[0,20,30,50,99])->first())
        {
            return $this->responseAjax('fail','最新日志状态正常，无需再重新发送');
        }

        //进行重新发送
        $result = $express->send($order_id,$log_item->delivery_id);
        
        if(!$result)
        {
            return $this->responseAjax('fail','发送失败');
        }

        return $this->responseAjax('success','发送成功');
    }
    

}

