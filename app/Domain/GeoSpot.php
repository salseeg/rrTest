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

    /** @var GeoLatitude  degrees */
    public $latitude;
    
    /** @var GeoLongitude degrees  */
    public $longitude;
    
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
                list( $latitude, $longitude) = StringHelper::splitMultiple($raw, [5, 6]);
                $this->latitude = GeoLatitude::fromNotamString($latitude);
                $this->longitude = GeoLongitude::fromNotamString($longitude);
        }
    }

    function __toString()
    {
        return $this->rawString;
    }


}