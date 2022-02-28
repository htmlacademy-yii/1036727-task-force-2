<?php

$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@avatars' => 'uploads/avatars',
        '@files' => 'uploads/files',
    ],
    'components' => [
        'geocoder' => [
            'class' => 'app\components\GeocoderClient'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'M48085L3F3Oy7cV4U01mWd1We7OtSiiG',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\UserIdentity',
            'enableAutoLogin' => false,
            'loginUrl' => ['landing/index'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                '/' => 'landing/index',
                'geoapi/<geocode>' => 'api/geocoder',
                'login' => 'user/login',
                'logout' => 'user/logout',
                'reply/accept/<reply_id:\d+>' => 'reply/accept',
                'reply/create' => 'reply/create',
                'reply/refuse/<reply_id:\d+>' => 'reply/refuse',
                'signup' => 'user/signup',
                'tasks' => 'tasks/index',
                'tasks/add' => 'tasks/create',
                'tasks/<category:(?!view)>' => 'tasks/index',
                'tasks/cancel/<task_id:\d+>' => 'tasks/cancel',
                'tasks/complete' => 'tasks/complete',
                'tasks/refuse/<task_id:\d+>' => 'tasks/refuse',
                'tasks/view/<id:\d+>' => 'tasks/view',
                'user/view/<id:\d+>' => 'profile/view',
            ],
        ],
    ],
    'modules' => [
        'test' => [
            'class' => 'app\modules\test\Test',
        ]
    ],
    'params' => $params,
    'language' => 'ru',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
