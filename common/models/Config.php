<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Config extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%config}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'key' => 'Код параметра',
            'value' => 'Значение',
            'value_type' => 'Тип значения',
            'description' => 'Описание',
        ];
    }

    public function rules()
    {
        return [
            [['key'], 'required'],
        ];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function ValueOf($key)
    {
        $one = Config::find()->where(['key' => $key])->one();
        if (!$one)
            return "";
        $value = $one->value;

        switch ($key) {
            case "ShoppingCartText":
                $value = preg_replace("/\{date\}/", date("d.m.Y", time()), $value);
                break;
        }

        return $value;
    }
}
