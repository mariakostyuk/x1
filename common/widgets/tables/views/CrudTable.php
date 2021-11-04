<?php
use common\widgets\tables\DataTable;

$currentURL=$_SERVER['REQUEST_URI'];
//$currentURL=preg_replace("/[\&\?]{1}page=[0-9]+/","",$currentURL);
if(preg_match("/\?/",$currentURL))
	$currentURL.="&";
else
	$currentURL.="?";

$template="
<table width=100% class=\"table table-striped\">
 <tr>";
 for($j=0;$j<count($Coloumns);$j++){
	if(!$Coloumns[$j][1])
		continue;
	$field=$Coloumns[$j][0];
	$filterStr=((isset($Filters[$field]))?"<br>{filter['".$field."']}":"");
	$template.="<th>";
	if(isset($Sort[$Coloumns[$j][0]]))
		$template.="<a href=\"{sortlink['".$field."']}\">";
	// DEFAULT LABEL OR CUSTOM
	if(!isset($Coloumns[$j][2]))
		$template.=$className::attributeLabels()[$field];
	else
		$template.=$Coloumns[$j][2];
	if(isset($Sort[$Coloumns[$j][0]]))
		$template.="&nbsp;{sortarrow['".$field."']}</a>";
	$template.=$filterStr."</th>";
	}
 
	$template.="<th width=\"1%\" style=\"text-align:center;\">";

	if(!isset($addButton))
 		$template.="<a href=\"".$currentURL."action=add\">[&nbsp;добавить&nbsp;]</a>";
	elseif($addButton)
 		$template.="<a href=\"".$addButton."\">[&nbsp;добавить&nbsp;]</a>";

	$template.="</th>
 </tr>
 {row}
 <tr>";

for($j=0;$j<count($Coloumns);$j++){
	$field=$Coloumns[$j][0];
	$template.="{data['".$field."_celldata']}";
	}

 $buttons="";
 $idClass=preg_replace("/.*\\\/","",$className)."Id";
 for($k=0;$k<count($Buttons);$k++){
	if(isset($Buttons[$k][3]) && ($Buttons[$k][3]=='confirm'))
		$buttons.='<a href="#" onclick="JavaScript:if(window.confirm(\'Вы уверены?\'))window.location=\''.$Buttons[$k][0].'?'.$idClass.'={data[\'id\']}\';" title="'.$Buttons[$k][1].'"><i id="w1" class="material-icons">'.$Buttons[$k][2].'</i></a>';
	else
		$buttons.='<a href="'.$Buttons[$k][0].'?'.$idClass.'={data[\'id\']}" title="'.$Buttons[$k][1].'"><i id="w1" class="material-icons">'.$Buttons[$k][2].'</i></a>';
	}

$template.='
  <td nowrap style="text-align:center;">
	'.$buttons;

if(!isset($editButton) || $editButton)
	$template.='
		<a href="'.$currentURL.'action=edit&id={data[\'id\']}" title="Редактировать"><i id="w1" class="material-icons">edit</i></a>';
if($orderButton)
	$template.='
	<a href="'.$currentURL.'action=order&dir=up&id={data[\'id\']}" title="Поднять"><i id="w1" class="material-icons">arrow_upward</i></a>
	<a href="'.$currentURL.'action=order&dir=down&id={data[\'id\']}" title="Опустить"><i id="w1" class="material-icons">arrow_downward</i></a>
	';
if($UploadPictures)
	$template.='
	<a href="'.$currentURL.'action=pictures&id={data[\'id\']}" title="Управление картинками"><i id="w1" class="material-icons">image</i></a>';
if(!isset($deleteButton) || ($deleteButton))
	$template.='
		<a href="#" onclick="JavaScript:if(window.confirm(\'Вы уверены?\'))window.location=\''.$currentURL.'action=delete&id={data[\'id\']}\';" title="Удалить"><i class="material-icons">delete</i></a>';
$template.='
  </td>
{/row}</table>';

echo DataTable::widget([
        'template' => $template,
	'data' => $items,
	'perPage' => $perPage,
	'url' => $ListURL,
	'ExcelButton'=>false,
	'filters' => $Filters,
	'Sort' => $Sort,
	]);

?>