<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 03.04.16
 * Time: 12:27
 */

namespace App\Domain;



class IcaoCollection implements \IteratorAggregate
{
    protected $collection = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }
    
    public function addStrings($values){
        if (!is_array($values)){
            $values = [$values];
        }
        
        foreach ($values as $code){
            $this->collection[] = Icao::fromString($code);
        }
    }
    
    public function isEmpty(){
        return empty($this->collection);
    }
}