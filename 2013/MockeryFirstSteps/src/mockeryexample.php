<?php // file: mockeryfirststeps.php

class Filesystem
{
	function __construct()
	{
		// see how this class not implemented a put()-method?
		// but we're able to test this all classes that are calling for a put() method with mockery
	}
}

/**
 * Logbook write given lines to logfile.
 * @package default
 */
class Logbook
{
	/**
	 * protect property (Filesystem)
	 * 
	 */
	protected $file;

	/**
	 * Constructor for better testing
	 * Btoh is possible
	 * - inject the dependency
	 * - or a new Filesystem object will be created automatically
	 * @param type Filesystem $file 
	 * @return type
	 */
	function __construct(Filesystem $file = null)
	{
		// pass a $file object or create a new. So both is possible.
		// Easy Access for developers and mocking for testers
		$this->file = $file ?: new Filesystem;
	}

	/**
	 * log the given line to the given logfile
	 * @param type $line 
	 * @return type
	 */
	public function logtoFile($line)
	{
		return $this->file->put($line, 'logfile.txt');
	}
}

/**
 * Test the Logbook Class
 * 
 * @package default
 */
class LogBookTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tear down the test environment
	 * important: Mockery::close();
	 * 
	 * @return type
	 */
	public function tearDown()
	{
		Mockery::close();
	}

	/**
	 * test that the logToFileMethod is accessing the filesystem trough put
	 * 
	 * @return type
 	*/
	public function testLogToFileWritesToFile()
	{
		$file = Mockery::mock('Filesystem');

		$file->shouldReceive('put')
			->with('This is a line', 'logfile.txt')
			->once()
			->andReturn(true);

		$log = new Logbook($file);

		$this->assertTrue($log->logToFile('This is a line'));
	}
}