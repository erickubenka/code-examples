## Das Problem [code-fever.de Blog-Eintrag](https://code-fever.de/artikel/selenium-haengende-sessions-verhindern.html)

Bei Verwendung von Selenium und dessen `RemoteWebDriver` kann es bei der Ausführung von Tests und Interaktionen dazu kommen, dass Verbindungsprobleme oder Netzwerkprobleme auftreten und die intern von Selenium festgelegten Timeouts für `ConnectionTimeout` und `ReadTimeout` greifen.  
Vor allem der Read Timeout sorgt hier dann für hängende Selenium-Sessions und lässt sich meist durch ein Abbruch des Testlaufs beheben.

Ich möchte zeigen, wie man Seleniums Default-Wert von 3 Stunden des Read-Timeouts von Selenium verändern kann.

## Entwicklungsumgebung

Wie fast immer verwende ich Selenium(Version 3.141.59) mit Java auf einem Windows Host System. Mein Remote-Selenium Server läuft hier beispielhaft mittels des Docker-Images von Selenium innerhalb eines Docker-Containers mit freigegebenem Port `4444`.

## Testklasse

`de.codefever.selenium.timeout.GooglePageTest`
````java
public class GooglePageTest {

    @Test
    public void testT01_GoogleSearch() throws MalformedURLException {

        final WebDriver driver = new RemoteWebDriver(new URL("http://localhost:4444/wd/hub"), new ChromeOptions());
        driver.get("https://google.de");
    }
}
````

Wie Euch und mir natürlich klar ist, wird dieses einfache Beispiel in den seltensten Fällen dazu führen, dass es zu einem Read-Timeout oder Connection-Timeout kommt.  
Doch was ist eigentlich der Unterschied?

### Connection Timeout
Der Connection-Timeout tritt auf, wenn die TCP-Verbindung zum Zielserver nicht hergestellt werden konnte. Ein TCP-Handshake konnte also innerhalb dieser Zeit nicht abgeschlossen werden.  
Der Default-Wert seitens Selenium liegt hier bei `2` Minuten.

### Read Timeout
Der Read-Timeout greift, wenn eine Verbindung erfolgreich aufgebaut werden konnte, der TCP-Handshake erflogreich abgeschlossen wurde und seitens des Servers keine Daten gesendet wurde, obwohl der Client welche erwartet.  
Der Default-Wert seitens Selenium liegt hier, wie eingangs bereits erwähnt, bei `3` Stunden.

Beide Werte werden in Seleniums Interface `HttpClient`, konkret in der dort abstrakt definierten Klasse `HttpClient.Builder` gesetzt.
[GitHub-Link zur File](https://github.com/SeleniumHQ/selenium/blob/selenium-3.141.59/java/client/src/org/openqa/selenium/remote/http/HttpClient.java)

## Lösung 

Um nun den Default Read-Timeout von 3 Stunden überschreiben zu können, muss also das Interface `HttpClient.Factory` implementiert werden.  
Eine einfache Lösung sieht wie folgt aus.

````java
public class CustomHttpFactory implements HttpClient.Factory {

    protected Duration connectionTimeout = Duration.ofSeconds(120L); // kills, when connect does not succeed in this timeout
    protected Duration readTimeout = Duration.ofSeconds(300L); // Kill hanging / stuck selenium commands after this timeout.
    private final ConnectionPool pool = new ConnectionPool();

    @Override
    public HttpClient.Builder builder() {
        return HttpClient.Factory.createDefault().builder()
                .connectionTimeout(connectionTimeout)
                .readTimeout(readTimeout);
    }

    @Override
    public HttpClient createClient(URL url) {
        return HttpClient.Factory.createDefault().builder()
                .connectionTimeout(connectionTimeout)
                .readTimeout(readTimeout).createClient(url);
    }

    @Override
    public void cleanupIdleClients() {
        pool.evictAll();
    }
}
````

Um diese selbst geschriebene `CustomHttpFactory` nun zu verwenden, muss das zu Beginn gezeigte Beispiel zum Aufruf des `RemoteWebDriver`-Objekts verändert werden. 
Dazu kann einfach einer der weiteren Konstruktoren verwendet werden, welcher ein Objekt der `HttpCommandExecutor` erwartet.  
Das Beispiel müsste dann nun also wie folgt aussehen:

````java
@Test
public void testT01_GoogleSearch() throws MalformedURLException {

    final HttpCommandExecutor executor = new HttpCommandExecutor(new HashMap<>(), new URL("http://localhost:4444/wd/hub"), new CustomHttpFactory());
    final WebDriver driver = new RemoteWebDriver(executor, new ChromeOptions());
    driver.get("https://google.de");
}
````

### Anmerkung
Mit Selenium 4 wurde der `HttpClient` überarbeitet und die hier gezeigte Lösung ist nicht mehr notwendig.


## Weiterführende Links
Den Quellcode für dieses Beispiel gibt es wie immer auf [GitHub](https://github.com/erickubenka/code-examples/tree/master/2020/selenium-three-hours-timeout).

[GitHub :: Selenium](https://github.com/SeleniumHQ/selenium)  
[GitHub :: Selenium HttpClient](https://github.com/SeleniumHQ/selenium/blob/selenium-3.141.59/java/client/src/org/openqa/selenium/remote/http/HttpClient.java)  
[DockerHub :: Selenium Docker Container](https://hub.docker.com/r/selenium/standalone-chrome/)
[GitHub :: Beispiel-Code](https://github.com/erickubenka/code-examples/tree/master/2020/selenium-three-hours-timeout)