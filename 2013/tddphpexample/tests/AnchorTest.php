<?php

class AnchorTest extends PHPUnit_Framework_TestCase
{
	public function testCreateAnchor()
	{
		// arrange
		$anchor = new Anchor('red');

		$expected = "<a href='http://code-fever.de'>Code Fever</a>";
		
		// act
		$actual = $anchor->create('http://code-fever.de', 'Code Fever');

		// assert
		$this->assertSame($expected, $actual);
	}
}