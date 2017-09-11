<?php
require_once __DIR__.'/../AJAPIController.php';

if (isset($argv[1])) {
    $file = fopen($argv[1], 'r');
    $file = fread($file, 10485760);
    $arguments = explode(PHP_EOL, $file);
}
else {
    $arguments = ['https://uat-external.eflglobal.com/api/v2/applicant_journey/',
        '../TestKeys/ApplicantJourney/identifier.txt',
        '../TestKeys/ApplicantJourney/decryption.key',
        '../TestKeys/ApplicantJourney/encryption.key'];
}

$requestApplicantJourney = new EFLGlobal\EWSClient\AJAPIController($arguments[0], $arguments[1], $arguments[2], $arguments[3]);

echo "CallPrefetchApplications method returns: <br>";
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

$data = [
    "attachmentType"=>         'photo',
    "attachmentTypeVersion"=>  '1.0',
    "contentType"=>            'image/jpeg',
    "inlineData"=>             "/9j/4AAQSkZJRgABAQEASABIAAD//gATQ3JlYXRlZCB3aXRoIEdJTVD/2wBDAAEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB
            AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wgARCAAoACgDAREAAhEBAxEB/8QAGgAAAgMBAQAAAAAAAAAAAAAABgkD
            CAoABf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAU7gQEAYjUhOJ5QflxR/hkYDMs2aDC4ZiNLSj9y6xKZNh2ow4mOP/8QA
            HhAAAQUBAAMBAAAAAAAAAAAAAgEDBAUGBwARExD/2gAIAQEAAQUCl2DryqSmbRkCVkl/7c6o5Nwsv0PkcVIo8NXSxOCfs5HPsUzTRPkT5x6/
            0mJycq2m4DBRqmIACAx2CUsdl5VxL53zeHTRhEQHzJ5B6wc5pztqtAAQA/P/xAAUEQEAAAAAAAAAAAAAAAAAAABA/9oACAEDAQE/AU//xAAU
            EQEAAAAAAAAAAAAAAAAAAABA/9oACAECAQE/AU//xAAoEAABAwIFBAEFAAAAAAAAAAABAAIDESEQEhMiMQRBYfCxMkJx4fH/2gAIAQEABj8C
            c5zjc/KurJmWtaj5UGowlu1UGAAChOkTuHZRF0YD8ra28YCgUTRC5wzD7VDJLEM+Vp48e+8hrRQBbR399sootMnM4D6f0oeomiGpQHj+eUGt
            FAMIxpk1I7c8KLqJod1jdv5TWCwaKY//xAAkEAEAAgIBAgcBAQAAAAAAAAABESExQQBRYRBxgZGhwfDhsf/aAAgBAQABPyF9ZmZdrzCZ3p1E
            hMOqc5Nbx9ctCF/Dq5986jgvK1cp2PSs+nWw1FU1N6l+COI5bCdpiKjy+K4P9/4V/fiZp01+v1chPfkZMyy4Ra+n1d8Hrkw9U4L+8TMpEtk5
            0E9+n+PuN6Xp0lvC/FdNcU5dyIHuXO+k9uGgXqS4UUYKXdmL4BAAAO3ASisK1M/ocNHDUfJEuIL0GWptGJpOHN3WBmYorgMkXw0AoA8IS4bp
            LSiWNYYF6IJAHSnzE/pvhYgACivH/9oADAMBAAIAAwAAABAAAQAACQASCCAf/8QAFBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAwEBPxBP/8QA
            FBEBAAAAAAAAAAAAAAAAAAAAQP/aAAgBAgEBPxBP/8QAHRABAQEBAQEAAwEAAAAAAAAAAREhMQBBEFGh8P/aAAgBAQABPxB+MmZqVFUzAgYC
            l4KnVmdUfpSAZYpXWHleCAAQBon1gsWCxJpY7Q1ALwVAIhJBAeFq2664xVSAqgGgMPCQUIFT/QBVVTGEPVQVVSmsaUUVCSV+mPHT2XqVjO3Y
            MFVDUFe3UsmpZR0MswGnqHnAbQI4KFMA2PBViMVaQr8ukOqHd8qLOsCmhihQVe5Sr5eNb0gkcgVTvwBynfJQGCWgQA0gp8gGciYIfz/d9BuA
            hUNKU1AU0CLgrwvxREEKEVIWgPF6kCQXggzAMUgJhlDAAEOfjKkDCLTiY3QcAPFiHjYCpodxydCgEjLcAAAAAGcCHPz/AP/Z",
    "name"=>                   'test',
    "sha1Hash"=>               "91e408d7897162c9f0946aab6bc4a066d75ae6ea",
    "size"=>                   1266,
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