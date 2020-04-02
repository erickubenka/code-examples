package de.codefever.selenium.timeout;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.remote.HttpCommandExecutor;
import org.openqa.selenium.remote.RemoteWebDriver;
import org.testng.annotations.Test;

import java.net.MalformedURLException;
import java.net.URL;
import java.util.HashMap;

/**
 * @author Eric Kubenka
 * creation date: 30.03.2020 - 23:11
 */
public class GooglePageTest {

    @Test
    public void testT01_GoogleSearch() throws MalformedURLException {

        final HttpCommandExecutor executor = new HttpCommandExecutor(new HashMap<>(), new URL("http://localhost:4444/wd/hub"), new CustomHttpFactory());
        final WebDriver driver = new RemoteWebDriver(executor, new ChromeOptions());
        driver.get("https://google.de");

    }


}
