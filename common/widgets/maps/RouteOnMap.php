<?php
namespace common\widgets\maps;

use Yii;
use yii\base\Widget;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class RouteOnMap extends Widget
{
    public $coords;
    public $YandexApiKey;
    public $width;
    public $height;
    public $MyCitySelectId;

    public function run()
    {
	if(!isset($this->MyCitySelectId))
		$this->MyCitySelectId="CityNotExist";
	if(!isset($this->width))
		$this->width="100%";
	if(!isset($this->height))
		$this->height="100%";
        return $this->render('@common/widgets/maps/views/RouteOnMap',[
		'coords' => $this->coords,
		'YandexApiKey' => $this->YandexApiKey,
		'width' => $this->width,
		'height' => $this->height,
		'MyCitySelectId' => $this->MyCitySelectId,
		]);
    }
}
