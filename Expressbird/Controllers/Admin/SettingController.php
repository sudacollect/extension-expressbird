<?php

 
namespace App\Extensions\Expressbird\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

// use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use App\Extensions\Expressbird\Controllers\AdminController;
use Gtd\Suda\Models\Setting;
use Gtd\Suda\Models\Media;

use GuzzleHttp\Client as HttpClient;

use App\Extensions\Expressbird\Contracts\ExpressbirdFactory;


class SettingController extends AdminController{
    
    public $self_url = 'extension/expressbird/setting';
    
    
    //设置应用参数
    public function showSetting(Request $request,$express_code)
    {
        $this->setMenu($express_code.'_menu','setting');

        $this->breadParent('首页','/');
        $this->breadSet('参数配置');
        $this->title('参数配置');
        $this->gate($express_code.'_menu.setting',app(Setting::class));

        $key_name = 'expressbird_'.$express_code.'_setting';
        if($data = Setting::where(['key'=>$key_name,'group'=>'extension'])->first())
        {
            $this->setData('data',unserialize($data->values));

        }else{
            $this->setData('data',[]);
        }
        
        $this->setData('express_code',$express_code);
        return $this->display('setting.basic');
    }

    public function settingSave(Request $request,$express_code)
    {

        $key_name = 'expressbird_'.$express_code.'_setting';
        
        if(!$request->express_info || !$request->express_info['app_name'] || !$request->express_info['app_id']){
            return $this->responseAjax('fail','请填写完整信息');
        }

        $values = $request->express_info;

        $data = [
            'key'=>$key_name,
            'group'=>'extension',
            'type'=>'text',
            'values'=>serialize($values),
        ];

        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>$key_name,'group'=>'extension'])->first())
        {
            Setting::where(['key'=>$key_name,'group'=>'extension'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }

        Cache::store(config('sudaconf.admin_cache','file'))->forever($key_name,$values);

        return $this->responseAjax('success','参数配置完成');

    }


    //设置URL参数
    public function settingUrl(Request $request,$express_code)
    {
        $this->setMenu($express_code.'_menu','url');

        $this->breadParent('首页','/');
        $this->breadSet('URL设置');

        $this->gate($express_code.'_menu.url',app(Setting::class));

        $this->title('URL参数配置');


        $key_name = 'expressbird_'.$express_code.'_url';

        if($data = Setting::where(['key'=>$key_name,'group'=>'extension'])->first())
        {
            $this->setData('data',unserialize($data->values));

        }else{
            $this->setData('data',[]);
        }
        
        $this->setData('express_code',$express_code);
        return $this->display('setting.url_'.$express_code);
    }

    public function settingUrlSave(Request $request,$express_code)
    {

        $key_name = 'expressbird_'.$express_code.'_url';
        
        if(!$request->express_url){
            return $this->responseAjax('fail','请填写完整信息');
        }


        $values = $request->express_url;

        $data = [
            'key'=>$key_name,
            'group'=>'extension',
            'type'=>'text',
            'values'=>serialize($values),
        ];

        $settingModel = new Setting;
        
        if($first = Setting::where(['key'=>$key_name,'group'=>'extension'])->first())
        {
            Setting::where(['key'=>$key_name,'group'=>'extension'])->update($data);
        }
        else
        {
            $settingModel->fill($data)->save();
        }

        Cache::store(config('sudaconf.admin_cache','file'))->forever($key_name,$values);

        return $this->responseAjax('success','链接参数配置完成');

    }


    public function memo(Request $request)
    {
        $this->title('使用说明');
        $this->setMenu('help_menu');

        // $expressbird = app('expressbird')->channel('sfexpress');

        // dd($expressbird);

        // echo '<pre>';
        // print_r($expressbird->conf);
        // exit();
        


        return $this->display('setting.memo');
    }


    public function showHelp(Request $request,$express_code)
    {
        $this->title('使用说明');
        $this->setMenu($express_code.'_menu.help');


        $this->setData('express_code',$express_code);
        return $this->display('setting.help_'.$express_code);
    }

    public function showMeituanHelp(Request $request)
    {
        
        // echo '<pre>';
        // print_r($expressbird->getCrop());
        // exit();

        // $mtService = app('expressbird')->channel('meituan');
        // echo '<pre>';
        // print_r($mtService->getCorpCode());
        // exit();
        
        
        
        $this->title('使用说明');
        $this->setMenu('meituan_menu.help');


        $this->setData('express_code','meituan');
        return $this->display('setting.help_meituan');
    }
}

