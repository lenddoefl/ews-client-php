# EFL Web Services PHP Client
This library provides a client that you can integrate into your PHP applications to connect to EFL Web Services, including Applicant Journey and Scores APIs.

## Installation
### Using Composer
To install this library using [Composer](https://getcomposer.org/), add the following to your project's `composer.json` file:

```json
"repositories": [
  {
    "url": "https://github.com/eflglobal/ews-client-php",
    "type": "git"
  }
],

"require": {
  "eflglobal/ewsclient" : "*"
}
```

Execute `php composer.phar install`, and you should be good to go.

In order to use EWS clients, ensure that you `require 'vendor/autoload.php'` in your application.  See <https://getcomposer.org/doc/01-basic-usage.md#autoloading> for more information.

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
require __DIR__ . '/vendor/autoload.php';

use EFLGlobal\EWSClient\AJAPIController;

$client = AJAPIController(
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
require __DIR__ . '/vendor/autoload.php';

use EFLGlobal\EWSClient\ScoresAPIController;

$client = ScoresAPIController(
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
### Demos
In Demos directory you can find demo files (`Demos/AJAPIDemo.php`, `Demos/ScoresAPIDemo.php`). They show how to access methods of both client classes.

### Command-Line Demos
You can also execute command-line based files in Demos folder (`Demos/AJAPICommand.php`, `Demos/ScoresAPICommand.php`) to try clients.

#### Applicant Journey API
To execute the Applicant Journey API Command-Line Demo, invoke the `Demos/AJAPICommand.php` script and provide the following arguments:

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
> php AJAPICommand.php 'https://uat-external.eflglobal.com/api/v2/applicant_journey/' '/path/to/identifier.txt' '/path/to/decryption.key' '/path/to/encryption.key' 'sdkExample'
```

#### Scores API
To execute the Scores API Command-Line Demo, invoke the `Demos/ScoresAPICommand.php` script and provide the following arguments:


1. URL path to API:
    - For testing: `'https://api-uat-external.eflglobal.com/api/v1/scores/'`
    - For production: `'https://api.eflglobal.com/api/v1/scores/'`
2. Absolute path to `identifier.txt` file.
3. Absolute path to `decryption.key` file.
4. Absolute path to `encryption.key` file.

The `identifier.txt`, `decryption.key` and `encryption.key` files can be found in the API Key archive downloaded from the EFL Webapp; contact EFL Support for more information.

Example:

```
> php ScoresAPICommand.php 'https://uat-external.eflglobal.com/api/v1/scores/' '/path/to/identifier.txt' '/path/to/decryption.key' '/path/to/encryption.key'
```
