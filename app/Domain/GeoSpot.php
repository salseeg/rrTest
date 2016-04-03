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

    /** @var GeoLatitude  degrees */
    public $latitude;
    
    /** @var GeoLongitude degrees  */
    public $longitude;
    
    /** @var GeoRadius meters  */
    public $radius;

    public function __construct($rawString)
    {
        $this->rawString = $rawString;
        $this->radius = GeoRadius::fromNotamString('000');
        $this->latitude = GeoLatitude::fromNumericValue(0);
        $this->longitude = GeoLongitude::fromNumericValue(0);
        
        $this->initStructure();
    }

    protected function initStructure(){
        $raw = $this->rawString;
        $rawLength = strlen($raw);
        switch ($rawLength) {
            case 14: // w/ radius
                list($raw, $radius)  = StringHelper::splitMultiple($raw, [11, 3]);
                $this->radius = GeoRadius::fromNotamString($radius);
            case 11: // w/o radius
                list( $latitude, $longitude) = StringHelper::splitMultiple($raw, [5, 6]);
                $this->latitude = GeoLatitude::fromNotamString($latitude);
                $this->longitude = GeoLongitude::fromNotamString($longitude);
        }
    }
    
    function getNotamString(){
        return
            $this->latitude->getNotamString()
            . $this->longitude->getNotamString()
            . $this->radius->getNotamString()
        ;
    }

    public function __toString()
    {
        return $this->rawString;
    }

    /**
     * for json output
     * @return array
     */
    public function asArray(){
        return [
            'latitude' => floatval((string) $this->latitude),
            'longitude' => floatval((string) $this->longitude),
            'radius' => intval((string) $this->radius),
        ];
    }
    
    
}