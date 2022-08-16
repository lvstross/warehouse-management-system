<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
    * Double Decode Decoded JSON Data For PDF Class
    *
    * @param $val  Double Encoded String
    * @return Double Decoded String
    */
    public function doubleDecode($val){
        while(gettype($val) == 'string'){
            $val = json_decode($val, true);
        }
        return $val;
    }

    /**
    * Invert a date string
    *
    * @param $date | String
    * @return String
    */
    public function invertDate($date)
    {
        $dateArr = explode('-', $date);
        $newDate = $dateArr[1].'-'.$dateArr[2].'-'.$dateArr[0];
        return $newDate;
    }

    /**
     * Add decimal X2 to value
     *
     * @param $num | Integer
     * @return String
     */
    public function addDecimal($num)
    {
        if(strpos($num, '.') == false){
            $num = strval(number_format($num,2));
            while(strpos($num,',') != false){
                $num = substr_replace($num, '', strpos($num, ','), 1);
            }
            return $num;
        }else{
            return $num;
        }
    }

    /**
    * Check to see if the year is formated wrong with leading '00'
    *
    * @param $d | String
    * @return string
    */
    public function correctYearFormat($d){
        $date = explode("-", $d);
        $str = substr($date[0],0,2);
        $last2digits = substr($date[0],2);
        $year = substr(date('Y'),0,2);
        if($str == '00'){
            return $year.$last2digits.'-'.$date[1].'-'.$date[2]; 
        }else{
            return $d;
        }
    }
}
