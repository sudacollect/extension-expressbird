<?php

namespace App\Extensions\Expressbird\Traits;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use ReflectionClass;
use Illuminate\Support\Facades\Cache;

use Gtd\Suda\Models\Setting;

use App\Extensions\Expressbird\Models\ExpressbirdCorp;

trait ExpressbirdTrait
{
    public function getCorp()
    {
        return ExpressbirdCorp::where(['corp_code'=>$this->corp_code])->first();
    }
    
    public function getCorpCode()
    {
        return $this->corp_code;
    }

    //获取应用信息
    public function getConfigSetting($express_code){
        
        
        $key_name = 'expressbird_'.$express_code.'_setting';

        
        $config_setting = Cache::store(config('zhila.admin_cache','file'))->get($key_name);

        if(!$config_setting)
        {
            $config = Setting::where(['key'=>$key_name,'group'=>'extension'])->first();
            if($config){
                $config_setting = unserialize($config->values);
            }
        }

        if(!$config_setting)
        {
            $config_setting = [
                'max_distance'          =>  5,      //最大配送距离
                'max_distance_notice'   =>  '超出配送距离无法完成配送',  //最大配送距离
                'begin_km'              =>  3,      //起始距离
                'begin_weight'          =>  5,      //起始重量
                'begin_cost'            =>  10,     //起始运费，单位元
                'km_cost'               =>  2,      //超出后每公里
                'weight_cost'           =>  2,      //超出后每公斤
            ];
        }

        return $config_setting;
    }
    
}
