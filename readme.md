# EFL Web Services PHP Client

Please, read this instruction to try this project.\
AJAPIDemo.php and ScoresAPIDemo.php are executable files that can show you how developers can interact with this client.

## Installation

You can just copy files from the repository 

**OR**

To install this library you can add following text to your project's **composer.json**.
```
"repositories":
 [
   {
       "url": "https:*username*:*password*@github.com/eflglobal/ews-client-php",
       "type": "git"
   }
 ],
"require":
{
   "eflglobal/ews-client-php" : "*"
}

```

## How to use

**AJAPIController** and **ScoresAPIController** classes provide simple access to ApplicantJourney and Scores API's.
In order to use them you need to include corresponding file in your document and then call an instance of AJAPIController and ScoresAPIController class.
Pay attention that both classes take place in **EFLGlobal\EWSPHPClient** namespace.

Class exemplar demands four arguments:
+ URL path to API;
+ path to identifier file;
+ path to decryption key file;
+ path to encryption key file.

After that you can call methods to work with API.

Every endpoint of API is represented by method called like callEndpointName. For example for login you should use callLogin.\
It is also possible to get variables using method getVariableName, or set some of them using setVariableName.

It is strongly recommended to call callLogin method before others as it creates keys needed to get access to other endpoints.
As a general rule, calling most methods you have to provide array with data you want to send. In some cases, if data wasn't provided it can be set automatically. 
Details of this behavior will be explained below. 

As a result you will get pure JSON from API endpoint. Also instance will receive several properties you can access directly through getters.

## EWSPMain class 

This class provides methods you can use with both child-classes AJAPIController and ScoresAPIController.
This is an abstract class.

Variables:
+ $identifier;
+ $decryptionKey;
+ $encryptionKey;
+ $url;
+ $authToken64;
+ $reqToken64;

To set variables class uses following public methods:
+ setIdentifier ($identifier)
+ setDecryptionKey ($decryptionKey)
+ setEncryptionKey ($encryptionKey)

To get variables class uses following public methods:
+ setURL ($url)
+ getIdentifier ()
+ getDecryptionKey ()
+ getEncryptionKey ()
+ getURL () 

Constructor of this class receives 4 arguments: $url , $identifier, $decryptionKey, $encryptionKey.

### Main methods

Under the hood class uses protected static function *sendRequest* which receives url and post information ans sends it to API.
Users do not normally have access to this method.

To process received tokens class uses protected function *encoderDecoder* which receives information from callLogin mehtod, process tokens and stores them into variables.

To handle errors class uses protected static function *handleError*. 
It was encapsulated in case of further development by third party to provide a better way for handling errors.

##### Public method callLogin ()
Connects to login endpoint of both APIs.\
Method also calls protected method encoderDecoder.\
Needs no arguments.

## Class AJAPIController 

This class extends EWSPMain. You can use it to connect to ApplicantJourney API.

Variables:
+ $applicationHash;
+ $publicKey;
+ $uid;
+ $sequence;
+ $attachmentUids;

To get variables class uses following public methods:
+ getUid()
+ getPublicKey()
+ getAttachmentUids()
+ getApplicationHash()

### Main methods

These methods need to be provided with single argument - PHP array representing data (*i.e. in API's documentation's "data" value*) which will be sent to API.
For callFinishSession method data is not necessary.
Methods can take data from stored variables and, after receiving a valid response, also store it. In table below you will find all useful information about what wariables are loaded, stored by methods and what arguments they receive.

##### callStartSession($data)
Method connects to startSession endpoint and returns JSON answer.\
It also stores uid, public key, application hash in class instance and set sequence to 0.\
As an argument takes a PHP array of data to send on server. Method can take stored application hash if one provided.\
Pay attention, that before calling this session you should call to login endpoint ot get reqToken.

##### callFinishSession([$data])
Method connects to finishSession endpoint and returns JSON answer.\
It may take a PHP array of data to send on server.\
If not provided, data is taken from stored $uid and $sequence variables.\
Pay attention, that before calling this session you should call to login endpoint ot get new reqToken.

##### callPrefetchApplications($data)
Method connects to prefetchApplications endpoint and returns JSON answer.\
As an argument takes a PHP array of data to send on server.

##### callResumeSession($data)
Method connects to resumeSession endpoint and returns JSON answer.\
It also stores uid and public key in class instance.\
As an argument takes a PHP array of data to send on server.\
If not provided, uid is taken from stored $uid variables.

