<?php

namespace common\widgets\tables;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use common\helpers\Arr;
use yii\widgets\MaskedInput;

class DataTable extends Widget
{
    public $url="";
    public $type;
    public $perPage;
    public $page;
    public $data;
    public $template;
    public $total=0;
    public $ExcelButton=false;
    public $PDFButton=false;
    public $excel=false;
    public $pdf=false;
    public $Sort=[];


    public $filters=[];



    public function run()
    {
	if(isset($_GET['page']))
		$this->page=ceil($_GET['page']);
	else
		$this->page=1;

	$arr=new Arr();
	$arr->data=$this->data;
	$arr=$this->ApplyFilters($arr);
	$arr=$this->ApplySort($arr);
	$this->total=$arr->Count();
	$sum=$arr->Sums();

	if((!$this->excel) && (!$this->pdf))
		$arr->Cut(($this->page-1)*$this->perPage,$this->perPage);

	$this->ParseSum($sum);
	$this->ParseSort();
	$this->ParseFilters();

	$header=preg_replace("/\{row[0-9]*\}.*/","",preg_replace("/[\n\r]+/","",$this->template));
	$footer=preg_replace("/.*\{\/row[0-9]*\}/","",preg_replace("/[\n\r]+/","",$this->template));

	$row_template['row']=preg_replace("/.*\{row\}/","",$this->template);
	$row_template['row']=preg_replace("/\{\/row\}.*/","",$row_template['row']);

//print_r(preg_replace("/[\n\r]+/","",$footer));
//exit;

	for($i=0;$i<count($arr->data);$i++){
		$t="row";
		if(isset($arr->data[$i]['TableRow']))
			$t=$arr->data[$i]['TableRow'];
		$row_template[$t]=preg_replace("/.*\{".$t."\}/","",$this->template);
		$row_template[$t]=preg_replace("/\{\/".$t."\}.*/","",$row_template[$t]);
		}

	$rows=[];
	for($i=0;$i<count($arr->data);$i++){
		$t="row";
		if(isset($arr->data[$i]['TableRow']))
			$t=$arr->data[$i]['TableRow'];
	        $rows[]=$this->ParseRow($arr->data[$i],$row_template[$t],($this->page-1)*$this->perPage+$i+1);
		}
	$body=join("",$rows);

	if($this->excel)
            return $this->render('@common/widgets/tables/views/DataTableXLS', [
		'header' => $header,
		'footer' => $footer,
		'body' => $body,
	        ]);

	if($this->pdf)
            return $this->render('@common/widgets/tables/views/DataTablePDF', [
		'header' => $header,
		'footer' => $footer,
		'body' => $body,
		'fileName' => $this->pdf,
	        ]);

        return $this->render('@common/widgets/tables/views/DataTable', [
		'header' => $header,
		'footer' => $footer,
		'body' => $body,
		'page' => $this->page,
		'perPage' => $this->perPage,
		'total' => $this->total,
		'url' => $this->url,
		'ExcelButton'=>$this->ExcelButton,
		'PDFButton'=>$this->PDFButton,
	        ]);
    }

    public function ParseSum($sum){

	foreach($sum as $field=>$value){
		$this->template=preg_replace("/{sum\['".$field."'\]}/",$value,$this->template);
		}
	$this->template=preg_replace("/{sum\['[^']+'\]}/",0,$this->template);

    }

