<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 10:10
 */

namespace tests\Domain;


use App\Domain\GeoRadius;
use App\Domain\GeoSpot;

class GeoSpotTest extends \PHPUnit_Framework_TestCase
{

    function testCreation(){
        $spot  = new GeoSpot('5129N00028W005');

        $this->assertAttributeEquals('5129N00028W005', 'rawString', $spot);

        $this->assertEquals(round(5 * GeoRadius::NM_TO_METER, 0), (string) $spot->radius);
        $this->assertEquals(round(51 + 29/60.0, 6), (string) $spot->latitude);
        $this->assertEquals(round(-28/60.0, 6),     (string) $spot->longitude);

    }

    function testWithoutRadius(){
        $spot  = new GeoSpot('5129N00028W');

        $this->assertAttributeEquals('5129N00028W', 'rawString', $spot);

        $this->assertEquals(round(51 + 29/60.0, 6), (string) $spot->latitude);
        $this->assertEquals(round(-28/60.0, 6),     (string) $spot->longitude);

    }
    function testBatch(){
        $cases = [
            '9999S99977E' => [-90, 180, false],
            '9060N18060W' => [90, -180, false],
            '0060N00059E' => [59/60.0, 59/60.0 , false],
            '8000S15030E' => [-80, 150.5, true],
            '8000S15030E040' => [-80, 150.5, true],
            '0500S03030E040' => [-5, 30.5, true],
//            '' => [],
        ];

        foreach ($cases as $raw => $case){
            $spot = new GeoSpot($raw);

            $this->assertEquals(round($case[0], 6), (string) $spot->latitude);
            $this->assertEquals(round($case[1], 6), (string) $spot->longitude);
            $this->assertEquals($case[2], $spot->getNotamString() == $raw, $raw);
        }
    }

}
