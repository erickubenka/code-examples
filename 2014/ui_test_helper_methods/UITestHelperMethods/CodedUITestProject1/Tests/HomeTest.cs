using Microsoft.VisualStudio.TestTools.UITest.Common;
using UITestHelperMethods.Helper;
using Microsoft.VisualStudio.TestTools.UITesting;
using Microsoft.VisualStudio.TestTools.UnitTesting;
using UITestHelperMethods.Helper.Cleanup;
using UITestHelperMethods.Helper.Creation;
using UITestHelperMethods.Helper.Encapsulation;
using UITestHelperMethods.Helper.Finder;

namespace UITestHelperMethods.Tests
{
    /// <summary>
    /// Summary description for CodedUITest1
    /// </summary>
    [CodedUITest]
    public class HomeTest
    {
        public HomeTest()
        {
        }

        [TestMethod]
        public void LoginUserAndShowHome()
        {
            // Entsprechenden Testdatensatz generieren
            var user = DataGenerator.CreateUser("eric", "password");

            // Zusammengefasste Action Aufrufen, da die Handlungen immer identisch sind
            Authentication.PerformLogin(user);

            // Die Willkommen-Message suchen und entsprechend verifizieren
            var welcomemsg = ElementFinder.FindElementById("welcomemsg");
            StringAssert.Contains(welcomemsg.GetProperty("Text").ToString(), "Willkommen, eric");
        }

        [TestMethod]
        public void LoginUserAndShowHomeOld()
        {
            // Entsprechenden Testdatensatz generieren
            var user = new User("eric", "password");

            // Login-Elemente suchen udn füllen
            var usrTxtBox = new UITestControl();
            var pwTxtBox = new UITestControl();
            var btn = new UITestControl();

            // searchproperties hinzufügen und element suchen
            usrTxtBox.SearchProperties.Add("AutomationId", "username",PropertyExpressionOperator.EqualTo);
            usrTxtBox = usrTxtBox.FindMatchingControls()[0];

            // searchproperties hinzufügen und element suchen
            pwTxtBox.SearchProperties.Add("AutomationId", "password", PropertyExpressionOperator.EqualTo);
            pwTxtBox = pwTxtBox.FindMatchingControls()[0];

            // searchproperties hinzufügen und element suchen
            btn.SearchProperties.Add("AutomationId", "loginbtn", PropertyExpressionOperator.EqualTo);
            btn = btn.FindMatchingControls()[0];


            // Setze entsprechende Werte 
            usrTxtBox.SetProperty("Text", user.Name);
            pwTxtBox.SetProperty("Text", user.Password);

            // LoginButton Clicken
            Mouse.Click(btn);

            // Die Willkommen-Message suchen und entsprechend verifizieren
            var welcomemsg = new UITestControl();
            welcomemsg.SearchProperties.Add("AutomationId", "welcomemsg", PropertyExpressionOperator.EqualTo);

            StringAssert.Contains(welcomemsg.GetProperty("Text").ToString(), "Willkommen, eric");
        }


        #region Additional test attributes

        // You can use the following additional attributes as you write your tests:

        ////Use TestInitialize to run code before running each test 
        //[TestInitialize()]
        //public void MyTestInitialize()
        //{        
        //    // To generate code for this test, select "Generate Code for Coded UI Test" from the shortcut menu and select one of the menu items.
        //}

        //Use TestCleanup to run code after each test has run
        [TestCleanup()]
        public void MyTestCleanup()
        {
            CleanupHelper.RemoveUser("eric");
        }

        #endregion

        /// <summary>
        ///Gets or sets the test context which provides
        ///information about and functionality for the current test run.
        ///</summary>
        public TestContext TestContext
        {
            get
            {
                return testContextInstance;
            }
            set{
                testContextInstance = value;
            }
        }
        private TestContext testContextInstance;
    }
}
