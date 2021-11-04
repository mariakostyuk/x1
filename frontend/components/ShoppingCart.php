<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Json;
use common\models\Query;
use frontend\components\MyCity;

class ShoppingCart extends Component {

    public $tablename;
    protected $cart;

    public function isEmpty(){
	$this->getCart();
	if(count($this->cart))
		return false;
	else
		return true;
    }

    public function readCart(){
	$cart = Yii::$app->getRequest()->getCookies()->getValue('ShoppingCartItems');
	if(!$this->cart){
		if($cart)
			$this->cart=Json::decode($cart);
		else
			$this->cart=[];
		}
	}

    public function getCart(){
	if(is_array($this->cart))
		return true;
	$this->readCart();

	$discounts=Yii::$app->getRequest()->getCookies()->getValue('ShoppingCartDiscount');
	$discounts=Json::decode($discounts);

	$myCity=new MyCity();
	$cityId=$myCity->getCityId();

	$cart=[];
	if($this->cart){

		$allproducts=[];
		foreach($this->cart as $pId=>$products){
			if(!$products)
				continue;
			foreach($products as $id=>$number){
				$p=$this->tablename::findOne($id);
	        		$cart[$pId]['products'][$id]=$p;
	        		$cart[$pId]['numbers'][$id]=$number;
				$allproducts[$id]=$p;
				}
			$cart[$pId]['discount']=((isset($discounts[$pId]))?$discounts[$pId]:0);
			}

		if(count($allproducts)){
	        	$prices=Query::DrugsPrices($allproducts,$cityId);
			}


		foreach($this->cart as $pId=>$products){
			if(!$products)
				continue;
			$cost=0;
			foreach($products as $id=>$number)
			   if(isset($prices[$id])){
       				$cart[$pId]['prices'][$id]=$prices[$id]['pharmacies'][$pId]['price'];
       				$cart[$pId]['old_prices'][$id]=$prices[$id]['pharmacies'][$pId]['price_old'];
				if($cart[$pId]['old_prices'][$id]==0.00)
					$cart[$pId]['old_prices'][$id]=0;

       				$cart[$pId]['costs'][$id]=$cart[$pId]['prices'][$id]*$cart[$pId]['numbers'][$id];
				$cost+=$cart[$pId]['costs'][$id];
				}
			    else {
                                $this->update($allproducts[$id],0,$pId);
				}
			$cart[$pId]['cost']=$cost;
			$cart[$pId]['total']=$cost-$cart[$pId]['discount'];
			}


		}

	return $cart;		
    }

    public  function update($item,$number,$pharmacyId){

	$this->readCart();

	$this->cart[$pharmacyId][$item->id]=$number;
	if(!$number)
		unset($this->cart[$pharmacyId][$item->id]);

	foreach($this->cart as $pharmacyId=>$pharmacy)
		if(!$pharmacy)
			unset($this->cart[$pharmacyId]);

	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'ShoppingCartItems',
		'value' => Json::encode($this->cart),
		]));

	$this->checkDiscount($pharmacyId);
    }

    public  function clear(){

	$this->cart=[];

	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'ShoppingCartItems',
		'value' => Json::encode($this->cart),
		]));

	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'ShoppingCartDiscount',
		'value' => 0,
		]));
    }

    public  function clearPharmacy($pharmacyId){

	$this->getCart();
	unset($this->cart[$pharmacyId]);

	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'ShoppingCartItems',
		'value' => Json::encode($this->cart),
		]));

	$this->checkDiscount($pharmacyId);
    }

    public function checkDiscount($pharmacyId){
	$cart=$this->getCart();
	$cost=$cart[$pharmacyId]['cost'];
	$discount=$cart[$pharmacyId]['discount'];
	if($discount>$cost){
		$discount=$cost;
		$this->setDiscount($discount,$pharmacyId);
		}
    }

    public function setDiscount($value,$pharmacyId){
	$discount=Yii::$app->getRequest()->getCookies()->getValue('ShoppingCartDiscount');
	$discount=Json::decode($discount);
        $discount[$pharmacyId]=$value;
	\Yii::$app->getResponse()->getCookies()->add(new \yii\web\Cookie([
		'name' => 'ShoppingCartDiscount',
		'value' => Json::encode($discount),
		]));
    }

    public function getCount(){
	$this->readCart();
	$count=0;
	foreach($this->cart as $pharmacyId=>$cart){
		foreach($cart as $id=>$number){
			$count++;
			}
		}
	return $count;
    }
}
?>