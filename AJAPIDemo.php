<?php
require_once 'AJAPIController.php';

/**
 * This file calls most of ApplicantJourneyController class methods in order to show users how the code works.
 */

if (isset($argv[1])) {
    $file = fopen($argv[1], 'r');
    $file = fread($file, 10485760);
    $arguments = explode(PHP_EOL, $file);
}
else {
    $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
        'TestKeys/ApplicantJourney/identifier.txt',
        'TestKeys/ApplicantJourney/decryption.key',
        'TestKeys/ApplicantJourney/encryption.key'];
}

$requestApplicantJourney = new EFLGlobal\EWSPHPClient\AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

echo "CallLogin method returns: <br>";
echo $requestApplicantJourney->callLogin();

echo "<br><br>CallPrefetchApplications method returns: <br>";
echo $requestApplicantJourney->callPrefetchApplications(
    ["applications" => ["sdkExample"=>   "64a9354b-1014-1698-330e-721b75a109bb#1.20.0.0"]]);

//We provide data needed to start session.
$data = [
    "applicant"=> [
          "birthday"=>         '11.11.11',
          "email"=>            'test@test.test',
          "employmentStatus"=> 'intern',
          "firstName"=>       'bob',
          "gender"=>           'm',
          "lastName"=>         'dylan',
          "maritalStatus"=>    'single',
          "loan"=>            [
                                "amount"=>         15000,
                                "businessIncome"=> 15000,
                                "currency"=>       'PEN',
                                "personalIncome"=> 12000,
                                "term"=>           2.17
                              ],
          "locale"=>           'en',
          "utcOffset"=>        '2',

          "addresses"=> [
            "business"=> [
                          "city"=>       'asdf',
                          "country"=>    'USA',
                          "latitude"=>   12,
                          "longitude"=>  12,
                          "street"=>     'qwe',
                          "postalCode"=> 'qwe',
                          "region"=>     'qwer'
                         ],
            "home"=>     [
                            "city"=>       'asdf',
                            "country"=>    'USA',
                            "latitude"=>   12,
                            "longitude"=>  12,
                            "street"=>     'qwe',
                            "postalCode"=> 'qwe',
                            "region"=>     'qwer'
                         ],
            "work"=>     [
                            "city"=>       'asdf',
                            "country"=>    'USA',
                            "latitude"=>   12,
                            "longitude"=>  12,
                            "street"=>     'qwe',
                            "postalCode"=> 'qwe',
                            "region"=>     'qwer'
                         ],
          ],

          "connections"=> [
            "facebook"=>   true,
            "google"=>     true,
            "linkedin"=>   true,
            "microsoft"=>  true,
            "twitter"=>    true,
            "yahoo"=>      true
          ],

          "idNumbers"=> [
            "analyticsId"=>        '123',
            "bankAccountNumber"=>  '1234123413414',
            "driversLicense"=>     '1234',
            "externalKey"=>        '12341234',
            "nationalId"=>         '1234123',
            "passport"=>           '12341234134',
            "phoneNumber"=>        '12341234',
            "voterId"=>            '1234124'
          ]
    ],
    "application"=>   "sdkExample"
];

echo "<br><br>CallStartSession method returns: <br>";
echo $requestApplicantJourney->callStartSession($data);

//We update $data so it is now contains minimal data to resume session.
unset($data["application"]);

echo "<br><br>CallResumeSession method returns: <br>";
//We take new reqToken to resume session.
$requestApplicantJourney->callLogin();
echo $requestApplicantJourney->callResumeSession($data);

//We provide data needed to getApplication endpoint.
$data = [
    "device"=> [
        "browser"=> null,
        "deviceId"=> null,
        "ipAddress"=> null,
        "os"=> [
            "type"=> null,
            "version"=> null,
        ],
        "referrer"=> null,

        "viewport"=> [
            "height"=> null,
            "width"=> null
          ]
],
    "player"=> [
        "type"=>    "web-embedded",
        "version"=> "1.20"
    ]
  ];

