<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Config;

?>

<h1>x1 shorted url</h1>
<?php $form = ActiveForm::begin([
    'id' => 'MainForm',
]);
?>
    <?= $form->field($model, 'fullurl')->textInput(['autocomplete' => 'off', 'placeholder' => 'url'])->label(false);?>
    <?php
    if (($model->shorturl) != ''){
        echo
            '<label>Short Link:: </label> 
            <a href="'. $model->shorturl.'">'. $model->shorturl.'</a>';
    }
    ?>
    <br>
    <button type="submit"> Get short url</button>
<?php ActiveForm::end(); ?>