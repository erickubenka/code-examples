<?php

use AspectMock\Test as test;

/**
 * Include tests for the Logbook Class
 * @package default
 */
class LoogbookTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		test::clean();
	}

	public function testLogToTestWritesToFile()
	{
		// specify which classes shoudl be mocked and what the called methods
		// should return
		$filesystem = test::double('Filesystem', ['put'=> '']);

		// Create a new Instance of object under test
		$log = new Logbook;

		// call the methods under test
		$log->logToFile('this is a test line');

		// verify that the method 'put' was invoked with the right parameters
		$filesystem->verifyInvoked('put', ['test.log', 'this is a test line']);
	}

	public function testReadLastLogLineReadsLogFile()
	{
		$filesystem = test::double('Filesystem', ['read' => 'hello, this is log']);

		$log = new Logbook;
		$log->readLastLogLine();

		$filesystem->verifyInvoked('read', 'test.log');
	}
}