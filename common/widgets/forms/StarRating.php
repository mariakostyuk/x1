<?php
namespace common\widgets\forms;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\helpers\CurrentURL;

class StarRating extends Widget
{
    public $model;
    public $field;
    public $form;
    public $label;

    public function run()
    {
	if(!isset($this->label))
		$this->label=false;
        return $this->render('@common/widgets/forms/views/StarRating',[
		'model'=>$this->model,
		'field'=>$this->field,
		'form'=>$this->form,
		'label'=>$this->label,
		]);
    }

}
