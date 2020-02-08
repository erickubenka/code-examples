package de.codefever.shadowroot;

import org.openqa.selenium.By;
import org.openqa.selenium.JavascriptExecutor;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.testng.Assert;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;

import java.util.concurrent.TimeUnit;

/**
 * @author Eric
 * creation date: 08.02.2020 - 00:43
 */
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

        // Just ensure, that elements are displayed
        final WebElement container = driver.findElement(By.cssSelector("#container"));
        final WebElement normalContentText = driver.findElement(By.cssSelector("#normal-content p"));
        final WebElement shadowHost = driver.findElement(By.cssSelector("#shadow-host"));

        Assert.assertTrue(container.isDisplayed(), "#container is displayed");
        Assert.assertTrue(normalContentText.isDisplayed(), "#normal-content p is displayed");
        Assert.assertTrue(shadowHost.isDisplayed(), "#shadow-host is displayed");

        // This would throw an exception
//         final WebElement shadowContentText = driver.findElement(By.cssSelector("#shadow-content p"));

        // You have to select the shadowRoot via JavaScriptExecutor and return the attribute .shadowRoot
        final JavascriptExecutor javascriptExecutor = (JavascriptExecutor) driver;
        final WebElement shadowRoot = (WebElement) javascriptExecutor.executeScript("return arguments[0].shadowRoot", shadowHost);

        // Then you can do the following things
        final WebElement shadowContentText = shadowRoot.findElement(By.cssSelector("#shadow-content p"));
        Assert.assertTrue(shadowContentText.isDisplayed(), "#shadow-host #shadow-content p is displayed.");

        // But xpath wont work on Shadow Root elements.
        // final WebElement shadowContentTextWithXpath = shadowRoot.findElement(By.xpath("//*[@id='shadow-content']//p"));

        // But what you can do, is locate the top element of your shadow root and then executing a find with xpath on it.
        final WebElement shadowContent = shadowRoot.findElement(By.cssSelector("#shadow-content"));
        final WebElement shadowContentTextWithXpath = shadowContent.findElement(By.xpath("//p"));
    }

}
