<?php 

class Comparer
{
	public function __construct()
	{

	}

	public function compare($one, $two)
	{
		if($one < $two)
		{
			return 'one < two';
		}

		if($one > $two)
		{
			return 'one > two';
		}

		if($one === $two)
		{
			return 'one === two';
		}

		return 'something else';
	}
}