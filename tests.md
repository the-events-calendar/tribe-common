# Tribe tests setup guide
This is a brief and quick guide that's covering the bare essentials needed to set up the tests on your local plugin copy.
Please refer to [Codeception](http://codeception.com/docs) and [WP Browser](https://github.com/lucatume/wp-browser) documentation for any issue that's not TEC related.

## Set up
After cloning the repository on your local machine change directory to the plugin root folder and pull in any needed dependency using [Composer](https://getcomposer.org/):

	composer update

Our testing stack uses modules from [wp-browser](https://github.com/lucatume/wp-browser "lucatume/wp-browser · GitHub") on top of the [Codeception](http://codeception.com/ "Codeception - BDD-style PHP testing.") testing framework.  
In each plugin we have different *suites* set up to test our code in different ways and different levels; each suite has its own configuration file, e.g. the `wpunit` suite will read its configuration from the `tests/wpunit.suite.yml` file.  
The repository provides, for each suite, the *distribution* version of the suite configuration file; e.g. the distribution version of the `wpunit` suite configuration file will be called `tests/wpunit.suite.dist.yml`.  
For each suite **make a copy** of the distribution version of the suite configuration file and remove the `.dist` part; e.g. clone the `wpunit.suite.dist.yml` file to the `wpunit.suite.yml` file, this is now the *local* version of the suite configuration file.  
Codeception applies suites configurations in a cascading way (same as CSS): apply the distribution version first and then override it with the local version.  
The distribution version of the suites configuration files will specify domains, database credentials and other details suitable for the CI environment: you should modify those value in each suite configuration file to match your local setup.

### Testing the testing framework
To test that the configuration is ok run Codeception build command:

```shell
./vendor/bin/codeception build
```
If you get some errors **take the time to read what the error says** and fix it.  
If you did everything ok you should now be able to run each suite with the following command:

```shell
./vendor/bin/codeception run <suite>
```

E.g.

```shell
./vendor/bin/codeception run wpunit
```

Do not use the command to run all suites at the same time as we have, in many plugins, multisite test suites that will break the world.

## Adding test cases to the suites
[wp-browser](https://github.com/lucatume/wp-browser "lucatume/wp-browser · GitHub") extends [Codeception](http://codeception.com/ "Codeception - BDD-style PHP testing.") to provide convenient ways to generate test cases, e.g. adding a test case for the `Tribe__Some__Class` class to the `wpunit` suite is as easy as:

```shell
./vendor/bin/wpcept generate:wpcept wpunit "Tribe\Some\Class"
```

The command will take care of the rest.  
If the test case has already been added add a test method (a `function`) to the test case (the `class somethingTest`).

### Where and what should I add
Please spend some time to understand what you are doing reading something about testing.  
In short:  

* **Acceptance** tests are meant to test the UI/API from a client perspective, run against the full application stack and do not "peek" at the database and set the testing fixture using a db dump and UI only; typical modules: `WPBrowser`, `WPWebDriver`, `WPDb`)
* **Functional** tests are meant to test the implementation of that client consumed/used UI/API from a developer perspective, run against the application entry points (e.g. WordPress routing system) and set up the testing fixture manipulating the database and HTTP requests (GET, POST etc.); typical modules: `WordPress`, `REST`, `WPDb`, `Filesystem`)
* **Integration** tests are meant to test a group of classes working together to accomplish a task (e.g. REST API endpoint handling), run against a module main class (e.g. a REST API endpoint handler) and set up the fixture using WordPress methods; typical modules: `WPLoader`, `Filesystem`
* **WordPress Unit** tests are meant to test a single class that depends on WordPress functions in isolation mocking its dependencies and checking its outputs, run against a single class and set up the testing fixture using mocking and WordPress methods typical modules: `WPLoader`
* **Unit** tests are meant to test a single class that doesn't depend on WordPress functions in isolation mocking its dependencies and checking its outputs, run against a single class and set up the testing fixture using mocking and WordPress methods typical modules: `Unit`

We do a mostly WordPress Unit tests.

## Testing dos and donts
1. If you break it you fix it.
2. Removing a test is not fixing; it should **never** be done unless the tested class/case/module has been removed.
3. Use namespacing for testing: the test framework requires PHP 5.6: stop writing code for PHP 5.2 in tests.
4. Avoid "clever" code in tests: clarity is paramount in testing over smart code.
5. When in doubt call for help.

Read [Codeception](http://codeception.com/ "Codeception - BDD-style PHP testing.") and [wp-browser](https://github.com/lucatume/wp-browser "lucatume/wp-browser · GitHub") documentation to understand what you are doing.
