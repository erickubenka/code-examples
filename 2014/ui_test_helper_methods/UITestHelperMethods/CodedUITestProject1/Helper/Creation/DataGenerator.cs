using System.Collections.Specialized;

namespace UITestHelperMethods.Helper.Creation
{
    public class DataGenerator
    {
        public static User CreateUser(string name, string password)
        {
            var user = new User(name, password);
            return user;
        }
    }
}
