<?php
use yii\widgets\ActiveForm;
$form = ActiveForm::begin([
    	'id' => 'upload-files-form',
	'options' => ['enctype' => 'multipart/form-data'],
	]);

echo $form->field($model, 'FileName')->fileInput()->label(false);
?>
<button class="btn.block" type="submit" name="action">Добавить файл</button>
<?php ActiveForm::end()?>
<br>
<table width=100%  class="table table-striped">
<tr>
<th>Имя файла</th>
<th style="text-align:center;">Ширина (px)</th>
<th style="text-align:center;">Высота (px)</th>
<th>&nbsp;</th>
</tr>
<?php
for($i=0;$i<count($files);$i++){
	$size=getimagesize($path."/".$files[$i]);
        echo "<td><a href=\"".$internetpath."/".$files[$i]."\" target=\"_blank\" title=\"".$internetpath."/".$files[$i]."\">".$files[$i]."</a></td>";
	if($size[0])
		echo "<td style=\"text-align:center;\">".$size[0]."</td><td style=\"text-align:center;\">".$size['1']."</td>";
	else
		echo "<td>&nbsp;</td><td>&nbsp;</td>";
	echo "<td><a href=\"#\" onclick=\"JavaScript:if(window.confirm('Вы уверены?'))window.location='".$url."&deleteFile=".$files[$i]."';\" title=\"Удалить\"><i class=\"material-icons\">delete</i></a></td>";
	echo "</tr>";
	}
?>
</table>
