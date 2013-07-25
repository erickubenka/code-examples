<?php

/**
 * Simple Filesystem Wrapper
 * @package default
 */
class Filesystem 
{
	/**
	 * Writes the given content to the given file(by name)
	 * @param type $name 
	 * @param type $content 
	 * @return type
	 */
	public function put($name, $content)
	{
		file_put_contents($name, $content);
	}

	/**
	 * Reads the content of the given file
	 * @param type $name 
	 * @return type
	 */
	public static function read($name)
	{
		return file_get_contents($name);
	}
}