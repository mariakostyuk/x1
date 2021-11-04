<?php
namespace common\widgets\tables;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use common\helpers\CurrentURL;
use yii\web\UploadedFile;

class CrudTable extends Widget
{
    public $ListURL="";
    public $className;
    public $Coloumns;                   
    public $Fields;
    public $Filters;
    public $sortBy;                   
    public $Sort=[];                   
    public $UploadPictures=false;
    public $Buttons=[];
    public $Where="";
    public $addButton;
    public $orderButton;
    public $deleteButton;
    public $editButton;
    public $afterSaveURL;
    public $afterAddURL;
    public $Validate="";

    protected $perPage=50;
    protected $tableName;
    protected $page;
    protected $picturesPath="/uploads/";
    protected $picturesInternetPath="/uploads/";

    public function run()
    {
	$this->picturesPath=$_SERVER['DOCUMENT_ROOT'].$this->picturesPath;
	$protocol="http";	
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on")
		$protocol="https";

	$this->tableName=preg_replace("/.*[\/\\\]{1}/","",$this->className);
	$this->picturesPath.=$this->tableName;
	$this->picturesInternetPath.=$this->tableName;

	if (!is_dir($this->picturesPath))
		mkdir($this->picturesPath,0777);

	if(isset($_GET['action'])){
	        switch($_GET['action']){
	                case "add":
				$item=Yii::createObject([
			          	'class' => $this->className,
				         ]);

				if(isset($_POST[$this->tableName])){
					$item->setAttributes($_POST[$this->tableName],false);
					$valid=true;
					if($this->Validate){
						$Validate=$this->Validate;
						$valid=$item->$Validate();
						}

					for($i=0;$i<count($this->Fields);$i++){
						$fieldName=$this->Fields[$i][0];
						$fieldType=$this->Fields[$i][1];

						switch($fieldType){
							case "date":
								$item->$fieldName=strtotime($_POST[$this->tableName][$fieldName]);
								break;
							case "hash":
								$item->$fieldName=Yii::$app->security->generatePasswordHash($_POST[$this->tableName][$fieldName]);
								break;
							case "SelectMany":
								$item->$fieldName=Json::encode($_POST[$this->tableName][$fieldName]);
								break;                                                                      
							}
						}

					foreach($_POST[$this->tableName] as $fieldName=>$value)
						if(is_array($value))
							$item->$fieldName=Json::encode($value);

					if($valid){
					   $item->save(false);
					   $this->SaveDocuments($item);
		        		   Yii::$app->getSession()->setFlash('success', 'Добавлено.');
					   if(isset($this->afterAddURL) && $this->afterAddURL){
						$url=preg_replace("/\{id\}/",$item->id,$this->afterAddURL);
		        			Yii::$app->response->redirect([$url]);
						}
					   else {
		        		   	Yii::$app->response->redirect([CurrentURL::delete(['action'])]);
//		        			Yii::$app->response->redirect([$this->ListURL]);
						}
					   Yii::$app->end();
					   }
					}

			        return $this->render('@common/widgets/tables/views/CrudForm',[
			                'item'=>$item,
					'className' => $this->className,
					'Fields' => $this->Fields,					
					'picturesPath' => $this->picturesPath,
					]);
				break;

	                case "edit":
				$item=$this->className::find()->where("id=".ceil($_GET['id']).(($this->Where)?" AND (".$this->Where.")":""))->one();
				if(!$item)
				    return false;
				$this->DeleteFile($item);
				
				if(isset($_POST[$this->tableName])){
					$item->setAttributes($_POST[$this->tableName],false);
					$valid=true;
					if($this->Validate){
						$Validate=$this->Validate;
						$valid=$item->$Validate();
						}

					 for($i=0;$i<count($this->Fields);$i++){
						$fieldName=$this->Fields[$i][0];
						$fieldType=$this->Fields[$i][1];

						switch($fieldType){
							case "date":
								$item->$fieldName=strtotime($_POST[$this->tableName][$fieldName]);
								break;
							case "SelectMany":
								$item->$fieldName=Json::encode($_POST[$this->tableName][$fieldName]);
								break;                                                                      
							case "image":
								unset($item->$fieldName);
								break;                                                                      
							case "document":
								unset($item->$fieldName);
								break;                                                                      
							}
						}

					foreach($_POST[$this->tableName] as $fieldName=>$value)
						if(is_array($value))
							$item->$fieldName=Json::encode($value);

					if($valid){
					   $item->save(false);
				   	   $this->SaveDocuments($item);
		        		   Yii::$app->getSession()->setFlash('success', 'Сохранено.');
					   if(isset($this->afterSaveURL) && $this->afterSaveURL)
		        		   	Yii::$app->response->redirect([$this->afterSaveURL]);
					   else
		        		   	Yii::$app->response->redirect([CurrentURL::delete(['id','action'])]);
					   Yii::$app->end();
					   }
					}

			        return $this->render('@common/widgets/tables/views/CrudForm',[
			                'item'=>$item,
					'ListURL' => $this->ListURL,
					'className' => $this->className,
					'Fields' => $this->Fields,					
					'picturesInternetPath' => $this->picturesInternetPath,
					'picturesPath' => $this->picturesPath,
					]);
				break;

	                case "pictures":

				if(isset($_GET['id']))
					$id=$_GET['id'];
				else
					exit;

				$item=$this->className::findOne($id);

			        return $this->render('@common/widgets/tables/views/CrudPictures',[
					'item' => $item,
					'ListURL' => $this->ListURL,
					'picturesInternetPath' => $this->picturesInternetPath,
					'picturesPath' => $this->picturesPath,
					]);
				break;

	                case "delete":
				$where="id=".$_GET['id'];
				if($this->Where)
					$where.=" AND (".$this->Where.")";
				$item=$this->className::find()->where($where)->one();
				if($item)
					$item->delete();
	        		Yii::$app->getSession()->setFlash('success', 'Удалено.');
	        		Yii::$app->response->redirect([$this->ListURL]);
				Yii::$app->end();
				break;

	                case "order":
				$item=$this->className::findOne($_GET['id']);
				if(isset($_GET['dir'])){
					$item->changeOrder($_GET['dir']);
	        			Yii::$app->getSession()->setFlash('success', 'Последовательность изменена.');
					}
//	        		Yii::$app->response->redirect([$this->ListURL]);
	        		Yii::$app->response->redirect([CurrentURL::delete(['id','action','dir'])]);
				Yii::$app->end();
				break;
			}
		}


	if(isset($_GET['page']))
		$this->page=$_GET['page'];
	else
		$this->page=1;

	$select=[];
	for($i=0;$i<count($this->Coloumns);$i++){
		if(!preg_match("/_cell/",$this->Coloumns[$i][0]))
		        $select[]=$this->Coloumns[$i][0];
		if(!$this->Coloumns[$i][1])
			continue;
		if(preg_match("/\{from\./",$this->Coloumns[$i][1])){
		    $varname=preg_replace("/^.*\{from\./","",$this->Coloumns[$i][1]);
		    $varname=preg_replace("/\}.*$/","",$varname);
		    $$varname=$this->className::$varname();
	            $this->Coloumns[$i][1]=preg_replace("/\{from\.[^\}]+\}/",'$'.$varname.'[{'.$this->Coloumns[$i][0].'}]',$this->Coloumns[$i][1]);
		    }
		for($j=0;$j<count($this->Coloumns);$j++)
			$this->Coloumns[$i][1]=preg_replace("/\{".$this->Coloumns[$j][0]."\}/","\$items[\$i]['".$this->Coloumns[$j][0]."']",$this->Coloumns[$i][1]);
		if(!preg_match("/^\"\<td.*/",$this->Coloumns[$i][1]))
			$this->Coloumns[$i][1]='"<td>".'.$this->Coloumns[$i][1].'."</td>"';
		$this->Coloumns[$i][1]=$this->Coloumns[$i][1].";";
		}
	$select=join(",",$select);
	
	$items=$this->className::find()->select($select)->where($this->Where)->orderBy($this->sortBy)->asArray()->all();


	for($i=0;$i<count($items);$i++)
	   for($j=0;$j<count($this->Coloumns);$j++){
		$fieldName=$this->Coloumns[$j][0];
		if($this->Coloumns[$j][1]){
			eval("\$a=".$this->Coloumns[$j][1]);
			$items[$i][$fieldName."_celldata"]=$a;
			}
		else
			$items[$i][$fieldName."_celldata"]="";
		}

	$ItemsTotal=$this->className::find()->count();
	$TotalPages=ceil($ItemsTotal / $this->perPage);

        return $this->render('@common/widgets/tables/views/CrudTable', [
		'items' => $items,
		'className' => $this->className,
		'ItemsTotal' => $ItemsTotal,
		'TotalPages' => $TotalPages,
		'page' => $this->page,
		'perPage' => $this->perPage,
		'Coloumns' => $this->Coloumns,
		'ListURL' => $this->ListURL,
		'Filters' => $this->Filters,
		'UploadPictures' => $this->UploadPictures,
		'Buttons' => $this->Buttons,
		'addButton' => $this->addButton,
		'editButton' => $this->editButton,
		'orderButton' => $this->orderButton,
		'deleteButton' => $this->deleteButton,
		'Sort' => $this->Sort,
	        ]);
    }


