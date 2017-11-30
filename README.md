# EFL Web Services PHP Clients
This library provides a set of clients that you can integrate into your PHP applications to connect to EFL Web Services, including Applicant Journey and Scores APIs.

## Installation
**Compatibility:** EWS clients have been tested for compatibility with ["Active Support" versions of PHP](http://php.net/supported-versions.php).  Earlier versions of PHP (including "Security Fixes Only" versions) are not supported.

**Dependencies:** [Git](https://git-scm.com/) is required to install the EWS clients library.

### Using Composer (Recommended)
To install this library using [Composer](https://getcomposer.org/), run the following commands:

1. Install Composer (see [instructions](https://getcomposer.org/download/)).  Skip this step if Composer is already installed.
   ```
   > curl -sS https://getcomposer.org/installer | php
   ```

2. Set up `composer.json`.  Skip this step if your project already has a `composer.json` file.
   ```
   > php composer.phar init
   ```

   **Important:** when it asks if you would like to define dependencies interactively, type "no".

3. Configure `composer.json` to use the `ews-client-php` repository:
   ```
   > php composer.phar config repositories.EWSClient '{"type": "git", "url": "https://github.com/eflglobal/ews-client-php"}' 
   ```

4. Configure `composer.json` to install the EWS clients as a dependency:
   ```
   > php composer.phar require 'EFLGlobal/EWSClient' 'dev-master'
   ```

   **Note:** This will also install the EWS clients library automatically.

In order to use EWS clients, ensure that you `require 'vendor/autoload.php';` in your application (see [instructions](https://getcomposer.org/doc/01-basic-usage.md#autoloading)).

**Tip:** `composer.json` and `composer.lock` should be checked into your project's source control system (e.g., `git add composer.json composer.lock`).  `composer.phar` should not be checked in (e.g., add it to `.gitignore`).

### Manually
For a manual installation, complete the following steps:

1. `git clone https://github.com/eflglobal/ews-client-php`
2. Copy the files from `ews-client-php/src` into your project.

Make sure that your project's autoloader is aware of the new library and/or make sure to `require` the necessary files in your application.

## Usage
### Applicant Journey API
To send requests to the Applicant Journey API use the `EFLGlobal\EWSClient\AJAPIController` class.

The `AJAPIController` constructor takes four arguments:

- `$url`: URL path to API:
    - For testing: `'https://api-uat-external.eflglobal.com/api/v2/applicant_journey/'`
    - For production: `'https://api.eflglobal.com/api/v2/applicant_journey/'`
- `$identifier`: Absolute path to `identifier.txt` file.
- `$decryptionKey`: Absolute path to `decryption.key` file.
- `$encryptionKey`: Absolute path to `encryption.key` file.

The `identifier.txt`, `decryption.key` and `encryption.key` files can be found in the API Key archive downloaded from the EFL Webapp; contact EFL Support for more information.

Example:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use EFLGlobal\EWSClient\AJAPIController;

$client = new AJAPIController(
  'https://api-uat-external.eflglobal.com/api/v2/applicant_journey/',
  '/path/to/identifier.txt',
  '/path/to/decryption.key',
  '/path/to/encryption.key'
);
```

#### Sending AJ API Requests
##### `callStartSession`
Method connects to [`startSession`](https://developers.eflglobal.com/applicant-journey-api/endpoints/startSession.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.
- `$repeat`: (optional) Whether to auto-retry the request in the event of authToken expiration (usually the default value is what you want).

##### `callGetApplication`
Method connects to [`getApplication`](https://developers.eflglobal.com/applicant-journey-api/endpoints/getApplication.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.
- `$repeat`: (optional) Whether to auto-retry the request in the event of authToken expiration (usually the default value is what you want).

##### `callFinishStep`
Method connects to [`finishStep`](https://developers.eflglobal.com/applicant-journey-api/endpoints/finishStep.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.
- `$repeat`: (optional) Whether to auto-retry the request in the event of authToken expiration (usually the default value is what you want).


##### `callCreateAttachment`
Method connects to [`createAttachment`](https://developers.eflglobal.com/applicant-journey-api/endpoints/createAttachment.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.
- `$repeat`: (optional) Whether to auto-retry the request in the event of authToken expiration (usually the default value is what you want).

##### `callFinishSession`
Method connects to [`finishSession`](https://developers.eflglobal.com/applicant-journey-api/endpoints/finishSession.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.
- `$repeat`: (optional) Whether to auto-retry the request in the event of authToken expiration (usually the default value is what you want).

##### `callPrefetchApplications`
Method connects to [`prefetchApplications`](https://developers.eflglobal.com/applicant-journey-api/endpoints/prefetchApplications.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.
- `$repeat`: (optional) Whether to auto-retry the request in the event of authToken expiration (usually the default value is what you want).

##### `callResumeSession`
Method connects to [`resumeSession`](https://developers.eflglobal.com/applicant-journey-api/endpoints/resumeSession.html) endpoint and returns JSON answer.

Arguments:

- `$data`: Contents of the `data` payload.

This method does not accept a `$repeat` argument.

**Note:** the AJAPIController will call this method automatically when needed; your application only needs to invoke `callResumeSession` explicitly if you would like to customize the `$data` value.

#### Handling Errors
If an error occurs (e.g., non-200 response from the web service), the `\Exception` object will be returned.

To handle errors in your code, add an `instanceof` check, like this:

```php
$response = $client->callStartSession(...);

if ($response instanceof \Exception) {
  // Handle error case.
} else {
  // Handle success case.
}
```

### Scores API
To send requests to the Scores API, use the `EFLGlobal\EWSClient\ScoresAPIController` class.

The `ScoresAPIController` constructor takes four arguments:

* `$url`: URL path to API:
    * For testing: `'https://api-uat-external.eflglobal.com/api/v1/scores/'`
    * For production: `'https://api.eflglobal.com/api/v1/scores/'`
* `$identifier`: Absolute path to `identifier.txt` file.
* `$decryptionKey`: Absolute path to `decryption.key` file.
* `$encryptionKey`: Absolute path to `encryption.key` file.

The `identifier.txt`, `decryption.key` and `encryption.key` files can be found in the API Key archive downloaded from the EFL Webapp; contact EFL Support for more information.

Example:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use EFLGlobal\EWSClient\ScoresAPIController;

$client = new ScoresAPIController(
  'https://api-uat-external.eflglobal.com/api/v1/scores/',
  '/path/to/identifier.txt',
  '/path/to/decryption.key',
  '/path/to/encryption.key'
);
```

#### Sending Scores API Requests
##### `callDateQuery`
Sends a request to [`dateQuery`](https://developers.eflglobal.com/scores-api/endpoints/dateQuery.html) endpoint and returnts JSON answer.

Arguments:

- `$date`: The value to use as the lower bound for the date search, in YYYY-MM-DD format.

##### public function callSubject ($subject)
Sends a request to [`subject`](https://developers.eflglobal.com/scores-api/endpoints/subject.html) endpoint and returns JSON answer.

Arguments:

- `$subject`: The search query.  The format of this argument depends on the type of search you wish to perform:
    - Search by eflId: `[['eflId' => '<eflId>']]`
    - Search by ID number: `[['identification' => [['type' => '<id type>', 'value' => '<id number>']]]]`
    
    Note that the double brackets (`[[` and `]]`) are intentional.

## Additional Features
### Unit/Functional Tests
The library ships with a complete test suite.  These tests can be used to verify the functionality of the library, and you can use them as additional documentation (in particular, reading the assertions in each test is a great way to see exactly what the structure of each response payload will be).

To run the unit tests, you will need to install [`phpunit`](https://phpunit.de/).  You can install this library by running `php composer.phar require --dev phpunit/phpunit '^6.2'`

**Important:** The unit and functional tests require that the library was installed using Composer.

Run the unit tests like this:
```
> phpunit vendor/EFLGlobal/EWSClient/Tests/AJAPIUnitTests.php vendor/EFLGlobal/EWSClient/Tests/ScoresAPIUnitTests.php
```

To run the functional tests requires a little extra preparation.  You will need to install API keys that the client can use to send real API requests and verify the response.

Install API keys into `TestKeys/ApplicantJourney` and `TestKeys/Scores` (relative to current directory).

Example:
```
# Applicant Journey API Functional Tests
> mkdir -p TestKeys/ApplicantJourney
> cp /path/to/applicant_journey/{identifier.txt,decryption.key,encryption.key} TestKeys/ApplicantJourney/
> phpunit vendor/EFLGlobal/EWSClient/Tests/AJAPITests.php

# Scores API Functional Tests
> mkdir -p TestKeys/Scores
> cp /path/to/scores/{identifier.txt,decryption.key,encryption.key} TestKeys/Scores/
> phpunit vendor/EFLGlobal/EWSClient/Tests/ScoresAPITests.php
```

If you get an error similar to "command not found: phpunit", refer to <https://stackoverflow.com/q/26753674/> for ways to fix it.

### Demos
The EWS clients library includes example scripts that show how to get started with different EWS clients.

The location of these scripts depends on how the library was installed:

- Installed using Composer:  `vendor/EFLGlobal/EWSClient/Demos`
- Installed manually: the `ews-client-php/Demos` directory was created when you cloned the Git repository.

**Important:** the demos will only function if the library was installed using Composer.  Regardless, you can still reference the demos to see examples of how to use the library in your application. 

### Command-Line Demos
You can also execute command-line based files in `Demos` directory to try clients.

#### Applicant Journey API
To execute the Applicant Journey API Command-Line Demo, invoke the `vendor/EFLGlobal/EWSClient/Demos/AJAPICommand.php` script and provide the following arguments:

1. URL path to API:
    - For testing: `'https://api-uat-external.eflglobal.com/api/v2/applicant_journey/'`
    - For production: `'https://api.eflglobal.com/api/v2/applicant_journey/'`
2. Absolute path to `identifier.txt` file.
3. Absolute path to `decryption.key` file.
4. Absolute path to `encryption.key` file.
5. Name of application to load (contact EFL Support for more information).

The `identifier.txt`, `decryption.key` and `encryption.key` files can be found in the API Key archive downloaded from the EFL Webapp; contact EFL Support for more information.

Example:

```
> php vendor/EFLGlobal/EWSClient/Demos/AJAPICommand.php 'https://uat-external.eflglobal.com/api/v2/applicant_journey/' '/path/to/identifier.txt' '/path/to/decryption.key' '/path/to/encryption.key' 'sdkExample'
```

#### Scores API
To execute the Scores API Command-Line Demo, invoke the `vendor/EFLGlobal/EWSClient/Demos/ScoresAPICommand.php` script and provide the following arguments:


1. URL path to API:
    - For testing: `'https://api-uat-external.eflglobal.com/api/v1/scores/'`
    - For production: `'https://api.eflglobal.com/api/v1/scores/'`
2. Absolute path to `identifier.txt` file.
3. Absolute path to `decryption.key` file.
4. Absolute path to `encryption.key` file.

The `identifier.txt`, `decryption.key` and `encryption.key` files can be found in the API Key archive downloaded from the EFL Webapp; contact EFL Support for more information.

Example:

```
> php vendor/EFLGlobal/EWSClient/Demos/ScoresAPICommand.php 'https://uat-external.eflglobal.com/api/v1/scores/' '/path/to/identifier.txt' '/path/to/decryption.key' '/path/to/encryption.key'
```
