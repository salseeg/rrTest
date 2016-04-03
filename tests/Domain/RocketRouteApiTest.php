<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 18.03.16
 * Time: 22:56
 */

namespace tests\Domain;


use App\Domain\IcaoCollection;
use App\Domain\RocketRouteApi;
use App\Domain\RocketRouteException;

class RocketRouteApiTest extends \PHPUnit_Framework_TestCase
{

    function testCreation(){
        $caughtException = false;
        $api = new RocketRouteApi();
        $icao = new IcaoCollection();
        $icao->addStrings('EGHH');

        try{
            $response  = $api->getNotam($icao);
        }catch (RocketRouteException $e){
            $caughtException = true;
        }
        $this->assertFalse($caughtException);

    }
    function testWrongPassword(){
        $caughtException = false;

        $api = new RocketRouteApi();
        $api->setPassword('pp');
        $icao = new IcaoCollection();
        $icao->addStrings('EGHH');


        try{

            $response  = $api->getNotam($icao);

        }catch (RocketRouteException $e){
            $this->assertEquals(8, $e->getCode());
            $caughtException = true;
        }
        $this->assertTrue($caughtException);

    }

    function testManyCodes(){
        $api = new RocketRouteApi();
        $icao = new IcaoCollection();
        $icao->addStrings(['EGKA' , 'EGHH']);

        $response = $api->getNotam($icao)->asArray();
        
        $this->assertEquals(2, count($response));
    }

}
