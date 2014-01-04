<?php namespace Example;

use \Example\Connection\Connector;

class MyExample
{
	public function hello()
	{
		echo 'hello world';
		$c = new Connector();
		var_dump($c->connect('eric'));
	}	
}