<?php

namespace Tests\Calculator;

use Calculator\StringCalculatorInterface;
use Calculator\StringCalculator;

class StringCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StringCalculatorInterface
     */
    protected $calculator;

    public function setUp()
    {
        $this->calculator = new StringCalculator();
    }

    public function testEmptyString()
    {
        $this->calculator->setString('');

        $this->assertEquals(0, $this->calculator->getValue());
    }

    public function testSingleNumber()
    {
        $this->calculator->setString('1');

        $this->assertEquals(1, $this->calculator->getValue());
    }

    public function testTwoNumbersComma()
    {
        $this->calculator->setString('1,2');

        $this->assertEquals(3, $this->calculator->getValue());
    }

    public function testTwoNumbersNewLine()
    {
        $this->calculator->setString("1\r\n2");
        $this->assertEquals(3, $this->calculator->getValue());

        $this->calculator->setString("2\n2");
        $this->assertEquals(4, $this->calculator->getValue());

        $this->calculator->setString("1\r\n2\r\n8");
        $this->assertEquals(11, $this->calculator->getValue());

        $this->calculator->setString("3\r\n2\n8");
        $this->assertEquals(13, $this->calculator->getValue());
    }

    public function testSingleCharDelimiter()
    {
        $this->calculator->setString("//#\r\n1#2#3");

        $this->assertEquals(6, $this->calculator->getValue());
    }

    public function testMultiCharDelimiter()
    {
        $this->calculator->setString("//[###]\r\n1###3###4");

        $this->assertEquals(8, $this->calculator->getValue());
    }

    public function testNegativeNumbers()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->calculator->setString('-1');
        $this->calculator->getValue();

        $this->calculator->setString('-1,2');
        $this->calculator->getValue();

        $this->calculator->setString('1,-2');
        $this->calculator->getValue();

        $this->calculator->setString('-1\r\n2');
        $this->calculator->getValue();

        $this->calculator->setString('1\r\n-2');
        $this->calculator->getValue();

        $this->calculator->setString("//#\r\n-1#2#3");
        $this->calculator->getValue();

        $this->calculator->setString("//#\r\n-1#-2#3");
        $this->calculator->getValue();

        $this->calculator->setString("//#\r\n-1#2#-3");
        $this->calculator->getValue();

        $this->calculator->setString("//[###]\r\n-1###3###4");
        $this->calculator->getValue();

        $this->calculator->setString("//[###]\r\n1###-3###4");
        $this->calculator->getValue();

        $this->calculator->setString("//[###]\r\n1###3###-4");
        $this->calculator->getValue();
    }

    public function testNumberGreaterThan1000()
    {
        $this->calculator->setString('1000');
        $this->assertEquals(0, $this->calculator->getValue());

        $this->calculator->setString('1001,2');
        $this->assertEquals(2, $this->calculator->getValue());

        $this->calculator->setString('1,1002');
        $this->assertEquals(1, $this->calculator->getValue());

        $this->calculator->setString("1004\r\n4");
        $this->assertEquals(4, $this->calculator->getValue());

        $this->calculator->setString("5\r\n1006");
        $this->assertEquals(5, $this->calculator->getValue());

        $this->setExpectedException('InvalidArgumentException');

        $this->calculator->setString("//#\r\n1010#5#3#-10");
        $this->assertEquals(8, $this->calculator->getValue());

        $this->calculator->setString("//#\r\n10#-5#3000#10");
        $this->assertEquals(20, $this->calculator->getValue());

        $this->calculator->setString("//[###]\r\n-999###1###4###1000");
        $this->assertEquals(5, $this->calculator->getValue());

        $this->calculator->setString("//[###]\r\n999###1###-4###-1000");
        $this->assertEquals(1000, $this->calculator->getValue());
    }


}
