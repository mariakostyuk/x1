<?php
namespace common\helpers;

class Arr
{
	public $data=[];

	public function Cut($start,$count){
		$this->data=array_slice($this->data, $start, $count); 
	}

	public function Data(){
		return $this->data;
	}

	public function Count(){
		return count($this->data);
	}


	public function sort($field,$way,$type){

	$way=(($way=="desc")?SORT_DESC:SORT_ASC);
	switch($type){
	        case "number":
			$type=SORT_NUMERIC;
			break;
	        case "text":
			$type=SORT_STRING;
			break;
		}

	$coloumn=array_column($this->data, $field);
	foreach($coloumn as $key=>$value){
		$coloumn[$key]=mb_strtolower($coloumn[$key]);
		}
	array_multisort($coloumn, $way, $type, $this->data);

	}	

	public function Sums(){

		$sum=[];
		for($i=0;$i<count($this->data);$i++)
		  foreach($this->data[$i] as $field => $value){
			$v=preg_replace("/[^\-0-9\.]+/","",$value);
                        @$sum[$field]+=$v;
			}
		return $sum;

	}


	public function Filter($field,$operation,$value){

		$data=[];
		for($i=0;$i<count($this->data);$i++){
			if(!isset($this->data[$i][$field]))
				$this->data[$i][$field]="";
		        switch($operation){
				case "":
				case "=":
					if($this->data[$i][$field]==$value)
						$data[]=$this->data[$i];
			                break;
				case "<":
					if($this->data[$i][$field]<$value)
						$data[]=$this->data[$i];
			                break;
				case ">":
					if($this->data[$i][$field]>$value)
						$data[]=$this->data[$i];
			                break;
				case ">=":
					if($this->data[$i][$field]>=$value)
						$data[]=$this->data[$i];
			                break;
				case "<=":
					if($this->data[$i][$field]<=$value)
						$data[]=$this->data[$i];
			                break;
				case "<>":
					if($this->data[$i][$field]!=$value)
						$data[]=$this->data[$i];
			                break;
				case "like":
					$v=preg_quote($value);
					$v=str_replace("_",".",$v);
					if(preg_match("/".mb_strtoupper($v)."/i",mb_strtoupper($this->data[$i][$field])))
						$data[]=$this->data[$i];
			                break;
				default:
					$data[]=$this->data[$i];
				}
			}
		$this->data=$data;
	}

}
