<?php
namespace app\components;
use app\models\Modules;
class Helper 
{
public static function amount_to_money($amount)
{
//$amount=ceil($amount);
$s=explode('.',$amount);
$amount = Helper::moneyFormatIndia($s[0]);
if(isset($s[1]) && $s[1] !='')
{
	return ''.$amount.".".$s[1]."";
	//return number_format((float)$amount, 2, '.', ''); 
}
else
return ''.$amount.".00";

//return $amount;
}

public static function moneyFormatIndia($num){
    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}

public static function decimal_to_words($x)
{
                   $x = ceil($x);
	$x = str_replace(',','',$x);
	$pos = strpos((string)$x, ".");
	if ($pos !== false) { $decimalpart= substr($x, $pos+1, 2); $x = substr($x,0,$pos); }
	$tmp_str_rtn = Helper::number_to_words ($x);
	//if(!empty($decimalpart) && $decimalpart !='00')
		//$tmp_str_rtn .= ' rupees and '  . Helper::number_to_words ($decimalpart) . ' paise';
	$tmp_str_rtn .= " ONLY";	
	return   strtoupper($tmp_str_rtn);
} 

public static function number_to_words ($x)
{
     $x = ceil($x);
    $nwords = array(  "zero", "one", "two", "three", "four", "five", "six", 
	      	  "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", 
	      	  "fourteen", "fifteen", "sixteen", "seventeen", "eightteen", 
	     	  "nineteen", "twenty", 30 => "thirty", 40 => "fourty",
                     50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eigthy",
                     90 => "ninety" );

     if(!is_numeric($x))
     {
         $w = '#';
     }else if(fmod($x, 1) != 0)
     {
         $w = '#';
     }else{
         if($x < 0)
         {
             $w = 'minus ';
             $x = -$x;
         }else{
             $w = '';
         }
         if($x < 21)
         {
             $w .= $nwords[$x];
         }else if($x < 100)
         {
             $w .= $nwords[10 * floor($x/10)];
             $r = fmod($x, 10);
             if($r > 0)
             {
                 $w .= ' '. $nwords[$r];
             }
         } else if($x < 1000)
         {
		
             $w .= $nwords[floor($x/100)] .' hundred';
             $r = fmod($x, 100);
             if($r > 0)
             {
                 $w .= ' '. Helper::number_to_words($r);
             }
         } else if($x < 100000)
         {
         	$w .= Helper::number_to_words(floor($x/1000)) .' thousand';
             $r = fmod($x, 1000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $w .= ' ';
                 }
                 $w .= Helper::number_to_words($r);
             }
         } else {
             $w .= Helper::number_to_words(floor($x/100000)) .' lakhs';
             $r = fmod($x, 100000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $word .= ' ';
                 }
                 $w .= Helper::number_to_words($r);
             }
         }
     }
     return $w;
}
public static function modules_status($module_name)
{
	$module = Modules::find()->where(['moduleName'=>$module_name,'status'=>1])->one();
	if(!empty($module))
		return true;
	else
		return false;
}
	
}
