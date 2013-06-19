<?php

/**
 * Anchor Management Class to handle the generation of anchors
 * @package default
 */
class Anchor
{
	/**
	 * Create a Anchor with url and displayed text
	 * @param type $url 
	 * @param type $link 
	 * @return type
	 */
	public function create($url, $link)
	{
		return "<a href='{$url}'>{$link}</a>";
	}
}