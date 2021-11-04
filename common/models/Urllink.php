<?php

namespace common\models;


use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use common\helpers\Arr;

class Urllink extends ActiveRecord
{
    static $SYMBOLS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function tableName(): string
    {
        return '{{%urllink}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'full_link' => 'Full link',
            'short_link' => 'Short link',
        ];
    }
    public function rules()
    {
        return [];
    }

    public static function getFullLink($url){
        $urlLink = Urllink::find()->where(['short_link' => $url])->one();

        if ($urlLink){
            return $urlLink['full_link'];
        }
        return false;
    }

    public static function getRandomToken(){
        do {
            $token = '';
            for ($i = 0; $i < 6; $i++) {
                $token[$i] = Urllink::$SYMBOLS[rand(0, strlen(Urllink::$SYMBOLS))];
            }
        } while (Urllink::find()->where(['short_link' => $token])->one());
        return $token;
    }
}