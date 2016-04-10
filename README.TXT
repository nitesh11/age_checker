Age Gate is a simple module that restricts users to access the pages on the site once he passes the age Gate page of the site.

What a user has to do:
1. Navigate to the site.
2. User would be redirected to /agegate page and would have to pass the Age Gate page by filling up the Date of birth.
3. If the user's age is greater than the legal age, then he would be able to navigate to the site and access the content on the site.
4. If the user is under aged, user would be redirected to the Under age page.

Configuration pages:
1. Settings configuration for adding list of Languages and Countries:
  a. Page that lists the basic configuration of the module.
  b. URL of the configuration page : admin/config/people/age_checker
  b. Fields:
    -> Would you like to display remember me check box - A checkbox field for displaying the Remember me checkbox on the Age Gate page.
    -> URL for fetching the country code - Enter the API for fetching the country code of the user.
    -> Age Checker Languages - List of all the languages. It should be in key|value format.
    -> Age Checker Countries - List of all the countries that would be visible on the . It should be in key|value format.
    -> Cookie expiration days - Text field for adding cookie expiration time.
    -> Enter underage page url - Text field for adding the underage url.
    -> Add Background image - Adding the background image for the Age Gate.
    -> Add logo image - Adding the logo image for the Age Gate.