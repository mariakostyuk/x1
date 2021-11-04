<?php
namespace common\helpers;

use Yii;

class CurrentURL
{
	public function add($parameter,$value,$url=""){
		if(!$url)
			$currentURL=$_SERVER['REQUEST_URI'];
		else
			$currentURL=$url;
		$currentURL=preg_replace("/[\&\?]{1}".$parameter."=[^\&]+/","",$currentURL);
		if(preg_match("/\?/",$currentURL))
			$currentURL.="&";
		else
			$currentURL.="?";
		$currentURL.=$parameter."=".$value;
		$currentURL=preg_replace("/\?/","&", $currentURL);
		$currentURL=preg_replace("/\&/","?", $currentURL, 1);

		return $currentURL;
	}

	public function delete($parameter,$url=""){
		if(!$url)
			$currentURL=$_SERVER['REQUEST_URI'];
		else
			$currentURL=$url;
		if(is_array($parameter)){
		   for($i=0;$i<count($parameter);$i++)
			$currentURL=preg_replace("/[\&\?]{1}".$parameter[$i]."=[^\&]+/","",$currentURL);
		   }
		else
			$currentURL=preg_replace("/[\&\?]{1}".$parameter."=[^\&]+/","",$currentURL);
		if(!preg_match("/\?/",$currentURL))
			$currentURL=preg_replace("/\&/","?",$currentURL,1);
		return $currentURL;
	}

	public function GoBack(){
		echo "<script>window.history.back();</script>";
		Yii::$app->end();
	}
}
?>