    public function ParseSort(){

	if(!isset($_SERVER['REQUEST_URI']))
		return true;

	$currentURL=$_SERVER['REQUEST_URI'];
	$currentURL=preg_replace("/[\&\?]{1}page=[0-9]+/","",$currentURL);
	$currentURL=preg_replace("/[\&\?]{1}sortBy=[^\&]+/","",$currentURL);
	$currentURL=preg_replace("/[\&\?]{1}sortWay=[^\&]+/","",$currentURL);
	if(preg_match("/\?/",$currentURL))
		$currentURL.="&";
	else
		$currentURL.="?";

	$this->template=preg_replace("/[\n\r]+/","",$this->template);
	if(count($this->Sort))
 	  foreach($this->Sort as $field=>$type){

		$sortWay="asc";
                $arrow="";
		if(isset($_GET['sortBy']) && $_GET['sortBy']==$field)
		   if(isset($_GET['sortWay']) && $_GET['sortWay']=="asc"){
			$arrow="&darr;";
			$sortWay="desc";
			}
		   else {
			$arrow="&uarr;";
			}
				
		$this->template=str_replace("{sortlink['".$field."']}",$currentURL."sortBy=".$field."&sortWay=".$sortWay,$this->template);
		$this->template=str_replace("{sortarrow['".$field."']}",$arrow,$this->template);
		}
	return true;
    }


    public function ApplySort($arr){
	if(isset($_GET['sortBy']) && isset($_GET['sortWay'])){
	        $sortBy=$_GET['sortBy'];
	        $sortWay=$_GET['sortWay'];
		$sortType=$this->Sort[$sortBy];
		$arr->sort($sortBy,$sortWay,$sortType);	
		}
	return $arr;
    }



    public function ParseFilters(){
	if($this->pdf)
		return true;
	$this->template=preg_replace("/[\n\r]+/","",$this->template);
	if(isset($this->filters))
 	  foreach($this->filters as $key=>$filter){
		switch($filter['type']){

		        case "select":
				$value="";
				if(isset($_GET['filter'][$key]))
					$value=$_GET['filter'][$key];
				$select=Html::dropDownList('filter['.$key.']', $value, $filter['data'],['onchange'=>'this.form.submit()']);
				$this->template=str_replace("{filter['".$key."']}",$select,$this->template);
				$this->template=preg_replace("/[\n\r]+/","",$this->template);
				break;

		        case "number":
				$value="";
				if(isset($_GET['filter'][$key]))
					$value=$_GET['filter'][$key];
				$number=Html::textInput('filter['.$key.']', $value, ['style'=>'width:100%;','onkeypress'=>'if(event.keyCode==13)this.form.submit();','onblur'=>'this.form.submit()','placeholder'=>'0,>1,<2,>=3,<=4,<>5']);
				$this->template=str_replace("{filter['".$key."']}",$number,$this->template);
				$this->template=preg_replace("/[\n\r]+/","",$this->template);
				break;

		        case "text":
				$value="";
				if(isset($_GET['filter'][$key]))
					$value=$_GET['filter'][$key];
				$text=Html::textInput('filter['.$key.']', $value, ['style'=>'width:100%;','onkeypress'=>'if(event.keyCode==13)this.form.submit();','onblur'=>'this.form.submit()','placeholder'=>'']);

				if(isset($filter['mask']))
					$text=MaskedInput::widget([
						'name' => 'filter['.$key.']',
						'value'=> $value,
						'mask' => $filter['mask'],
						'options' => [
							'style'=>'width:100%;','onkeypress'=>'if(event.keyCode==13)this.form.submit();','onblur'=>'this.form.submit()'
							],
						]);

				$this->template=str_replace("{filter['".$key."']}",$text,$this->template);
				$this->template=preg_replace("/[\n\r]+/","",$this->template);
				break;

		        case "date":
				$value="";
				if(isset($_GET['filter'][$key]))
					$value=$_GET['filter'][$key];


				$date=\yii\jui\DatePicker::widget([
			                    'name' => 'filter['.$key.']',
			                    'value' => $value,
			                    'dateFormat' => 'dd.MM.yyyy',
	                              	    'options' => [
                                    		'onchange' => 'this.form.submit();',
						'style'=>'width:100%;',
		                                ],
			                ]);

				$this->template=str_replace("{filter['".$key."']}",$date,$this->template);
				$this->template=preg_replace("/[\n\r]+/","",$this->template);
				break;

		        case "period":
				$value_from="";
				$value_to="";
				if(isset($_GET['filter'][$key]['from'])){
					$value_from=$_GET['filter'][$key]['from'];
					}
				if(isset($_GET['filter'][$key]['to'])){
					$value_to=$_GET['filter'][$key]['to'];
					}


				$date_from=\yii\jui\DatePicker::widget([
			                    'name' => 'filter['.$key.'][from]',
			                    'value' => $value_from,
			                    'dateFormat' => 'dd.MM.yyyy',
	                              	    'options' => [
                                    		'onchange' => 'this.form.submit();',
					    	'placeholder' => 'от',
						'style'=> 'width:'.(isset($filter['width'])?$filter['width']:'100%;'),
		                                ],
			                ]);

				$date_to=\yii\jui\DatePicker::widget([
			                    'name' => 'filter['.$key.'][to]',
			                    'value' => $value_to,
			                    'dateFormat' => 'dd.MM.yyyy',
	                              	    'options' => [
                                    		'onchange' => 'this.form.submit();',
					    	'placeholder' => 'до',
						'style'=> 'width:'.(isset($filter['width'])?$filter['width']:'100%;'),
		                                ],
			                ]);

				$this->template=str_replace("{filter['".$key."']}",$date_from.$date_to,$this->template);
				$this->template=preg_replace("/[\n\r]+/","",$this->template);
				break;
			}
		}
	return true;
    }

