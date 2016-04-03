<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 02.04.16
 * Time: 10:18
 */

namespace App\Domain;


class GeoLongitude
{
    const DIRECTION_EAST = 'E';
    const DIRECTION_WEST = 'W';

    const KNOWN_DIRECTIONS = [
        self::DIRECTION_EAST,
        self::DIRECTION_WEST,
    ];
    
    /** @var float  */
    protected $longitude = 0.0;

    /**
     * GeoLongitude constructor.
     * @param float $longitude
     */
    public function __construct($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
    
    public static function fromNumericValue($value){
        return new self($value);
    }
    
    public static function fromNotamString($str){
        list($degrees, $minutes, $direction) = StringHelper::splitMultiple($str, [3, 2, 1]);
        $degrees = intval($degrees);
        $minutes = intval($minutes);
        $minutes = min($minutes , 59);
        $degrees += $minutes / 60.0;
        $degrees = min($degrees, 180);
        if (in_array($direction, self::KNOWN_DIRECTIONS) and $direction == self::DIRECTION_WEST){
            $degrees *= -1;
        }

        return new self($degrees);
    }
    
    public function getNotamString(){
        $degrees = $this->longitude;
        $direction  = $degrees < 0 ? self::DIRECTION_WEST : self::DIRECTION_EAST;
        $degrees = abs($degrees);
        $minutes = floor( ($degrees - floor($degrees)) * 60 );
        $degrees = floor( $degrees );

        return
            str_pad($degrees, 3, '0', STR_PAD_LEFT)
            . str_pad($minutes, 2, '0', STR_PAD_LEFT)
            . $direction
        ;
    }
    
    function __toString()
    {
        return (string) round($this->longitude, 6, PHP_ROUND_HALF_DOWN);
    }
    
}