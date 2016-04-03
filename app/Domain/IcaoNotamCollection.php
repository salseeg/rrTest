<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 03.04.16
 * Time: 10:54
 */

namespace App\Domain;

use App\Domain\Notam;

class IcaoNotamCollection implements \IteratorAggregate
{
    private $collection = [];

    public function getIterator()
    {
        return new \ArrayIterator($this->collection) ;
    }
    
    public function addNotam($icao, Notam $notam){
        $this->collection[$icao][] = $notam;
    }
    
    public function absorb(IcaoNotamCollection $other){
        $this->collection = array_merge($this->collection, $other->collection);    
    }
    
    
    public function  asArray(){
        return array_map(function($notams){
            return array_map(function(Notam $notam){
                    return $notam->asArray();
                },
                $notams
            );
        }, $this->collection);
    }
}