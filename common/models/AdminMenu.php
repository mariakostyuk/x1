<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use common\helpers\Arr;

class AdminMenu extends ActiveRecord
{

    public static function tableName(): string
    {
        return '{{%admin_menu}}';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Пункт меню',
            'icon' => 'Иконка',
            'url' => 'URL',
            'order' => 'Последовательность',
        ];
    }

    public function rules()
    {
        return [];
    }

    public function changeOrder($dir)
    {
        if ($dir == "up")
            $one = $this->className()::find()->where('`order`<' . $this->order)->orderBy('`order` DESC')->one();
        else
            $one = $this->className()::find()->where('`order`>' . $this->order)->orderBy('`order`')->one();
        if (!$one)
            return false;

        $order = $one->order;
        $one->order = $this->order;
        $this->order = $order;
        $this->save(false);
        $one->save(false);

        return true;
    }

    // GET ADMIN ITEMS
    public function ItemsForWidget()
    {
        $curURL = $_SERVER['REQUEST_URI'];
        $items = [];
        $am = AdminMenu::find()->orderBy('order')->all();
        for ($i = 0; $i < count($am); $i++) {
            $label = trim($am[$i]->name);
            $items[$label]['label'] = $label;
            $items[$label]['icon'] = "fa fa-" . $am[$i]->icon;
            $items[$label]['url'] = $am[$i]->url;
            $items[$label]['active'] = preg_match("/" . preg_quote($am[$i]->url, "/") . "(\?|$)/", $curURL);
            $items[$label]['order'] = $am[$i]->order;
        }

        foreach ($items as $label => $item) {
            if (preg_match("/\//", $label)) {
                $names = explode("/", $label);
                $names[0] = trim($names[0]);
                if (!isset($items[$names[0]])) {
                    $items[$names[0]]['label'] = $names[0];
                    $items[$names[0]]['icon'] = $item['icon'];
                    $items[$names[0]]['url'] = "#";
                    $items[$names[0]]['order'] = $item['order'];
                }
                $item['label'] = $names[1];
                $items[$names[0]]['items'][] = $item;
                unset($items[$label]);
            }
        }

        $arr = new Arr();
        $arr->data = $items;
        $arr->Sort('order', 'asc', 'number');

        return $arr->data;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $maxOrder = $this->className()::find()->max('`order`');
                $maxOrder++;
                $this->order = $maxOrder;
            }
            return true;
        }

        return false;
    }
}
