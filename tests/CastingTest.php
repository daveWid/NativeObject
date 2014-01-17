<?php

use Wid\Cast;

class CastingTest extends PHPUnit_Framework_TestCase
{
	public function testToBoolean()
	{
		$this->assertFalse(Cast::toBoolean('false'));
	}

	public function testToInteger()
	{
		$this->assertSame(3, Cast::toInteger('3.1415'));
	}

	public function testToFloat()
	{
		$this->assertSame(3.1415, Cast::toFloat('3.1415'));
	}

	public function testToDateTime()
	{
		$date = Cast::toDateTime('2009-10-10 15:15:15');
		$this->assertInstanceOf('\\DateTime', $date);
	}

	public function testToDate()
	{
		$date = Cast::toDate('2009-10-10');
		$this->assertInstanceOf('\\DateTime', $date);
	}

	public function testToTime()
	{
		$date = Cast::toTime('15:15:15');
		$this->assertInstanceOf('\\DateTime', $date);
	}

	public function testToTimestamp()
	{
		$this->assertInstanceOf('\\DateTime', Cast::toTimestamp(time()));
	}

	public function testToCallback()
	{
		$value = "zero,one,two,three,four";
		$expected = array('zero', 'one', 'two', 'three', 'four');

		$callback = function($arg){
			return explode(',', $arg);
		};

		$this->assertSame($expected, Cast::toCallback($value, $callback));
	}

}
