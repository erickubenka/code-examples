<?php

/**
 * Test the anchor class
 * @package default
 */
class AnchorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * test that the create method works as epected
	 * @return type
	 */
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