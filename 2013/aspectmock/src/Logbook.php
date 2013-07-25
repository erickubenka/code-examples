<?php

/**
 * Log events and messages
 * @package default
 */
class Logbook
{
	protected $logfileName = 'test.log';

	/**
	 * Logs the given line to the logfile
	 * @param type $line 
	 * @return type
	 */
	public function logToFile($line)
	{
		$filesystem = new Filesystem();
		$filesystem->put($this->logfileName, $line);
	}

	/**
	 * Returns the last line in logfile 
	 * @return type
	 */
	public function readLastLogLine()
	{
		Filesystem::read($this->logfileName);
		// go on here and read last line
	}
}