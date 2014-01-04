namespace UITestHelperMethods.Helper.Creation
{
    public class User
    {
        public string Name { get; set; }

        public string Password { get; set; }

        public User(string name, string password)
        {
            this.Name = name;
            this.Password = password;
        }
    }
}