<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 10:10
 */

namespace tests\Domain;


use App\Domain\GeoSpot;

class GeoSpotTest extends \PHPUnit_Framework_TestCase
{

    function testCreation(){
        $spot  = new GeoSpot('5129N00028W005');

        $this->assertAttributeEquals('5129N00028W005', 'rawString', $spot);

        $this->assertEquals(5 * GeoSpot::NM_TO_METER, $spot->radius);
        $this->assertEquals(round(51 + 29/60.0, 4), round($spot->latitude, 4));
        $this->assertEquals(round(-28/60.0, 4), round($spot->longitude, 4));

    }

    function testWithoutRadius(){
        $spot  = new GeoSpot('5129N00028W');

        $this->assertAttributeEquals('5129N00028W', 'rawString', $spot);

//        $this->assertEquals(5 * 1855.3248, $spot->radius);
        $this->assertEquals(round(51 + 29/60.0,4), round($spot->latitude, 4));
        $this->assertEquals(round(-28/60.0, 4), round($spot->longitude, 4));

    }

}
