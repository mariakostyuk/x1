<?php
namespace common\helpers;

use Yii;

class CurrencyHelper
{

	public function Amount($amount, $currency){

	$number=floor($amount);
	$number2=round(($amount-floor($amount))*100);
	
	static $dic = array(
		array(
			-2	=> 'две',
			-1	=> 'одна',
			1	=> 'один',
			2	=> 'два',
			3	=> 'три',
			4	=> 'четыре',
			5	=> 'пять',
			6	=> 'шесть',
			7	=> 'семь',
			8	=> 'восемь',
			9	=> 'девять',
			10	=> 'десять',
			11	=> 'одиннадцать',
			12	=> 'двенадцать',
			13	=> 'тринадцать',
			14	=> 'четырнадцать' ,
			15	=> 'пятнадцать',
			16	=> 'шестнадцать',
			17	=> 'семнадцать',
			18	=> 'восемнадцать',
			19	=> 'девятнадцать',
			20	=> 'двадцать',
			30	=> 'тридцать',
			40	=> 'сорок',
			50	=> 'пятьдесят',
			60	=> 'шестьдесят',
			70	=> 'семьдесят',
			80	=> 'восемьдесят',
			90	=> 'девяносто',
			100	=> 'сто',
			200	=> 'двести',
			300	=> 'триста',
			400	=> 'четыреста',
			500	=> 'пятьсот',
			600	=> 'шестьсот',
			700	=> 'семьсот',
			800	=> 'восемьсот',
			900	=> 'девятьсот'
		),

		array(
			array(
				array('копейка', 'копейки', 'копеек'),
				array('рубль', 'рубля', 'рублей'),
				),
			array('тысяча', 'тысячи', 'тысяч'),
			array('миллион', 'миллиона', 'миллионов'),
			array('миллиард', 'миллиарда', 'миллиардов'),
			array('триллион', 'триллиона', 'триллионов'),
			array('квадриллион', 'квадриллиона', 'квадриллионов'),
		),
		
		array(
			2, 0, 1, 1, 1, 2
		)
		);
	
		$string = array();
	
		$number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
		$number2 = str_pad($number2, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);
		$allparts[0] = array_reverse(str_split($number2,3));
		$allparts[1] = array_reverse(str_split($number,3));

		foreach($allparts as $i1=>$parts)
		foreach($parts as $i=>$part) {
		   if($part>0) {
			$digits = array();
			if($part>99) {
				$digits[] = floor($part/100)*100;
			}
			if($mod1=$part%100) {
				$mod2 = $part%10;
				$flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 && $mod2>0 ? -1 : 1;
				if($mod1<20 || !$mod2) {
					$digits[] = $flag*$mod1;
				} else {
					$digits[] = floor($mod1/10)*10;
					$digits[] = $flag*$mod2;
				}
			}
			$last = abs(end($digits));
			foreach($digits as $j=>$digit) {
				$digits[$j] = $dic[0][$digit];
			}

			if($i>0)
				$digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
			else
				$digits[] = $dic[1][$i][$i1][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];
			array_unshift($string, join(' ', $digits));
		}
	   }

	$string=join(' ', $string);
	if(!ceil($number2))
		$string.=" 00 копеек";
	if(!ceil($number))
		$string="ноль рублей ".$string;
	return $string;
	}

}
?>