<?php

namespace App\Extensions\Expressbird\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

use Gtd\Suda\Models\Media;



class ExpressbirdCorp extends Model
{

    protected $table = 'expressbird_corps';

    protected $fillable = [
        'logo',"corp_name",'corp_code',"corp_rules",'enable','corp_type'
    ];
    
    protected $appends = ['corp_rules','logo_media'];
    

    public function getLogoMediaAttribute(){

        $media_id = $this->logo;
        if($media_id)
        {
            if($media = Media::where(['id'=>$media_id])->first())
            {
                return $media;
            }
            
        }
        return false;
    }

    public function getCorpRulesAttribute($value)
    {
        if($value)
        {
            return unserialize($value);
        }
        return [];
    }

    
    
}
