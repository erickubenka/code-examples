using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;
using Microsoft.VisualStudio.TestTools.UITesting;
using UITestHelperMethods.Helper.Creation;
using UITestHelperMethods.Helper.Finder;

namespace UITestHelperMethods.Helper.Encapsulation
{
    public class Authentication
    {
        public static void PerformLogin(User user)
        {
            var usrTxtBox = ElementFinder.FindElementById("username");
            var pwTxtBox = ElementFinder.FindElementById("password");

            // Setze entsprechende Werte 
            usrTxtBox.SetProperty("Text", user.Name);
            pwTxtBox.SetProperty("Text", user.Password);

            // LoginButton Clicken

            var btn = ElementFinder.FindElementById("loginbtn");
            Mouse.Click(btn);
        }
    }
}
