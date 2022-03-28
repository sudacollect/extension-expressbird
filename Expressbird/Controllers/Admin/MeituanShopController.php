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
use App\Extensions\Expressbird\Models\MtShop;

use App\Extensions\Expressbird\Contracts\ExpressbirdFactory;

class MeituanShopController extends AdminController{
    
    public $self_url = 'extension/expressbird';

    public function index(Request $request)
    {
        
        $this->title('门店列表');

        $page_size = 20;
        $page_no = $request->page?$request->page:1;
        $data = MtShop::where([])->orderBy('created_at','DESC')->paginate($page_size,['*'],'page',$page_no);


        $this->setData('data',$data);
        
        $this->setMenu('meituan_menu','shop');
        return $this->display('mt_shop.list');
    }

    public function showDetail(Request $request,$id)
    {
        
        $this->title('门店详情');

        // $page_size = 20;
        // $page_no = $request->page?$request->page:1;
        $data = MtShop::where(['id'=>$id])->first();


        $this->setData('item',$data);
        
        $this->setMenu('meituan_menu','shop');
        return $this->display('mt_shop.detail');
    }

    public function shopQuery(Request $request, ExpressbirdFactory $express, $shop_id='')
    {
        
        $this->title('查询门店');

        $shop_id = $shop_id?$shop_id:$request->shop_id;
        $delivery_service_code = $request->delivery_service_code;
        
        if($shop_id)
        {
            $mtService = $express;
            $result = $mtService->shopQuery($shop_id);
            

            if($delivery_service_code)
            {
                $area_result = $mtService->shopAreaQuery($shop_id,$delivery_service_code);
                if($area_result)
                {

                    $this->setData('areas',$area_result);
                }
                $this->setData('delivery_service_code',$delivery_service_code);
            }

            if($result)
            {

                $this->setData('item',MtShop::where(['shop_id'=>$shop_id])->first()->toArray());
            }

            $this->setData('shop_id',$shop_id);
        }

        
        
        $this->setMenu('meituan_menu','shop');
        return $this->display('mt_shop.query');
    }

    
    public function shopQueryFilter(Request $request)
    {
        $search = [];
        $search['shop_id'] = $request->shop_id;
        $search['delivery_service_code'] = $request->delivery_service_code;
        
        $values = http_build_query($search, null, '&', PHP_QUERY_RFC3986);

        if($values){
            $url = admin_url('extension/expressbird/meituan/shop-query?'.$values);
        }else{
            $url = admin_url('extension/expressbird/meituan/shop-query');
        }
        return redirect($url);
    }

    
    

}