##### callGetApplication($data)
Method connects to getApplication endpoint and returns JSON answer.\
It also stores application hash in class instance.\
As an argument takes a PHP array of data to send on server.\
If not provided, application hash and uid are taken from stored variables.

##### callFinishStep($data)
Method connects to finishStep endpoint and returns JSON answer.\
As an argument takes a PHP array of data to send on server.\
If not provided, sequence and uid are taken from stored variables.

##### callCreateAttachment($data)
Method connects to createAttachment endpoint and returns JSON answer.\
It also stores attachment uid in class instance.\
As an argument takes a PHP array of data to send on server.\
If not provided, uid is taken from stored variables.

| Method                   | Arguments              | Loads                         | Saves                                                                                     |
|--------------------------|------------------------|-------------------------------|-------------------------------------------------------------------------------------------|
| callStartSession         | array $data            | applicationHash (if provided) | Sets sequence to  0 ; sets attachmentUids to empty array; applicationHash, publicKey, uid |
| callFinishSession        | array $data (optional) | uid, sequence                 |                                                                                           |
| callCreateAttachment     | array $data            | uid                           | Pushes attachmentUid in attachmentUids array                                              |
| callFinishStep           | array $data            | uid, sequence                 | Adds 1 do sequence                                                                        |
| callGetApplication       | array $data            | uid, applicationHash          | applicationHash                                                                           |
| callResumeSession        | array $data            | uid                           | publicKey, uid                                                                            |
| callPrefetchApplications | array $data            |                               |                                                                                           |


## Class ScoresAPIController 

This class extends EWSPMain. You can use it to connect to Scores API.

### Methods

##### public function callDateQuery ($date)

Method connects to subject endpoint and returns JSON answer.\
As an argument takes a PHP array representing information about dateQuery (*i.e. in API's documentation's "dateQuery" value*).

##### public function callSubject ($subject)

Method connects to subject endpoint and returns JSON answer.\
As an argument takes a PHP array representing information about subjects (*i.e. in API's documentation's "subjects" value*).

## Testing

Both PHP clients are provided with automated tests.\
You can find them in Tests folder.

These are phpunit tests and must be started via phpunit.\
Basically there are two ways to execute tests. Normally you can provide an additional argument in command line while starting the test (so it is parsed by PHP as $argv[2]).\
This argument must be an absolute path to one text file. This file must contain 4 lines - arguments for constructor of AJAPIController or ScoresAPIController class.

For example, you store your keys and identifier in /absolute/path/to/your/project/TestKeys/ApplicantJourney. 
Your text file is /absolute/path/to/your/project/TestingData/environment.txt. (You cau use any extension of text file as long as it is parsed normally by PHP).

Then your environment.txt file is:
```
https://uat-external.eflglobal.com/api/v2/applicant_journey/
/absolute/path/to/your/project/TestKeys/ApplicantJourney/identifier.txt
/absolute/path/to/your/project/TestKeys/ApplicantJourney/decryption.key
/absolute/path/to/your/project/TestKeys/ApplicantJourney/encryption.key
```

Your command line code is something like:

> phpunit /absolute/path/to/ews-client-php/Tests/AJAPITests.php /absolute/path/to/your/project/TestingData/environment.txt

Another way, and **it is not recommended**, you can store keys in TestKeys/ApplicantJourney subdirectory in directory of ews-client-php.\
In that case you don't need provide additional argument in command line.

***Pay attention that Scores API may return "Too many requests" error in case of frequent requests.***

## Additional features

### Demos

In Demos directory you can find demo files. They show how to access methods of both client classes.\
Demos works exactly like tests, except they call every key method once and, once executed in browser, will return human-friendly page.

### Command line demos

You can also execute files in CommandLine folder to try clients. You can do it two ways:
+ Use first method we described for testing. In case of AJAPI you need to add 5-th line - application name.
+ Set arguments right in command line. There must be from  4 to 5 arguments:
  - URL path to API;
  - path to identifier file;
  - path to decryption key file;
  - path to encryption key file;
  - in case of AJAPI you need to provide 5-th argument - application name.
  
Your command line will be something like this:

> php AJAPICommand.php 'https://uat-external.eflglobal.com/api/v2/applicant_journey/' '/absolute/path/to/your/keys/identifier.txt' '/absolute/path/to/your/keys/decryption.key' '/absolute/path/to/your/keys/encryption.key' 'sdkExample'

or this:

> php ScoresAPICommand.php 'https://uat-external.eflglobal.com/api/v1/scores/' '/absolute/path/to/your/keys/identifier.txt' '/absolute/path/to/your/keys/decryption.key' '/absolute/path/to/your/keys/encryption.key'

