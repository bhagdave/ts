# Testing
The testing process and protocol for the app is to build tests for any changes you make to the app.  So if you go in and refactor a piece of code you **MUST** write a corresponding test to ensure that it still works.  The same goes for any features that are added, a test or suite of tests **MUST** be created.

The tests at the moment are Feature and Browser tests (in Laravel Partlance).  The feature tests are the equivalent of integration tests and are used to test a feature from end to end.  So therefore will probably involve the mocking of calls to app endpoints.  The other tests are browser tests done through Dusk which is a tool very similar to Selenium.  These actually open up a headless browser and go through the site testing the clicks etc.

The feature tests are in the [tests/Feature] folder and are run by doing a vendor/bin/phpunit from the root folder.  They use the .env.testing environment setup and look for credentials in there to test certain things.

The browser tests are in the [tests/Browser] folder and need to have the php artisan server running before you run them.  They also use a different .env file and look for .env.dusk.local to run against.  They are run by running php artisan dusk from the root folder.  You can also run dusk:dashboard from the root folder which will watch an6y changes to the code and run the tests and whow you the results in the browser.

If you run the above commands they will run all tests but you can limit it to one test by giving the path and file name in the command line.

All tests must be passing before sending anything to deploy.
