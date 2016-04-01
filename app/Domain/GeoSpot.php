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
    const NM_TO_METER = 1855.3248;


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
        $raw = $this->rawString;
        $rawLength = strlen($raw);
        switch ($rawLength) {
            case 14: // w/ radius
                list($raw, $radius)  = StringHelper::splitMultiple($raw, [11, 3]);
                $this->radius = intval($radius) * self::NM_TO_METER;
            case 11: // w/o radius
                list(
                    $latitudeDegree,
                    $latitudeMinutes,
                    $latitudeDirection,
                    $longitudeDegree,
                    $longitudeMinutes,
                    $longitudeDirection
                ) = StringHelper::splitMultiple($raw, [2,2,1, 3,2,1]);
                if (
                    in_array($latitudeDirection, ['N', 'S']) 
                    and in_array($longitudeDirection, ['E', 'W'])
                ){
                    $this->latitude = self::geoToNumeric($latitudeDirection,
                        filter_var($latitudeDegree, FILTER_VALIDATE_INT),
                        filter_var($latitudeMinutes, FILTER_VALIDATE_INT)
                    );
                    $this->longitude = self::geoToNumeric($longitudeDirection, 
                        filter_var($longitudeDegree, FILTER_VALIDATE_INT), 
                        filter_var($longitudeMinutes, FILTER_VALIDATE_INT)
                    );
                }
        }
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

    function __toString()
    {
        return $this->rawString;
    }


}