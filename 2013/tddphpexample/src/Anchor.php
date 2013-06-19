<?php

class Anchor
{
	public function create($url, $link)
	{
		return "<a href='{$url}'>{$link}</a>";
	}
}