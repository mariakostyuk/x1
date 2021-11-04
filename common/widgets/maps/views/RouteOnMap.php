<div id="map" style="width:<?=$width;?>;height:<?=$height;?>"></div>
<?php
$gps=[];
for($i=0;$i<count($coords);$i++)
	$gps[$i]=join(",",$coords[$i]);
$gps=join(";",$gps);

$script="
var myMap, MyCoords=[55.755814, 37.617635], MyCity=\"\";

function UpdateMap(stops){

if(stops.length>0)
    stops=stops.split(\";\");
else
    stops=[];

ymaps.ready(init);
if(myMap!=null)
	myMap.geoObjects.removeAll()


function init() {
    var v='';
    if($('#".$MyCitySelectId."').val()!=undefined)
        v=$('#".$MyCitySelectId."').val();
    
    if(v.length>0){
        show(false);
        }
    else {
        var geolocation = ymaps.geolocation.get({provider: 'yandex'}).then(function (res) {
            show(res);
            });
        }
}

function show(res) {
    if(res){
        MyCoords = res.geoObjects.get(0).geometry.getCoordinates();
        MyCity=res.geoObjects.get(0).getLocalities()[0];

        var ex;
        $('#".$MyCitySelectId." > option').each(function() {
            ex = new RegExp('^'+MyCity+' \\(','i');
            if(ex.test($(this).text())){
                $('#".$MyCitySelectId."').val($(this).val()).trigger('change');
                return true;
                }
            });
        }
    
    if(myMap==null)
        myMap = new ymaps.Map('map', {
            center: MyCoords,
            zoom: 10
        }, {
            searchControlProvider: 'yandex#search'
        });

    var multiRoute = new ymaps.multiRouter.MultiRoute({   
    referencePoints: stops
	}, {
	      boundsAutoApply: true
	});

    myMap.geoObjects.add(multiRoute);

}
}

UpdateMap('".$gps."');";
$this->registerJs($script);
$this->registerJsFile("https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=".$YandexApiKey);
?>