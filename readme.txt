ApplicantJourneyController and ScoresController classes provide simple access to ApplicantJourney and Scores API's.
In order to use them you need to include corresponding file in your document and then call an instance of ApplicantJourneyController or ScoresController class.
Pay attention that both classes take place in EWSPHPClient namespace.

Class exemplar demands four arguments:
    URL;
    path to identifier file;
    path to decryption key file;
    path to encryption key file.

After that you can call methods to work with API.
Ever endpoint of API is represented by method called like callEndpointName. For example for login you should use callLogin.
It is strongly recommended to call callLogin method before others as it creates keys needed to get access to other endpoints.
As a general rule, calling every method except callLogin you must provide array with data you want to send. In some cases if data wasn't provided it can be set automatically (check description of methods).

As a result you will get pure JSON from API endpoint. Also instance will receive several properties you can access directly through getters. FFor more information check method description.
You can also overwrite provided to class constructor arguments by getters.

##################
# EWSPMain class #
##################

This class provides methods you can use with both child-classes.
This is an abstract class.

PUBLIC METHODS

___________________________
setIdentifier ($identifier)
Sets new path to identifier file. Needs one argument.

_________________________________
setDecryptionKey ($decryptionKey)
Sets new path to decryption key file. Needs one argument.

_________________________________
setEncryptionKey ($encryptionKey)
Sets new path to encryption key file. Needs one argument.

_____________
setURL ($url)
Sets new URL. Needs one argument.

________________
getIdentifier ()
Returns path to identifier file. Needs no arguments.

___________________
getDecryptionKey ()
Returns path to decryption key file. Needs no arguments.

___________________
getEncryptionKey ()
Returns path to encryption key file. Needs no arguments.

_________
getURL ()
Returns URL. Needs no arguments.

___________
callLogin()
Connects to login endpoint of both API.
Method also calls protected method encoderDecoder which process received tokens and stores them into the instance.
Needs no arguments.

###############
# Development #
###############

To call test of command line feature:

php -f ApplicantJourneyCMD.php 'https://uat-external.eflglobal.com/api/v2/applicant_journey/' 'TestKeys/ApplicantJourney/identifier.txt' 'TestKeys/ApplicantJourney/decryption.key' 'TestKeys/ApplicantJourney/encryption.key' 'sdkExample'