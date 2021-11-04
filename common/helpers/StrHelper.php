<?php
namespace common\helpers;

use Yii;

class StrHelper
{

	public function Upper($str){
		mb_internal_encoding("UTF-8");
		return mb_strtoupper($str);
	}

	public function UpperFirst($str){
		mb_internal_encoding("UTF-8");
		$res=mb_strtoupper(mb_substr($str,0,1)).mb_substr($str,1);
		return $res;
	}

	public function Slug($str){
        $cyr  = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у', 
            'ф','х','ц','ч','ш','щ','ъ', 'ы','ь', 'э', 'ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
            'Ф','Х','Ц','Ч','Ш','Щ','Ъ', 'Ы','Ь', 'Э', 'Ю','Я', ' ');
        $lat = array( 'a','b','v','g','d','e','e','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
            'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'i', 'y', 'y', 'e' ,'yu' ,'ya','A','B','V','G','D','E','E','Zh',
            'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
            'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'I' ,'Y' ,'Y', 'E', 'Yu' ,'Ya', '-');
	    
	    $text = str_replace($cyr, $lat, $str);
	    $text = preg_replace("/[^a-zA-Z\-_]/", "", $text);
        return $text;
	}
}
?>