echo "<br><br>CallGetApplication method returns: <br>";
echo $requestApplicantJourney->callGetApplication($data);

$data = [
    "applicant"=>    [
        "birthday"=>         '11.11.11',
        "email"=>            'test@test.test',
        "employmentStatus"=> 'intern',
        "firstName"=>       'bob',
        "gender"=>           'm',
        "lastName"=>         'dylan',
        "maritalStatus"=>    'single',
        "loan"=>            [
            "amount"=>         15000,
            "businessIncome"=> 15000,
            "currency"=>       'PEN',
            "personalIncome"=> 12000,
            "term"=>           2.17
        ],
        "locale"=>           'en',
        "utcOffset"=>        '2',

        "addresses"=> [
            "business"=> [
                "city"=>       'asdf',
                "country"=>    'USA',
                "latitude"=>   12,
                "longitude"=>  12,
                "street"=>     'qwe',
                "postalCode"=> 'qwe',
                "region"=>     'qwer'
            ],
            "home"=>     [
                "city"=>       'asdf',
                "country"=>    'USA',
                "latitude"=>   12,
                "longitude"=>  12,
                "street"=>     'qwe',
                "postalCode"=> 'qwe',
                "region"=>     'qwer'
            ],
            "work"=>     [
                "city"=>       'asdf',
                "country"=>    'USA',
                "latitude"=>   12,
                "longitude"=>  12,
                "street"=>     'qwe',
                "postalCode"=> 'qwe',
                "region"=>     'qwer'
            ],
        ],

        "connections"=> [
            "facebook"=>   true,
            "google"=>     true,
            "linkedin"=>   true,
            "microsoft"=>  true,
            "twitter"=>    true,
            "yahoo"=>      true
        ],

        "idNumbers"=> [
            "analyticsId"=>        '123',
            "bankAccountNumber"=>  '1234123413414',
            "driversLicense"=>     '1234',
            "externalKey"=>        '12341234',
            "nationalId"=>         '1234123',
            "passport"=>           '12341234134',
            "phoneNumber"=>        '12341234',
            "voterId"=>            '1234124'
        ]
    ],
    "device"=>       [
        "browser"=> null,
        "deviceId"=> null,
        "ipAddress"=> null,
        "os"=> [
            "type"=> null,
            "version"=> null,
        ],
        "referrer"=> null,

        "viewport"=> [
            "height"=> null,
            "width"=> null
        ]
    ],
    "metas"=>        new stdClass,
    "observations"=> new stdClass,
    "state"=>        new stdClass,
    "step"=>         'abGlobal',
];

echo "<br><br>CallFinishStep method returns: <br>";
echo $requestApplicantJourney->callFinishStep($data);

$filePicture = fopen("TestingData/test.jpeg", 'r');
$file = fread($filePicture, 10485760);
$fileSize = filesize("TestingData/test.jpeg");
$sha1 = sha1($file);
$file = base64_encode($file);
$data = [
      "attachmentType"=>         'photo',
      "attachmentTypeVersion"=>  '1.0',
      "contentType"=>            'image/jpeg',
      "inlineData"=>             $file,
      "name"=>                   'test',
      "sha1Hash"=>               $sha1,
      "size"=>                   $fileSize,
];

echo "<br><br>CallCreateAttachment method returns: <br>";
echo $requestApplicantJourney->callCreateAttachment($data);

echo "<br><br>GetUid method returns: <br>";
echo $requestApplicantJourney->getUid();

echo "<br><br>GetPublicKey method returns: <br>";
echo $requestApplicantJourney->getPublicKey();

echo "<br><br>GetApplicationHash method returns: <br>";
echo $requestApplicantJourney->getApplicationHash();

echo "<br><br>GetAttachmentUids method returns: <br>";
print_r($requestApplicantJourney->getAttachmentUids());

echo "<br><br>CallFinishSession method returns: <br>";
echo $requestApplicantJourney->callFinishSession();