    public function ApplyFilters($arr){
	if(isset($_GET['filter'])){
	        $filters=$_GET['filter'];
		}
	else	
	        return $arr;
		
	foreach($filters as $key=>$value){
		if(!is_array($value))
			if(trim($value)=="")
		        	continue;

		if(isset($this->filters[$key]['operation']))
                if($this->filters[$key]['operation']=="json"){
			$this->filters[$key]['operation']="like";
			$value='"'.$value.'"';
			}

		switch($this->filters[$key]['type']){

		        case "select":
				if(!isset($this->filters[$key]['operation']))
					$operation="=";
				else
					$operation=$this->filters[$key]['operation'];
                                $arr->filter($this->filters[$key]['field'],$operation,$value);
				break;

		        case "number":
				$value=trim($value);
				$value=preg_replace("/,/"," ",$value);
				$value=preg_replace("/[ ]+/"," ",$value);
				$conditions=explode(" ",$value);
			   	for($i=0;$i<count($conditions);$i++){
					$operation=preg_replace("/[0-9]+.*/","",$conditions[$i]);
					$number=preg_replace("/^[^0-9]*/","",$conditions[$i]);
        	                        $arr->filter($this->filters[$key]['field'],$operation,$number);
					}
				break;

		        case "text":
                                $arr->filter($this->filters[$key]['field'],'like',$value);
				break;

		        case "date":
				$value=strtotime($value);
                                $arr->filter($this->filters[$key]['field'],'>=',$value);
                                $arr->filter($this->filters[$key]['field'],'<',$value+3600*24);
				break;

		        case "period":
				$value_from=strtotime($value['from']);
				$value_to=strtotime($value['to']);

				if($value_from || $value_to)
	                                $arr->filter($this->filters[$key]['field'],'>',0);

				if($value_from)
	                                $arr->filter($this->filters[$key]['field'],'>=',$value_from);
        
				if($value_to)
	                        	$arr->filter($this->filters[$key]['field'],'<',$value_to+3600*24);
				break;

			}
		}

        return $arr;
    }


    public function ParseRow($data,$template,$rownumber){

	$template=preg_replace("/\{data\[([^'\]]+)\]/","{data['\\1']",$template);
	$row=$template;
	$row=preg_replace("/\{rownumber\}/",$rownumber,$row);
	$mas=explode("{data['",$template);
	$vars=[];

	for($i=1;$i<count($mas);$i++){
		$vars[]=preg_replace("/'.*$/","",preg_replace("/[\n\r]+/","",$mas[$i]));
		}
	for($i=0;$i<count($vars);$i++){
		$row=preg_replace("/\{data\['".$vars[$i]."'\]\}/",$data[$vars[$i]],$row);
		$row=preg_replace("/\{data\[".$vars[$i]."\]\}/",$data[$vars[$i]],$row);
		}
	return $row;	
    }

}