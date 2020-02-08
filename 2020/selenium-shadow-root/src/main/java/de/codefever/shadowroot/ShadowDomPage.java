package de.codefever.shadowroot;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.WebDriverWait;

/**
 * @author Eric
 * creation date: 08.02.2020 - 06:08
 */
public class ShadowDomPage {


    @FindBy(css = "#container")
    public WebElement container;

    @FindBy(css = "#normal-content p")
    public WebElement normalContentText;

    @FindBy(css = "#shadow-host")
    public WebElement shadowHost;

    @FindBy(css = "#shadow-content p")
    public WebElement shadowContentText;

    public ShadowDomPage(WebDriver driver) {

        PageFactory.initElements(driver, this);

    }

}
