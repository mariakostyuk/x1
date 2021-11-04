<?php
namespace common\widgets\forms;

use Yii;
use yii\base\Widget;
use common\models\User;
use yii\helpers\ArrayHelper;
use common\widgets\forms\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\Json;

class UploadFiles extends Widget
{
    public $url;
    public $path;
    public $internetpath;

    protected $jsonFile="list.json";

    public function run()
    {
	$this->url=$_SERVER['REQUEST_URI'];

        $model = new UploadForm();
	$model->path=$this->path;

	if(isset($_GET['deleteFile'])){
	        $filename=preg_replace("/.*[\/\\\]+/","",$_GET['deleteFile']);
	        $this->url=preg_replace("/\&deleteFile=[^\&]+/","",$this->url);
		unlink($this->path."/".$filename);
		$this->updateJSON();
       		Yii::$app->response->redirect([$this->url]);
		return ;
		}

        if (Yii::$app->request->isPost) {
            $model->FileName = UploadedFile::getInstance($model, 'FileName');
            if ($model->upload()) {
		$this->updateJSON();
       		Yii::$app->response->redirect([$this->url]);
		return ;
            }
        }

	$files=$this->getfiles();
        return $this->render('@common/widgets/forms/views/UploadFiles', [
		'model' => $model,
		'files' => $files,
		'internetpath' => $this->internetpath,
		'path' => $this->path,
		'url' => $this->url,
		]);
    }

    public function updateJSON(){
	$json=[];
	$files=$this->getfiles();
	for($i=0;$i<count($files);$i++){
		$json[$i]['image']=$this->internetpath."/".$files[$i];
		}

	$json=Json::encode($json);
	$f=fopen($this->path."/".$this->jsonFile,"w");
	fputs($f,$json);
	fclose($f);
	return $json;
    }

    public function getfiles($ext="",$one=""){
	$i=0;
	$files=[];
	$dir=$this->path;

	if (!is_dir($dir))
		mkdir($dir,0777);

	if (is_dir($dir)) {
	    if ($dh = opendir($dir)) {
		$file="";
	        while ($file = readdir($dh)) {
			if(($file!=".") && ($file!="..") && ($file!=$this->jsonFile)){
				if($ext){
					if(eregi("\.".$ext."$",$file)){
						$files[$i++]=$file;
						if($one)
							return([$file]);
						}
					}
				else	{
					$files[$i++]=$file;
					if($one)
						return([$file]);
					}
				}
        		}
	        closedir($dh);
    		}
		else return [];
	    }
	else return [];
	return $files;
   }

}
