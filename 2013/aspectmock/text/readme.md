AspectMock - Mocken ohne Dependency Injection in PHP

Als Follower des Codeception-Twitter Kanals wurde in den vergangen Tagen eine Sache sehr oft erwähnt: AspectMock. Der Entwickler von Codeception Michael Bodnarchuk stellte sein neues Werk vor - eine einfach anzuwendende Mocking-Bibliothek basierend auf Go! AOP.

Für mich als Tester war der Trubel genug Anreiz um mir die Version 0.1.0 zu besorgen und die ersten Sachen auszuprobieren. Nach einigen Startschwierigkeiten erwieß sich die Anwendung als äußerst einfach. 

Ein großer Vorteil? Es ist auch möglich Code ohen Dependency Injection zu testen. Was das heißt möchte ich folgend erläutern.


Installation
Wie zahlreiche andere Packete steht Codeception\AspectMock via Packagist und damit als Composer-Package zur Verfügung.

"require-dev": {
    "phpunit/phpunit": "3.7.*",
    "codeception/aspect-mock": "0.1.0", //für stabile Version
    "codeception/aspect-mock": "*" // für aktuelle Version
},

Nach einem composer update steht das Framework zur Verfügung.Folgend habe ich im Verzeichnis tests/ eine bootstrap.php Datei erstellt, welche den Composer-Autoloader ausführt und Aspect-Mock lädt.

// tests/bootstrap.php
include __DIR__.'/../vendor/autoload.php'; // composer autoload

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    // 'cacheDir' => __DIR__.'/../tests/cache',
    // 'excludePaths' => [__DIR__.'/../vendor'],
    'includePaths' => [__DIR__.'/../src']
]);
?>
Diese Datei habe ich folgend in der phpunit.xml angegeben.

// phpunit.xml (project root)
<xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false"
         backupStaticAttributes="false"
         bootstrap="tests/bootstrap.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Application Test Suite">
            <directory>./tests/</directory>
        </testsuite>
    </testsuites>
</phpunit>

Die Testobjekte.
Folgend möchte ich das ganze an einem Beispiel erklären. Ich verwende, wie schon bei meiner Einführung für Mockery eine fiktionale Logbook-Klasse als Grundlage.
Also. Die Klasse Logbook besitzt eine Methode logToFile, welche auf die Methode put der Klasse Filesystem zugreift. Filesystem stellt lediglich einen Wrapper für die Methoden zur Dateiverarbeitung bereit.

Umgesetzt sieht das ganze so aus.

// src/Filesystem.php
class Filesystem 
{
	public function put($name, $content)
	{
		file_put_contents($name, $content);
	}
}

// src/Logbook.php
class Logbook
{
	protected $logfileName = 'test.log';

	public function logToFile($line)
	{
		$filesystem = new Filesystem();
		$filesystem->put($this->logfileName, $line);
	}
}

Und wie Euch bereits aufgefallen ist, wird beim Aufruf der Abhängikeit Filesystem in der Klasse Logbook dieses mal keine Dependency Injection verwendet. Das heißt, ein neues Object der Filesystem-Klasse wird erst im Aufruf der Methode logToFile vorgenommen. Dadurch wäre es unter normalen Umstände nicht möglich die Methode logToFile zu testen ohen dabei wirklich auf das Dateisystem zuzugreifen.

Der Test.
Durch Aspect-Mock jedoch ist das eben angesprochene Problem aber keines mehr. 
Um Aspect-Mock nun im Testfall zu verwenden gestaltet sich der Grundaufbau wie folgt. Wichtig dabei ist, dass test::clean() nach jedem Test aufgerufen wird. Nur so wird sichergestellt das alles Test-Doubles, Dummys und sonstige Sachen auch wirklich wieder aufgeräumt sind. Das Einbinden geschieht via use AspectMock\Test.

// tests/LogbookTest.php
use AspectMock\Test as test;

class LoogbookTest extends \PHPUnit_Framework_TestCase
{
	public function tearDown()
	{
		test::clean();
	}
}

Nun geht es an den eigentlichen Test, welcher wie folgt in bereits erähnter LogbookTest.php-Datei abgelegt wird.

public function testLogToTestWritesToFile()
{
	// object and method to mock
	$filesystem = test::double('Filesystem', ['put'=> '']);

	// object under test
	$log = new Logbook;

	// call method under test
	$log->logToFile('this is a test line');

	// verify that the method 'put' was invoked with the right parameters
	$filesystem->verifyInvoked('put', ['test.log', 'this is a test line']);
}

Zu den einzelnen Aufrufen. Zu Beginn wird ein Test-Double erstellt und in Array-Deklaration angegeben, wie welche Methode zu reagieren hat.
Anschließend wird ein neues Objekt der Klasse Logbook instanziiert, der zu testenden Klasse. Direkt danach wird die zu testende Methode mit dem benötigten parameter aufgerufen.
Damit wäre der Test an sich abgeschlossen. Jedoch gehe ich auf Nummer sicher, in dem die verifyInvoked-Method aufgerufen wird. So wird sichergestellt, dass die Methode auch wirklich mit dem richtigen Parametern aufgerufen wurde.

Und das erstaunliche daran ist. Das ganze funktioniert, wie bereits erwähnt auch OHNE Dependency-Injection. Wie ihr seht musste kein Objekt der Klasse Filesystem als Abhängigkeit mit an die Logbook-Klasse übergeben werden.

Erst wollte ich das ganze nicht glauben, daher habe ich kurzerhand einfach mal folgende Zeile abgeändert und den Test dann ausgeführt. 

$filesystem = test::double('Filesystem', ['put'=> '']);
$filesystem = test::double('Filesystem', [''=> '']);

Und tatsächlich. Ohne Angabe der put-Methode wird direkt auf das Dateisystem zugegriffen. Also erledigt Aspect-Mock seinen Job exzellent. 
[PICTURE filetree.png]

Anschließend habe ich noch ein paar weitere Sachen probiert, wie zum Beispiel das Entfernen des Aufrufs der put-Methode im Logbook-Bereich um sicherzugehen, dass auch verifyInvoked einen Fehler wirft, wenn put gar nicht aufgerufen wird. Das ganze sieht dann wie folgt aus.

[PICTURE]

Fazit
Also ich bin von den Möglichkeiten begeistert. Natürlich soltle man bei neuen projekten meiner Meinung nach auf Dependency-Injection achten, einfach um auch den Code sauberer zu halten, aber gerade beim Entwerfen von Tests für bereits bestehende projekte die noch ohne DI entwickelt wurden, stellt Aspect-Mock eine gute Möglichkeit bereit.

Statische Methoden mocken - Einfach wie nie zuvor?
Ein weiterer Vorteil ist auch, dass selbst statische Methodenaufrufe nun problemlos gemockt werden können. Das ganze geht genauso einfach. Ein Beispiel dazu noch kurzerhand. Dann ist aber wirklich Schluss.

// src/Filesystem.php
class Filesystem 
{
	public static function read($name)
	{
		return file_get_contents($name);
	}
}

// src/Logbook.php
class Logbook
{
	protected $logfileName = 'test.log';
	public function readLastLogLine()
	{
		Filesystem::read($this->logfileName);
		// go on here and read last line
	}
}

// tests/LogbookTest.php
public function testReadLastLogLineReadsLogFile()
{
	$filesystem = test::double('Filesystem', ['read' => 'hello, this is log']);

	$log = new Logbook;
	$log->readLastLogLine();

	$filesystem->verifyInvoked('read', 'test.log');
}

Das war es nun aber wirklich. Source-Code gibt es hier.