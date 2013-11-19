<?php

class ComparerTest extends PHPUnit_Framework_TestCase
{
	public function testCompareASmallerB()
	{
		$comparer = new Comparer();
		$result = $comparer->compare(12, 20);

		$this->assertEquals('one < two', $result);
	}

	public function testCompareBSmallerA()
	{
		$comparer = new Comparer();
		$result = $comparer->compare(25, 20);

		$this->assertEquals('one > two', $result);
	}
}