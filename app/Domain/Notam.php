<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 0:07
 */

namespace App\Domain;


class Notam
{
    public $id;
    public $itemQ;
    public $itemA;
    public $itemB;
    public $itemC;
    public $itemD;
    public $itemE;

    public function __construct(\SimpleXMLElement $element){
        $this->id = (string) $element['id'];

        $this->itemA = (string) $element->ItemA;
        $this->itemB = (string) $element->ItemB;
        $this->itemC = (string) $element->ItemC;
        $this->itemD = (string) $element->ItemD;
        $this->itemE = (string) $element->ItemE;
        $this->itemQ = (string) $element->ItemQ;
    }

    /**
     * @return GeoSpot|null
     */
    public function getGeoSpot(){
        if ($pos = strrpos($this->itemQ, '/')){
            return new GeoSpot(substr($this->itemQ, $pos + 1));
        }
        return null;
    }
    
    public function getMessage(){
        return $this->itemE;
    }

    public function asArray(){
        $spot = $this->getGeoSpot();
        return [
            'id' => $this->id,
            'spot' => $spot ? $spot->asArray() : [],
            'message' => $this->getMessage(),
            'coords' => $spot? $spot->getNotamString() : '',
        ];
    }
}