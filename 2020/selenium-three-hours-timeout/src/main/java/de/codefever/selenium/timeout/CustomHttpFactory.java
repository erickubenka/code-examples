package de.codefever.selenium.timeout;

import okhttp3.ConnectionPool;
import org.openqa.selenium.remote.http.HttpClient;

import java.net.URL;
import java.time.Duration;

/**
 * @author Eric Kubenka
 * creation date: 30.03.2020 - 18:31
 */
public class CustomHttpFactory implements HttpClient.Factory {

    protected Duration connectionTimeout = Duration.ofSeconds(120L); // ill, when connect does not succeed in this timeout
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



