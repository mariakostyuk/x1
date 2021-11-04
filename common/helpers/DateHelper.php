<?php
namespace common\helpers;

use Yii;

class DateHelper
{
	public function Months(){

		$months=[
			"Январь",
			"Февраль",
			"Март",
			"Апрель",
			"Май",
			"Июнь",
			"Июль",
			"Август",
			"Сентябрь",
			"Октябрь",
			"Ноябрь",
			"Декабрь"
			];

		return $months;
	}

	public function OfDate($date){
	
		$months=[
			"января",
			"февраля",
			"марта",
			"апреля",
			"мая",
			"июня",
			"июля",
			"августа",
			"сентября",
			"октября",
			"ноября",
			"декабря"
			];
		$date=explode(".",$date);

		return ceil($date[0])." ".$months[ceil($date[1])-1]." ".$date[2];
	}

	public function DayOfWeek($date)
	{
    	if(preg_match("/\./",$date))
	    	$date=strtotime($date);
	    $days=["Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Воскресенье"];
	    $w=date("N",$date)-1;
	    return $days[$w];
	}
	
	public function FirstDayOfMonth($date="",$asDate=true)
	{
	if(!$date)
		$date=time();
	if(!preg_match("/\./",$date))
		$date=date("d.m.Y",$date);
	$day=date("d",time());
	$month=date("m",time());
	$year=date("Y",time());
	$time=strtotime("01.".$month.".".$year);
	if($asDate)
		$time=date("d.m.Y",$time);
	return $time;
	}

	public function LastDayOfMonth($date="", $asDate=true)
	{
	if(!$date)
		$date=time();
	if(preg_match("/\./",$date))
		$date=strtotime($date);
	$m=date("m",$date);
	$y=date("Y",$date);
	$d=cal_days_in_month(CAL_GREGORIAN, $m, $y);
	$last=$d.".".$m.".".$y;
	if(!$asDate)
		$last=strtotime($last);
	return $last;
	}

	public function FirstDayOfNextMonth($date="",$asDate=true)
	{
	if(!$date)
		$date=time();
	$last=self::LastDayOfMonth($date);
	$next=strtotime($last)+3600*24;
	if($asDate)
		$next=date("d.m.Y",$next);
	return $next;
	}

	public function ToTime($time){
	$time=preg_split("/[^0-9]+/",trim($time));
	$time[0]=ceil($time[0]);
	if($time[0]<10)
		$time[0]="0".$time[0];
	$time=$time[0].":".$time[1];
	return $time;
	}

	public function TimeToStr($time){
	$hours=[];
	$hours[0]="часов";
	$hours[1]="час";
	$hours[2]="часа";
	$hours[3]="часа";
	$hours[4]="часа";
	$hours[5]="часов";
	$hours[6]="часов";
	$hours[7]="часов";
	$hours[8]="часов";
	$hours[9]="часов";
	$hours[10]="часов";
	$hours[11]="часов";
	$hours[12]="часов";
	$hours[13]="часов";
	$hours[14]="часов";
	$hours[15]="часов";
	$hours[16]="часов";
	$hours[17]="часов";
	$hours[18]="часов";
	$hours[19]="часов";
	$hours[20]="часов";
	$hours[21]="час";
	$hours[22]="часа";
	$hours[23]="часа";
	
	$time=explode(":",$time);
	if(count($time)>1){
	        $time=ceil($time[0])." ".$hours[ceil($time[0])]." ".$time[1]." минут";
		}
	return $time;
	}

}
?>