<?php

 
namespace App\Extensions\Expressbird\Controllers\Api;

use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Arr;

use App\Extensions\Expressbird\Controllers\ApiController;


use Gtd\Suda\Models\Setting;

use GuzzleHttp\Client as HttpClient;

use App\Extensions\Expressbird\Models\MtOrder;
use App\Extensions\Expressbird\Models\MtLog;


class MeituanCallbackController extends ApiController
{

    //配送状态更改
    public function updateOrderStatus(Request $request)
    {
        $mtService = app('expressbird')->channel('meituan');

        $method = $request->method();
        
        $request_post = $request->all();

        Log::info('[EB美团]配送状态更改',['method'=>$method,'post'=>$request_post,'GET'=>$_GET,'POST'=>$_POST]);

        $sign = '';
        if(isset($request_post['sign']))
        {
            $sign = $request_post['sign'];
            Arr::forget($request_post,'sign');
        }

        $sign_check = $mtService->checkSign($sign,$request_post);
        if(!$sign_check)
        {
            Log::info('[EB美团]配送状态失败',['msg'=>'签名验证失败']);
            return Response::json(['code'=>100,'message'=>'sign check failed']);
        }

        $result = $mtService->updateStatus($request_post['order_id'],$request_post);

        if(!$result)
        {
            Log::info('[EB美团]配送状态失败',['msg'=>'更新配送状态失败']);
            return Response::json(['code'=>101,'message'=>'更新配送状态失败']);
        }

        return Response::json(['code'=>0]);
    }


    // 订单配送异常
    public function updateOrderError(Request $request)
    {
        $method = $request->method();
        
        $post = $request->all();

        Log::info('[EB美团]订单配送异常',['method'=>$method,'post'=>$post,'GET'=>$_GET,'POST'=>$_POST]);

        $sign = '';
        if(isset($post['sign']))
        {
            $sign = $post['sign'];
            Arr::forget($post,'sign');
        }

        $mtService = app('expressbird')->channel('meituan');
        $sign_check = $mtService->checkSign($sign,$post);
        if(!$sign_check)
        {
            Log::info('[EB美团]订单配送异常',['msg'=>'签名验证失败']);
            return Response::json(['code'=>100,'message'=>'sign check failed']);
        }

        $result = $mtService->updateError($post);

        if(!$result)
        {
            Log::info('[EB美团]订单配送异常',['msg'=>'更新mtlog记录失败']);
            return Response::json(['code'=>101,'message'=>'更新mtlog记录失败']);
        }

        return Response::json(['code'=>0]);
    }

    public function updateShopAreas(Request $request)
    {
        Log::info('[EB美团]配送范围变更',['data'=>$request->all()]);
        return $this->responseJson(['code'=>0],200);
    }

    public function updateShopRisk(Request $request)
    {
        Log::info('[EB美团]配送风险等级更新',['data'=>$request->all()]);

        // 更新门店的配送风险等级
        $result = app('expressbird')->channel('meituan')->updateShopRisk($request->all());

        return $this->responseJson(['code'=>0],200);
    }

    public function updateShopStatus(Request $request)
    {
        // 六种状态：10-创建审核驳回、20-创建审核通过、30-创建成功、40-上线可发单、50-修改审核驳回、60-修改审核通过

        Log::info('[EB美团]更新店铺状态',['data'=>$request->all()]);

        return $this->responseJson(['code'=>0],200);
    }


    public function updateRiderStatus(Request $request)
    {
        Log::info('[EB美团]配送员上下班打卡回调',['data'=>$request->all()]);

        return $this->responseJson(['code'=>0],200);
    }

    
    
}

