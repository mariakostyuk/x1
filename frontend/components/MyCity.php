<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;

class MyCity extends Component {

    public $cityId;

    public  function setCityId($cityId){
	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'myCity',
		'value' => $cityId,
		]));
    }

    public function getCityId(){
	$this->cityId = Yii::$app->getRequest()->getCookies()->getValue('myCity');
	return $this->cityId;
    }


    public  function clear(){

	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'myCity',
		'value' => '',
		]));
    }

}
?>