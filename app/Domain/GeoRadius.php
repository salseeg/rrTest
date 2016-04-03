<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 03.04.16
 * Time: 9:34
 */

namespace App\Domain;


class GeoRadius
{
    const NM_TO_METER = 1855.3248;
    
    /** @var float in meters */
    protected $radius = 0.0;

    /**
     * GeoRadius constructor.
     * @param float $radius
     */
    protected function __construct($radius)
    {
        $this->radius = $radius;
    }

    /**
     * @return float
     */
    public function getRadius()
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;
    }
    
    static public function fromNotamString($str){
        $value = intval($str);
        return new self(round($value * self::NM_TO_METER, 0, PHP_ROUND_HALF_DOWN));    
    }
    
    public function getNotamString(){
        return $this->radius 
            ? str_pad(
                round($this->radius / self::NM_TO_METER, 0, PHP_ROUND_HALF_DOWN),
                3,
                '0',
            STR_PAD_LEFT
            )
            : ''
        ;
    }

    function __toString()
    {
        return (string) $this->radius;
    }


}