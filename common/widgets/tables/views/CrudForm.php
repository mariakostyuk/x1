<?php
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\Json;
use common\helpers\CurrentURL;
use kartik\color\ColorInput;
use common\widgets\forms\StarRating;

$form = ActiveForm::begin([
    'id' => 'crud-form',
	]);

for($j=0;$j<count($Fields);$j++){

	$field=$Fields[$j][0];
	$fieldType=$Fields[$j][1];
	if(isset($Fields[$j][3])){
		$e=preg_replace("/\{([a-zA-Z0-9_]+)\}/","\$item->\\1",$Fields[$j][3]);
		eval('$e='.$e.';');
		if(!$e)
			continue;
		}

	switch($fieldType){

	        case "SelectOne":
			$v="";
			if(isset($Fields[$j][4]) && $Fields[$j][4])
				$v=$Fields[$j][4];
			if(isset($item->$field))
				$v=$item->$field;
			$data=$Fields[$j][2];
			if($v)
				$item->$field=$v;
			echo $form->field($item, $field)->widget(Select2::classname(), [
			    	'data' => $data,
				'options' => [
					'placeholder' => 'Выбрать',
					],
			    	'pluginOptions' => [
				        'allowClear' => true,
				    ],
				]);
			break;
	        case "SelectOneRequired":
			$v="";
			if(isset($Fields[$j][4]) && $Fields[$j][4])
				$v=$Fields[$j][4];
			if(isset($item->$field))
				$v=$item->$field;
			$data=$Fields[$j][2];
			if($v)
				$item->$field=$v;
			echo $form->field($item, $field)->dropDownList($data);
			break;
	        case "SelectMany":
			$data=$Fields[$j][2];
			$value=Json::decode($item->$field);
			echo $form->field($item, $field)->widget(Select2::classname(), [
			    	'data' => $data,
				'options' => [
					'placeholder' => 'Выбрать',
					'value' => $value,
					],
			    	'pluginOptions' => [
				        'allowClear' => true,
        				'multiple' => true,
				    ],
				]);
			break;

	        case "date":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if((!isset($item->$field)) || (!$item->$field))
				$item->$field=$v;
			echo $form->field($item, $field)->widget('yii\jui\DatePicker', ['dateFormat' => 'dd.MM.yyyy','options'=>['autocomplete'=>'off']]);
			break;
	        case "time":
			echo $form->field($item, $field)->widget('yii\widgets\MaskedInput',[
	            'mask' => '99:99',
	            'options' => [
                    'style' => 'width:40px;',
		            ],
	            ]);
			break;
	        case "document":
			echo $form->field($item, $field)->fileInput();
			$url=CurrentURL::add("deleteFile",$field);
			if(isset($item->$field) && $item->$field && file_exists($_SERVER['DOCUMENT_ROOT'].$item->$field))
				echo "<div><a href=\"".$item->$field."\" target=\"_blank\">Скачать</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"".$url."\">Удалить</a></div><br>";
			break;
	        case "image":
			echo $form->field($item, $field)->fileInput();
			$url=CurrentURL::add("deleteFile",$field);
			if(isset($item->$field) && $item->$field && file_exists($_SERVER['DOCUMENT_ROOT'].$item->$field)){
				$size=getimagesize($_SERVER['DOCUMENT_ROOT'].$item->$field);
				$imageWidth=300;
				if($size[0])
					$imageWidth=(($size[0]<300)?$size[0]:300);
				echo "<a href=\"".$item->$field."\" target=\"_blank\"><img src=\"".$item->$field."?".time()."\" width=".$imageWidth."></a>";
				if($size[0]){
					$imageWidth=(($size[0]<300)?$size[0]:300);
					echo "<br><span style=\"width:300px;background-color:#000000;color:#FFFFFF;font-size:14px;padding:2px;\">".$size[0]."x".$size[1]." - ".$item->$field."</span>";
					}
				echo "<br><a href=\"".$item->$field."\" target=\"_blank\">Посмотреть в полном размере</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"".$url."\">Удалить</a>";
				}
			break;
	        case "hash":
			if(!isset($item->id))
				echo $form->field($item, $field);
			break;
	        case "checkbox":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if(isset($item->$field))
				$v=$item->$field;
			echo $form->field($item, $field)->hiddenInput(['value'=> 0])->label(false);
			echo $form->field($item, $field)->checkbox();
			break;
	        case "input":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if(isset($item->$field))
				$v=$item->$field;
			echo $form->field($item, $field)->textInput(['value'=> $v]);
			break;
	        case "hidden":
			$item->$field=$Fields[$j][2];
			echo $form->field($item, $field)->hiddenInput(['value'=> $Fields[$j][2]])->label(false);
			break;
	        case "number":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if(isset($item->$field))
				$v=$item->$field;
			echo $form->field($item, $field)->textInput(['value'=> $v,'style'=>'width:100px;','type'=>'number']);
			break;
	        case "star":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if(isset($item->$field))
				$v=$item->$field;
			echo StarRating::widget([
			        'form' => $form,
			        'model' => $item,
			        'field' => $field,
				'label' => $className::attributeLabels()[$field],
			        ]);
			break;
	        case "smallinput":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if(isset($item->$field))
				$v=$item->$field;
			echo $form->field($item, $field)->textInput(['value'=> $v,'style'=>'width:100px;']);
			break;                                    
	        case "mediuminput":
			$v="";
			if(isset($Fields[$j][2]) && $Fields[$j][2])
				$v=$Fields[$j][2];
			if(isset($item->$field))
				$v=$item->$field;
			echo $form->field($item, $field)->textInput(['value'=> $v, 'style'=>'width:500px;']);
			break;                                    
	        case "static":
			echo "<strong>".$className::attributeLabels()[$field].":</strong> ".$item->$field."<br>&nbsp;<br>";
			break;
	        case "staticdate":
			echo "<strong>".$className::attributeLabels()[$field].":</strong> ".date("d.m.Y",$item->$field)."<br>&nbsp;<br>";
			break;
	        case "static_datetime":
			echo "<strong>".$className::attributeLabels()[$field].":</strong> ".date("d.m.Y H:i:s",$item->$field)."<br>&nbsp;<br>";
			break;
	        case "color":
			    echo $form->field($item, $field)->widget(ColorInput::classname(), [
			        'options' => ['style'=>'width:100px;'],
			        'html5Container' => ['style'=>'width:70px;'],
			        ]);
			break;
	        case "textarea":
			echo $form->field($item, $field)->textarea(['rows'=>7]);
			break;
	        case "mediumtextarea":
			echo $form->field($item, $field)->textarea(['rows'=>15]);
			break;
	        case "ckeditor":
			$imageBrowser_listUrl="";
			if(file_exists($picturesPath.'/'.$item->id.'/list.json'))
				$imageBrowser_listUrl=$picturesInternetPath.'/'.$item->id.'/list.json?'.time();
			$editorOptions=(isset($picturesInternetPath)?[
				          "extraPlugins" =>"imagebrowser",
			      		]+(($imageBrowser_listUrl)?['imageBrowser_listUrl' => $imageBrowser_listUrl]:[]):[]);
			echo $form->field($item, $field)->widget(CKEditor::className(), [
			        'editorOptions' => $editorOptions,
		  		]);
			break;
	        case "mediumckeditor":
			$imageBrowser_listUrl="";
			if(file_exists($picturesPath.'/'.$item->id.'/list.json'))
				$imageBrowser_listUrl=$picturesInternetPath.'/'.$item->id.'/list.json?'.time();
			$editorOptions=(isset($picturesInternetPath)?[
				          "extraPlugins" =>"imagebrowser",
			      		]+(($imageBrowser_listUrl)?['imageBrowser_listUrl' => $imageBrowser_listUrl]:[]):[]);
			$editorOptions=array_merge(["height" => "500px"]);			
			echo $form->field($item, $field)->widget(CKEditor::className(), [
			        'editorOptions' => $editorOptions,
		  		]);
			break;
	        case "include":
			include($Fields[$j][2]);
			break;
		default:
			echo $form->field($item, $field);
		}
	}



?>
<p>&nbsp;</p>
<button class="btn waves-effect waves-light" type="submit" name="action"><?=((isset($item->id))?"Сохранить":"Добавить");?></button>
<?php
if(isset($item->id))
	echo '&nbsp;&nbsp;&nbsp;<a href="'.$ListURL.'">Отмена</a>';
?>
<?php ActiveForm::end()?>
