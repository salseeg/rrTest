<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 03.04.16
 * Time: 12:14
 */

namespace App\Domain;


class Icao
{
    protected $code;

    /**
     * Icao constructor.
     * @param $code
     */
    protected function __construct($code)
    {
        $this->code = $code;
    }

    function __toString()
    {
        return $this->code;
    }

    /**
     * @param $code
     * @return bool
     */
    public static function isValid($code){
        return preg_match('/^[A-Z]{4}$/', $code);
    }

    /**
     * @param $str
     * @return Icao
     * @throws \UnexpectedValueException
     */
    public static function fromString($str){
        $str = trim($str);
        if (self::isValid($str)){
            return new self($str);
        }
        throw new \UnexpectedValueException('Not valid ICAO code : '.$str);
    }


}