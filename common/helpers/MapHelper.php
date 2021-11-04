<?php
namespace common\helpers;

use Yii;
use yii\helpers\Json;

class MapHelper
{
    public function DistanceAndDuration($stops, $YandexRoutingApiKey){
	$s=$stops;
	$stops=[];
	for($i=0;$i<count($s);$i++)
		$stops[$i]=join(",",$s[$i]);
        $stops=join("|",$stops);
        $url="https://api.routing.yandex.net/v1.0.0/route?waypoints=".$stops."&apikey=".$YandexRoutingApiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        $response = curl_exec($ch);
        curl_close($ch);

        $response=Json::decode($response);

        $info=[];
        for($i=0;$i<count($response['route']['legs']);$i++){
            $info[$i]['distance']=0;
            $info[$i]['duration']=0;
            for($j=0;$j<count($response['route']['legs'][$i]['steps']);$j++){
                $stop=$response['route']['legs'][$i]['steps'][$j];
                $info[$i]['duration']+=$stop['duration'];
                $info[$i]['distance']+=$stop['length'];
                }
            $info[$i]['duration']=ceil($info[$i]['duration']/60);
            $info[$i]['distance']=ceil($info[$i]['distance']/1000);
            }
        return $info;
    }
}
?>