    // SAVE DOCUMENTS ON SERVER
    public function SaveDocuments($item){
	for($i=0;$i<count($this->Fields);$i++){
	   $fieldName=$this->Fields[$i][0];
	   $fieldType=$this->Fields[$i][1];
	   if(in_array($fieldType,['image','document'])){
	   	$fieldTemplate=$this->Fields[$i][2];
		$document = UploadedFile::getInstance($item, $fieldName);
		if(isset($document->extension) && (in_array(strtolower($document->extension),['jpg','png','jpeg','pdf','gif'])) && ($document)){
			$fieldTemplate=preg_replace("/\{id\}/",$item->id,$fieldTemplate);
			$fieldTemplate=preg_replace("/\{name\}/",$document->name,$fieldTemplate);
			for($i1=0;$i1<count($this->Fields);$i1++){
				$fieldName1=$this->Fields[$i1][0];
				$fieldTemplate=preg_replace("/\{".$fieldName1."\}/",$item->$fieldName1,$fieldTemplate);
				}
			$filename = $fieldTemplate;
			$item->$fieldName="/".$filename;
			$item->save(false);
	        $document->saveAs($filename);
	        
            // RESIZE IMAGE
	        if($fieldType && isset($this->Fields[$i]['resize'])){
                $size=$this->Fields[$i]['resize'];
                $this->ResizeImage($filename, $size['width'], $size['height']);
	            }
			}
		}
	   }
	}                

    public function DeleteFile($item){
	if(!isset($_GET['deleteFile']))
		return false;
	$field=$_GET['deleteFile'];
	$url=CurrentURL::delete('deleteFile');
	$path=$_SERVER['DOCUMENT_ROOT'].$item->$field;
//	@unlink($path);	
	$item->$field="";
	$item->save(false);
	Yii::$app->response->redirect($url);
	Yii::$app->end();
	}
	
	public function ResizeImage($filename, $max_width, $max_height){
        list($orig_width, $orig_height) = getimagesize($filename);
        $width = $orig_width;
        $height = $orig_height;
        if ($height > $max_height) {
            $width = ($max_height / $height) * $width;
            $height = $max_height;
        }
        if ($width > $max_width) {
            $height = ($max_width / $width) * $height;
            $width = $max_width;
        }
        $image_p = imagecreatetruecolor($width, $height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);
        imagejpeg($image_p, $filename, 100);
        imagedestroy($image_p);
    }
}
