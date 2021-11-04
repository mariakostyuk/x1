<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use common\models\User;
use common\models\Orders;
use common\models\Config;
use common\models\CashBack;

class TableItems extends Component {


    public  function CustomerCashback($user){

	// SORT BY DATE BY DEFAULT
	if(!isset($_GET['sortBy'])){
		$_GET['sortBy']="date";
		$_GET['sortWay']="desc";
		}

	$MAXDATE=99999999999;
	$cashback_paid_date_from=0;
	if(isset($_GET['filter']['cashback_paid_date']['from']))
		$cashback_paid_date_from=strtotime($_GET['filter']['cashback_paid_date']['from']);
	$cashback_paid_date_to=$MAXDATE;
	if(isset($_GET['filter']['cashback_paid_date']['to']) && $_GET['filter']['cashback_paid_date']['to']>0)
		$cashback_paid_date_to=strtotime($_GET['filter']['cashback_paid_date']['to']." 23:59:59");
	$cashback_future_date_from=0;
	if(isset($_GET['filter']['cashback_future_date']['from']))
		$cashback_future_date_from=strtotime($_GET['filter']['cashback_future_date']['from']);
	$cashback_future_date_to=$MAXDATE;
	if(isset($_GET['filter']['cashback_future_date']['to'])  && $_GET['filter']['cashback_future_date']['to']>0)
		$cashback_future_date_to=strtotime($_GET['filter']['cashback_future_date']['to']." 23:59:59");
	
	$items=[];
	$orders=Orders::find()->where(['id_user'=>$user->id,'status'=>4])->orderBy('date DESC')->all();
	$i=0;
	for($i1=0;$i1<count($orders);$i1++){

		// FOR FILTER WORKS EVERYTIME - SHOWS EVERYTHING
		if($cashback_paid_date_from)
	        	$items[$i]['cashback_paid_date']=$cashback_paid_date_from;
		if($cashback_paid_date_to)
	        	$items[$i]['cashback_paid_date']=$cashback_paid_date_to;
		if($cashback_future_date_from)
	        	$items[$i]['cashback_future_date']=$cashback_future_date_from;
		if($cashback_future_date_to)
	        	$items[$i]['cashback_future_date']=$cashback_future_date_to;

	        $items[$i]['reciept']=$orders[$i1]->reciept;
	        $items[$i]['reciept_cell']="<a href=/stat/customer-purchases?id=".$orders[$i]->id.">".$orders[$i]->reciept."</a>";
	        $items[$i]['date']=$orders[$i1]->date;
	        $items[$i]['date_cell']=date("d.m.Y",$orders[$i1]->date);
	        $items[$i]['pharmacy']=(($orders[$i1]->pharmacy)?$orders[$i1]->pharmacy->name.", ".(($orders[$i1]->pharmacy->city)?"г.".$orders[$i1]->pharmacy->city->name.", ":"").$orders[$i1]->pharmacy->address:"");
	        $items[$i]['id_pharmacy']=$orders[$i1]->id_pharmacy;
	        $items[$i]['order_type']=$orders[$i1]->order_type;
	        $items[$i]['order_type_cell']=Orders::OrderTypeList()[$orders[$i1]->order_type];
	        $items[$i]['total']=number_format($orders[$i1]->cost+$orders[$i1]->discount,2);
	        $items[$i]['cost']=number_format($orders[$i1]->cost,2);

		$cbinfo=$orders[$i1]->CashbackInfo();

	        $items[$i]['cashback']=(($orders[$i1]->cashback)?-$orders[$i1]->cashback->points:0);
	        $items[$i]['cashback_customer_percent']=$cbinfo[1]['percent'];
	        $items[$i]['cashback_customer_points']=$cbinfo[1]['points'];

		$cb=$orders[$i1]->CashbackEarned($user);
		$cashback_paid=[];
		$cashback_future=[];

		$cashback_paid_total=0;
		$cashback_future_total=0;

		$cashback_paid_dates=[];
		$cashback_future_dates=[];

		for($j=0;$j<count($cb);$j++){
			$tr="<tr><td>".date("d.m.Y",$cb[$j]->date)."</td><td>".$cb[$j]->points."</td></tr>";
			if($cb[$j]->date<time()){
				$cashback_paid[]=$tr;
				$cashback_paid_dates[]=$cb[$j]->date;
				$cashback_paid_total+=$cb[$j]->points;
				}
			else	{
				$cashback_future[]=$tr;
				$cashback_future_dates[]=$cb[$j]->date;
				$cashback_future_total+=$cb[$j]->points;
				}
			}
		$items[$i]['cashback_paid']=((count($cashback_paid))?"<table class=\"table table-striped\" width=100%>".join("",$cashback_paid)."</table>":"");
		$items[$i]['cashback_future']=((count($cashback_future))?"<table class=\"table table-striped\" width=100%>".join("",$cashback_future)."</table>":"");

//		$cashback_paid_total-=$items[$i]['cashback'];
		$items[$i]['cashback_paid_total']=$cashback_paid_total;
		$items[$i]['cashback_future_total']=$cashback_future_total;

		$flag=false;
		for($j=0;$j<count($cashback_paid_dates);$j++){
			if(($cashback_paid_date_from<=$cashback_paid_dates[$j]) && ($cashback_paid_date_to>=$cashback_paid_dates[$j]))
				$flag=true;
			}
		if((!$flag && count($cashback_paid_dates)) || (($cashback_paid_date_from || ($cashback_paid_date_to<$MAXDATE)) && !count($cashback_paid_dates))){
			unset($items[$i]);
		        continue;
			}

		$flag=false;
		for($j=0;$j<count($cashback_future_dates);$j++){
			if(($cashback_future_date_from<=$cashback_future_dates[$j]) && ($cashback_future_date_to>=$cashback_future_dates[$j]))
				$flag=true;
			}
		if((!$flag && count($cashback_future_dates)) || (($cashback_future_date_from || ($cashback_future_date_to<$MAXDATE)) && !count($cashback_future_dates))){
			unset($items[$i]);
		        continue;
			}
		$items[$i]['text']="";
		$i++;
		}
	
	// CASHBACK FOR CARD ACTIVATION
	$cashback=$user->MyDefaultCashback();
	if($cashback){
	        $items[$i]['TableRow']='row1';
	        $items[$i]['date']=$items[$i]['cashback_paid_date']=$cashback->date;
	        $items[$i]['date_cell']=date("d.m.Y",$cashback->date);

		$items[$i]['cashback_paid']="<table class=\"table table-striped\" width=100%><tr><td>".date("d.m.Y",$cashback->date)."</td><td>".$cashback->points."</td></tr></table>";

		$DefaultCashBackCanUseFrom=Config::ValueOf('DefaultCashBackCanUseFrom');
		$items[$i]['cashback_paid_total']=$cashback->points;
	        $items[$i]['text']='Бонусные баллы за регистрацию в системе';


		$i++;
		}

	// CASHBACK FOR PHARMACIES REGISTRATION
	$cashbacks=CashBack::find()->where("id_owner=".$user->id." AND type=2")->all();
	for($j=0;$j<count($cashbacks);$j++){
		$prc=$user->PharmacyRegistrationCashback($cashbacks[$j]->id_pharmacy);
	        $items[$i]['TableRow']='row1';
	        $items[$i]['date']=$items[$i]['cashback_paid_date']=$cashbacks[$j]->date;
	        $items[$i]['date_cell']=date("d.m.Y",$cashbacks[$j]->date);

		$items[$i]['cashback_paid']="<table class=\"table table-striped\" width=100%><tr><td>".date("d.m.Y",$cashbacks[$j]->date)."</td><td>".$cashbacks[$j]->points."</td></tr></table>";

		$items[$i]['cashback_paid_total']=$prc['total'];
	        $items[$i]['text']='За регистрацию аптеки <strong>'.$cashbacks[$j]->pharmacy->name."</strong>";
		$i++;
		}

	return $items;
	}
}