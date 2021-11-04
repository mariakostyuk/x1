<?php

namespace frontend\models;

use common\models\Urllink;
use yii\base\Model;
use Yii;
use common\models\Config;

class MainForm extends Model
{
    public $fullurl;
    public $shorturl;



    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Please, input url'],
        ];
    }

    public function Send()
    {
        $shorturlT = Urllink::find()->where(['full_link' => $this->fullurl])->one();
        if (isset($shorturlT)){
            $this->shorturl = 'http://x1front/'.$shorturlT['short_link'];
        }
        else{
            $user = new Urllink();
            $user->full_link = $this->fullurl;
            $user->short_link = Urllink::getRandomToken();
            $user->save();
            $this->shorturl =  'http://x1front/'.$user->short_link;
        }
    }
}