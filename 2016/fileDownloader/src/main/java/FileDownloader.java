
/**
 * FileDownloader
 * <p>
 * Date: 27.01.2016
 * Time: 06:58
 *
 * @author Eric Kubenka
 */

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.net.URLConnection;
import java.security.GeneralSecurityException;
import java.security.cert.X509Certificate;
import java.util.ArrayList;
import java.util.List;

import javax.net.ssl.HttpsURLConnection;
import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

import org.apache.commons.io.FileUtils;
import org.apache.commons.lang3.StringUtils;
import org.openqa.selenium.Cookie;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

/**
 * FileDownloader
 * <p/>
 * Date: 10.12.2015
 * Time: 09:18
 *
 * @author vespa
 */
public class FileDownloader {

    /**
     * Loggin Instance
     */
    private static final Logger LOGGER = LoggerFactory.getLogger(FileDownloader.class);

    /**
     * list of downloaded files. need for cleanup
     */
    private static List<String> downloadList = new ArrayList<String>();

    /**
     *
     */
    private String downloadLocation = System.getProperty("java.io.tmpdir");

    /**
     * Imitate Cookies
     */
    private boolean imitateCookies = true;

    /**
     * Instantiate FileDownloader
     *
     * @param downloadLocation String DownloadLocation
     * @param imitateCookies boolean imitateCookies
     */
    public FileDownloader(final String downloadLocation, final boolean imitateCookies) {
        this.downloadLocation = downloadLocation;
        this.imitateCookies = imitateCookies;
    }

    /**
     * Instantiate FileDownloader
     *
     * @param downloadLocation String
     */
    public FileDownloader(final String downloadLocation) {
        this.downloadLocation = downloadLocation;
    }

    /**
     * Instantiate FileDownloader
     *
     * @param imitateCookies booolean
     */
    public FileDownloader(final boolean imitateCookies) {
        this.imitateCookies = imitateCookies;
    }

    /**
     * Instantiate FileDownloader
     */
    public FileDownloader() {

    }

    /**
     * Deletes all Downloads
     */
    public static void deleteDownloads() {

        for (String path : downloadList) {
            File file = FileUtils.getFile(path);
            if (!file.delete()) {
                LOGGER.warn(String.format("File >%s< couldn't be deleted on cleanup. Please remove file manually.",
                        file.getAbsolutePath()));
            }
        }
    }

    /**
     * Get the current location that files will be downloaded to.
     *
     * @return The filepath that the file will be downloaded to.
     */
    public String getDownloadLocation() {
        return this.downloadLocation;
    }

    /**
     * Set the path that files will be downloaded to.
     *
     * @param filePath The filepath that the file will be downloaded to.
     */
    public void setDownloadLocation(final String filePath) {
        this.downloadLocation = filePath;
    }

    /**
     * Download the file specified in the href/src attribute of a WebElement
     *
     * @param element WebElement
     * @param targetFileName String
     * @return String
     */
    public String download(final WebDriver driver, final WebElement element, final String targetFileName) {

        LOGGER.info("Try to get href attribute of WebElement");
        String link = element.getAttribute("href");

        if (link.length() < 1) {
            LOGGER.info("No href attribute found. Try src attribute.");
            link = element.getAttribute("src");
        }

        if (link.length() < 1) {
            throw new RuntimeException("Neither href nor src attribute found on WebElement.");
        }

        return this.download(driver, link, targetFileName);
    }

    /**
     * Download file by URL
     *
     * @param driver WebDriver
     * @param url String
     * @param targetFileName String
     * @return String
     */
    public String download(final WebDriver driver, final String url, String targetFileName) {
        return this.pDownload(driver, url, targetFileName);
    }

    /**
     * imitate Cookies?
     * 
     * @return boolean
     */
    public boolean isImitateCookies() {
        return this.imitateCookies;
    }

    /**
     * Imitate cookies (Default: true)
     *
     * @param value boolean
     */
    public void setImitateCookies(boolean value) {
        this.imitateCookies = value;
    }

    /**
     * Load in all the cookies WebDriver currently knows about so that we can mimic the browser cookie state
     *
     * @param driver WebDriver
     * @return String
     */
    private String getImitatedCookies(final WebDriver driver) {

        String result = "";

        for (Cookie seleniumCookie : driver.manage().getCookies()) {
            result = result + String.format("%s=%s;", seleniumCookie.getName(), seleniumCookie.getValue());
        }

        return result;
    }

    /**
     * Downloads the given file
     *
     * @param driver WebDriver
     * @param url String
     * @param targetFileName String
     * @return String
     */
    private String pDownload(final WebDriver driver, final String url, final String targetFileName) {

        this.ensureLocationExists();

        try {
            URL fileToDownload = new URL(url);
            LOGGER.info("Download file from: " + url);

            File downloadedFile = new File(
                    this.cleanPath(this.getDownloadLocation()) + "\\" + this.cleanPath(targetFileName));
            LOGGER.info("Target location is: " + downloadedFile.getAbsolutePath());

            LOGGER.info("Open Connection");
            URLConnection request = fileToDownload.openConnection();

            LOGGER.info("Imitate cookies is set to " + this.isImitateCookies());
            if (this.isImitateCookies()) {
                request.setRequestProperty("Cookie", this.getImitatedCookies(driver));
            }

            InputStream in = request.getInputStream();
            FileOutputStream out = new FileOutputStream(downloadedFile);
            byte[] buffer = new byte[1024];
            int len = in.read(buffer);
            while (len != -1) {
                out.write(buffer, 0, len);
                len = in.read(buffer);
                if (Thread.interrupted()) {
                    throw new InterruptedException();
                }
            }
            in.close();
            out.close();

            LOGGER.info("File successfully loaded. Location is: " + downloadedFile.getAbsolutePath());

            downloadList.add(downloadedFile.getAbsolutePath());
            return downloadedFile.getAbsolutePath();
        } catch (InterruptedException | IOException e) {
            throw new RuntimeException("Error during File download", e);
        }
    }

    /**
     * Ensure Location exists
     *
     * @return boolean
     */
    private boolean ensureLocationExists() {

        File downloadLoc = FileUtils.getFile(this.getDownloadLocation());
        return downloadLoc.exists() || downloadLoc.mkdirs();
    }

    /**
     * removes slashes
     *
     * @param path String
     * @return String
     */
    private String cleanPath(String path) {

        path = StringUtils.removeStart(path, "/");
        path = StringUtils.removeStart(path, "\\");
        path = StringUtils.removeEnd(path, "/");
        path = StringUtils.removeEnd(path, "\\");
        return path;
    }
}
