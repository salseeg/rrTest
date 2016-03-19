<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 18.03.16
 * Time: 22:56
 */

namespace tests\Domain;


use App\Domain\RocketRouteApi;
use App\Domain\RocketRouteException;

class RocketRouteApiTest extends \PHPUnit_Framework_TestCase
{

    function testCreation(){
        $caughtException = false;
        $api = new RocketRouteApi();

        try{
            $response  = $api->getNotam('EGHH');
        }catch (RocketRouteException $e){
            $caughtException = true;
        }
        $this->assertFalse($caughtException);

    }
    function testWrongPassword(){
        $caughtException = false;

        $api = new RocketRouteApi();
        $api->setPassword('pp');

        try{

            $response  = $api->getNotam('EGHH');

        }catch (RocketRouteException $e){
            $this->assertEquals(8, $e->getCode());
            $caughtException = true;
        }
        $this->assertTrue($caughtException);

    }

    function testManyCodes(){
        $api = new RocketRouteApi();
        $response = $api->getNotam(['EGKA' , 'EGHH']);
        print_r($response);
    }

}
