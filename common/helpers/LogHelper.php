<?php
namespace common\helpers;

use Yii;
use yii\helpers\Json;
use common\models\Config;

class LogHelper
{

    // WRITES LOG TO FILE
	public function Log($obj, $clearFile=false, $filename=''){
        $str=self::PrepareString($obj);
        if(!$filename)
	        $filename='../runtime/logs/log.txt';
        $f=fopen($filename,(($clearFile)?'w':'a'));
        fputs($f, $str);
        fclose($f);
	}

    // SENDS LOG TO EMAIL
	public function Email($obj, $email="info@simple.solutions"){
	    $adminEmail=Config::ValueOf("adminEmail");
        $str=self::PrepareString($obj);
        Yii::$app->mailer->compose()
            ->setFrom([$adminEmail => $adminEmail])
            ->setTo($email)
            ->setSubject('LogHelper message')
            ->setHtmlBody(str_replace("\t","<br>",$str))
            ->send();
	}

    // PREPARES STRING FOR FURTHER ACTION
	public function PrepareString($obj){
	    if(is_array($obj))
	        $obj=Json::encode($obj);
	    if(strtolower($obj)=='get')
	        if(isset($_GET))
	            $obj="GET: ".Json::encode($_GET);
	        else
	            $obj='No GET parameters';
	    elseif(strtolower($obj)=='post')
	        if(isset($_POST))
	            $obj="POST: ".Json::encode($_POST);
	        else
	            $obj='No POST parameters';
	    $url=$_SERVER['REQUEST_URI'];
	    $ip=$_SERVER['REMOTE_ADDR'];
        $str="[".date("Y-m-d H:i:s").(($ip)?', '.$ip:'')."]\t".$obj."\t".$url."\n";
	    return $str;
	}
    
}
?>