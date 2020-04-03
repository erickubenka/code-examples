## Das Problem [code-fever.de Blog-Eintrag](https://code-fever.de/artikel/selenium-shadow-dom-und-shadow-root.html)
Als ich in der jüngeren Vergangenheit eine moderne Webseite testen sollte, stand ich vor dem Problem, dass die dort existierenden Elemente mittels Shadow DOM gerendert wurden.
Shadow DOM Elemente sind aus dem Selenium / Automatisierungsumfeld betrachtet Frames sehr ähnlich und halten einige Falltüren bereit. Ich zeige, wie man solche Elemente mittels Selenium lokalisiert und damit arbeitet.

## Entwicklungsumgebung einrichten

Ich arbeite mit Windows 10 und verwende `chocolatey` als Paketverwaltung, weil es das Reproduzieren von Software-Installationen einfacher macht, weshalb ich kurz darstellen möchte, wie ich meine Entwicklungsumgebung inklusive Java, JDK, Maven und ChromeDriver aufgesetzt habe.

````java
choco install chromedriver
choco install maven
choco install openjdk --version=12.0.2
````
## Website mit Shadow Roots
Extra für diesen Post hoste ich hier auf meinem Blog eine Demo-Seite, welche mit Shadow DOM arbeitet.

[code-fever: Example for Shadow DOM](https://examples.code-fever.de/2020/selenium-shadow-root/demosite/)

## Test erstellen
Aus persönlicher Überzeugung verwende ich in all meinen Testprojekten `TestNG`, weshalb ich dementsprechend nun kurz zeige, wie ich einen Basis TestNG Testfall mit `Selenium WebDriver` erzeuge und verwende. Dabei sollte beachtet werden, dass in produtkiven Projekten stets ein Manager oder Ähnliches für das Handling von WebDriver-Sessions verwendet wird, anstatt es via `BeforeMethod` und `AfterMethod` zu lösen.

````java
public class ShadowRootExampleTest {

    private WebDriver driver = null;

    @BeforeMethod(alwaysRun = true)
    public void openDriver() {
        driver = new ChromeDriver();
        driver.manage().timeouts().implicitlyWait(5, TimeUnit.SECONDS);
    }

    @AfterMethod(alwaysRun = true)
    public void closeDriver() {
        if (driver != null) {
            driver.close();
        }
    }

    @Test
    public void testShadowRoot() {

        driver.get("https://examples.code-fever.de/2020/selenium-shadow-root/demosite/");
    }
}
````

## Elemente vom Shadow DOM lokalisieren
Um Elemente eines Shadow DOM mittels Selenium zu lokalisieren, muss man zuerst den `Shadow-Root` finden, also jenes Element, welches den Beginn des Shadow DOM darstellt. In dem Beispielfall ist dies `#shadow-host`.

Ab dieser Ebene ist es mit Standard-Selenium-Selektoren nicht mehr möglich tiefere Elemente zu lokalisieren. Dazu ein Beispiel, welches zu einer `ElementNotFoundException` führen würde.
````java 
final WebElement shadowContentText = driver.findElement(By.cssSelector("#shadow-content p"));
````

Um dieses Problem zu umgehen ist die Lösung der `JavaScritpExecutor`, mit welchem ein neues Element ermittelt werden kann. 
````java
final WebElement shadowHost = driver.findElement(By.cssSelector("#shadow-host"));
final JavascriptExecutor javascriptExecutor = (JavascriptExecutor) driver;
final WebElement shadowRoot = (WebElement) javascriptExecutor.executeScript("return arguments[0].shadowRoot", shadowHost);
````

Ausgehend von diesem `WebElement` können nun mittels `findElement()` die tiefer liegenden Elemente lokalisiert werden. Mit einer kleinen Ausnahme!

## Nur findElement(By.cssSelector()) funktioniert!
Die Ausnahme ist, dass tiefer liegende Elemente nur mittels css-Selektor loaklisiert werden können, da es technisch einfach nicht möglich ist, einen anderen Ausdruck zu verwenden. Doch auch für dieses "Problem" gibt es eine einfache Lösung. Die meisten Shadow DOMs werden mit einem Root-Element beginnen, welches den Inhalt des DOM wrappt. Ausgehend von diesem Element, wenn es denn einmal lokalisiert wurde, kann dann wieder jede Such-Methode verwendet werden, um Elemente zu lokalisieren.

Funktioniert, da CSS-Selektor:
````java
final WebElement shadowContentText = shadowRoot.findElement(By.cssSelector("#shadow-content p"));
````

Funktioniert nicht, da Xpath-Selektor:
````java
final WebElement shadowContentTextWithXpath = shadowRoot.findElement(By.xpath("//*[@id='shadow-content']//p"));
````

Funktioniert, da zuerst ein CSS-Selektor verwendet wird:
````java
final WebElement shadowContent = shadowRoot.findElement(By.cssSelector("#shadow-content"));
final WebElement shadowContentTextWithXpath = shadowContent.findElement(By.xpath("//p"));
````

## Abschluss
Alle Source-Files und eine funktionierende Demo-Seite gibt es wie immer auf `GitHub`.

### Links
Internal:
- [codefever.de Demo-Website](https://examples.code-fever.de/2020/selenium-shadow-root/demosite/)
- [GitHub Sources](https://github.com/erickubenka/code-examples/tree/master/2020/selenium-shadow-root)

External:
- [Chocolatey](https://chocolatey.org/)
- [Selenium](https://selenium.dev/)
- [Maven](https://maven.apache.org/)
- [ChromeDriver](https://chromedriver.chromium.org/)
- [TestNG](https://testng.org/doc/)
- [Shadow-DOM W3C](https://www.w3.org/TR/dom41/#shadow-trees)
- [Shadow DOM Mozilla](https://developer.mozilla.org/en-US/docs/Web/Web_Components/Using_shadow_DOM)