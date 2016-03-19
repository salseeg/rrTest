<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 9:00
 */

namespace App\Domain;


class GeoSpot
{
    protected $rawString;

    /** @var null|float  degrees */
    public $latitude = null;
    
    /** @var null|float degrees  */
    public $longitude = null;
    
    /** @var null|float meters  */
    public $radius = null;

    public function __construct($rawString)
    {
        $this->rawString = $rawString;

        $this->initStructure();
    }

    protected function initStructure(){
        if (preg_match('/^(?<latitude>\d{4}[NS])(?<longitude>\d{5}[EW])(?<radius>\d+)?$/', $this->rawString, $match)){
            if (array_key_exists('radius', $match) and $match['radius']){
                $this->radius = intval($match['radius']) * 1855.3248;
            }

            list($degree, $minutes, $direction) = self::splitByLength($match['latitude'], [2,2]);
            $this->latitude = self::geoToNumeric($direction, $degree, $minutes);

            list($degree, $minutes, $direction)  = self::splitByLength($match['longitude'], [3,2]);
            $this->longitude = self::geoToNumeric($direction, $degree, $minutes);
        }
    }

    protected static function splitByLength($str, array $length){
        $result = [];
        foreach ($length as $len){
            $result[] = substr($str, 0, $len);
            $str = substr($str, $len);
        }
        if ($str){
            $result[] = $str;
        }
        return $result;
    }

    protected static function geoToNumeric($direction, $degree, $minutes = 0, $seconds = 0){
        $number = intval($degree);
        $minutes = intval($minutes);
        $seconds = intval($seconds);

        if ($minutes){
            $number += $minutes / 60.0;
        }
        if ($seconds){
            $number += $seconds / 3600.0;
        }
        if (in_array($direction, ['W', 'S'])){
            $number *= -1;
        }
        return $number;
    }

}