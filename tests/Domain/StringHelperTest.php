<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 30.03.16
 * Time: 21:42
 */

namespace tests\Domain;


use App\Domain\StringHelper;

class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    static $cases = [
        [ 'str' => '', 'lens' => [], 'out' => [] ],
        [ 'str' => '', 'lens' => [1], 'out' => [''] ],
        [ 'str' => '', 'lens' => [-10], 'out' => [] ],
        [ 'str' => '', 'lens' => [1, 'p', 3.4, 3], 'out' => ['',''] ],
        [ 'str' => 'a', 'lens' => [-1], 'out' => ['a'] ],
        [ 'str' => 'aabbbbcc', 'lens' => [2, 4], 'out' => ['aa', 'bbbb', 'cc'] ],
        [ 'str' => 'aabbbbcc', 'lens' => [2, 4, 2], 'out' => ['aa', 'bbbb', 'cc'] ],
//        [ 'str' => '', 'lens' => [], 'out' => [] ],
//        [ 'str' => '', 'lens' => [], 'out' => [] ],
    ];

    public function testSplitByLength(){
        foreach (self::$cases as $case){
            $res = StringHelper::splitByLength($case['str'], $case['lens']);
            $this->assertEquals(serialize($case['out']), serialize($res));
        }
    }

    public function testSplitMultiple(){
        foreach (self::$cases as $case){
            $res = StringHelper::splitMultiple($case['str'], $case['lens']);
            $this->assertEquals(serialize($case['out']), serialize($res));
        }
    }

    public function generateCase(){
        $chunksNumber = rand(1,6);
        $chunks = [];
        $lens = [];
        foreach (range(1, $chunksNumber) as $i){
            $l = rand(2,7);
            $chunks[] = str_pad('', $l, $i);
            $lens[] = $l;
        }
        return ['str' => implode('', $chunks), 'lens' => $lens, 'out' => $chunks];
    }

    public function testBench(){
        for ($i = 100; $i > 0 ; $i -= 1){
            self::$cases[] = $this->generateCase();
        }

        $i = 100;
        $time = microtime(true);
        while ($i){
            foreach (self::$cases as $c){
                $res = StringHelper::splitByLength($c['str'], $c['lens']);
            }
            $i -= 1;
        }
        $stopTime = microtime(true);
        echo 'splitByLength = '.($stopTime - $time)." s \n";

        $i = 100;
        $time = microtime(true);
        while($i){
            foreach (self::$cases as $c){
                $res = StringHelper::splitMultiple($c['str'], $c['lens']);
            }
            $i -= 1;
        }
        $stopTime = microtime(true);
        echo 'splitMultiple = '.($stopTime - $time)." s \n";

    }
}
