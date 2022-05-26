<?php

 
namespace App\Extensions\Expressbird\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

use App\Extensions\Expressbird\Controllers\AdminController;
use Gtd\Suda\Models\Setting;

use GuzzleHttp\Client as HttpClient;

class HomeController extends AdminController{
    
    public $self_url = 'extension/expressbird/setting';
    
    public function index(Request $request)
    {
        $this->gate('basic_menu.printer_status',app(Setting::class));
        
        $this->title('首页');


        // $this->setData('data',$data);
        
        $this->setMenu('basic_menu','printer_status');
        return $this->display('index');
    }
    
    public function setting()
    {

        $this->gate('basic_menu.printer_manage',app(Setting::class));
        
        $this->title('设备管理');
        
        $this->setData('data_list',[]);

        
        
        $this->setMenu('basic_menu','printer_manage');
        return $this->display('setting');
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
    
    
    
}

