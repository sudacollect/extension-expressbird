<?php
namespace App\Extensions\Expressbird\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as HttpClient;

use Illuminate\Support\Arr;

use App\Extensions\Expressbird\Models\MtLog;


class MeituanLogService
{

    public function __construct()
    {
        // $this->logObj = new MtLog;
    }

    public function saveLog($log_data)
    {
        $logObj = new MtLog;
        $logObj->fill($log_data)->save();
        return true;
    }

    
}