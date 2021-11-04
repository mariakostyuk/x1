<?php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
	'method' => 'get',
	'action' => $url,
	]);

echo $header;
echo $body;
echo $footer;

ActiveForm::end();

$currentURL=$_SERVER['REQUEST_URI'];
$currentURL=preg_replace("/[\&\?]{1}page=[0-9]+/","",$currentURL);
if(preg_match("/\?/",$currentURL))
	$currentURL.="&";
else
	$currentURL.="?";

if(ceil($total/$perPage)>1){
   echo "<ul class=\"pagination\">";
   for($i=0;$i<ceil($total/$perPage);$i++){
	if(($i==($page-10)) && ($i>0))
		echo "<li class=\"paginate_button\"><a href=".$currentURL."page=".($i).">...</a></li>";
	if(($i>=$page-10) && ($i<=$page+10))	
	        echo "<li class=\"paginate_button ".(($page==($i+1))?"active":"")."\"><a href=".$currentURL."page=".($i+1).">".($i+1)."</a></li>";
	if(($i==($page+10)) && ($i<(ceil($total/$perPage)-1)))
		echo "<li class=\"paginate_button\"><a href=".$currentURL."page=".($i+2).">...</a></li>";
	}
   echo "</ul>";
   }

if($ExcelButton)
	echo "<div style=\"text-align:right;\"><a href=\"".$currentURL."xls=yes\">XLS</a></div>";
if($PDFButton)
	echo "<div class='txt-right '><a class='button btn-border-violet mt-null' href=\"".$currentURL."pdf=yes\">".$PDFButton."</a></div>";
?>