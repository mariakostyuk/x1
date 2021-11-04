<?php
namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;

class MyFiles extends Component {

function DeleteDir($dirname){
	if (is_dir($dirname))
	      	$dir_handle = opendir($dirname);
	if (!$dir_handle)
	     	return false;
	while($file = readdir($dir_handle)){
		if ($file != "." && $file != ".."){
           		if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
           		else
                		self::DeleteDir($dirname.'/'.$file);
	      		}
		}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
	}
}
?>