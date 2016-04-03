<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 02.04.16
 * Time: 9:34
 */

namespace App\Domain;


class GeoLatitude
{
    const DIRECTION_SOUTH = 'S';
    const DIRECTION_NORTH = 'N';
    
    const KNOWN_DIRECTIONS = [
        self::DIRECTION_NORTH,
        self::DIRECTION_SOUTH,
    ];
    
    /**
     * @var float
     */
    protected $latitude = 0.0;

    /**
     * GeoLatitude constructor.
     * @param float $latitude
     */
    protected function __construct($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    public function getNotamString(){
        $degrees = $this->latitude;
        $direction  = $degrees < 0 ? self::DIRECTION_SOUTH : self::DIRECTION_NORTH;
        $degrees = abs($degrees);
        $minutes = floor( ($degrees - floor($degrees)) * 60 );
        $degrees = floor( $degrees );

        return
            str_pad($degrees, 2, '0', STR_PAD_LEFT)
            . str_pad($minutes, 2, '0', STR_PAD_LEFT)
            . $direction
        ;
    }

    /**
     * @param $str
     * @return GeoLatitude
     */
    public static function fromNotamString($str){
        list($degrees, $minutes, $direction) = StringHelper::splitMultiple($str, [2, 2, 1]);
        $degrees = filter_var($degrees, FILTER_VALIDATE_INT);
        $minutes = filter_var($minutes, FILTER_VALIDATE_INT);
        $minutes = min($minutes , 59);
        $degrees += $minutes / 60.0;
        $degrees = min($degrees, 90);
        if (in_array($direction, self::KNOWN_DIRECTIONS) and $direction == self::DIRECTION_SOUTH){
            $degrees *= -1;    
        }
        
        return new self($degrees);
    }

    /**
     * @param $value
     * @return GeoLatitude
     */
    public static function fromNumericValue($value){
        return new self($value);
    }

    function __toString()
    {
        return (string) round($this->latitude, 6, PHP_ROUND_HALF_DOWN);
    }


}