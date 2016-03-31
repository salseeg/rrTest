<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 30.03.16
 * Time: 21:39
 */

namespace App\Domain;


class StringHelper
{
    /**
     * Split $str into chunks of variable length specified in $length array and $str tail if  left
     * Not integers and negative values get filtered out of $length array
     *  
     * @param string $str
     * @param int[] $length
     * @return string[]
     */
    static function splitByLength($str, array $length){
        $result = [];
//        $length = array_filter($length, function($x){return $x >= 0 and is_int($x) ;});
       
        foreach ($length as $len){
            if (!is_int($len) or $len < 0) {
                continue;
            }
            $result[] = substr($str, 0, $len) ?: '';
            $str = substr($str, $len);
        }
       
        if ($str){
            $result[] = $str;
        }
       
        return $result;
    }

    static function isPositive($x){
        return is_int($x) and $x >= 0;
    }
    
    static function splitMultiple($str, array $length){
        $result = [];
        $strLen = strlen($str);
        $pos = 0;

        foreach ($length as $len){
            if ($len >= 0 and is_int($len)){
                $result[] = substr($str, $pos, $len) ?: '';
                $pos += $len;
            }
        }

        if (
            $tailLength = $strLen  - $pos 
            and $tailLength > 0
        ){
            $result[] = substr($str, $pos, $tailLength);
        }

        return $result;
    }

}