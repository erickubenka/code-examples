import java.io.File;
import java.io.IOException;

import org.apache.commons.io.FileUtils;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.firefox.FirefoxProfile;
import org.testng.Assert;
import org.testng.annotations.AfterMethod;
import org.testng.annotations.Test;

/**
 * BaseTest
 * <p>
 * Date: 27.01.2016
 * Time: 07:06
 *
 * @author Eric Kubenka
 */
public class FileDownloaderTest {

    @AfterMethod
    public void tearDown() {
        FileDownloader.deleteDownloads();
    }

    @Test
    public void testFileDownload() throws IOException, InterruptedException {

        FileDownloader downloader = new FileDownloader();

        FirefoxProfile profile = new FirefoxProfile();
        WebDriver driver = new FirefoxDriver(profile);
        driver.get("http://code-fever.de");

        final String absolutePath = downloader.download(driver, "http://code-fever.de/files/codefever/favicon.ico",
                "codefever_favicon.ico");

        File file = FileUtils.getFile(absolutePath);
        Assert.assertTrue(file.exists());
    }

}
