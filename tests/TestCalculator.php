<?php

namespace tests;

use app\calculator\CalculatorService;
use app\calculator\operation\Plus;
use phpdk\tests\TestCase;

class TestCalculator extends AbstractHttpTestCase
{
    public function getCalculator()
    {
        return (new CalculatorService([
            '+' => 'localhost:8085',
        ]));
    }

    public function testSum()
    {
        $sum = new Plus('localhost:8085');
        static::assertEquals(3, $sum->execute(1, 2));
    }

    public function testSumInString()
    {

        $res = $this->getCalculator()->execute("5 + 6 + 1");
        static::assertEquals(12, (string)$res);
    }
}