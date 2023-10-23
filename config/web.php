<?php
$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

$config = [
    'id'        => 'panelEscolar',
    'name'      => "AQUITIA",
    'version'   => '0.1.0',
    'language'  => 'es-MX',
    'timeZone'  => 'America/Mexico_City',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\components\Aliases'],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'crm' => [
            'class' => 'app\modules\crm\Module',
        ],
        'alumnos' => [
            'class' => 'app\modules\alumnos\Module',
        ],
        'calendario' => [
            'class' => 'app\modules\calendario\Module',
        ],
        'gestion' => [
            'class' => 'app\modules\gestion\Module',
        ],

        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'dVAwwe1I_3-eDS-grTA7CqUdyDM3Vxi3',

            /* IMPLEMENTACION PARA WEB SERVICE*/
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
            /**/
        ],    

'user' => [
    'identityClass' => 'app\models\user\UserIdentity',
    'enableAutoLogin' => true,
    'on afterLogin' => function ($event) {
        $user = $event->identity;
        $result = Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setSubject('Inicio de sesión exitoso')
            ->setTextBody('Se ha iniciado sesión en su cuenta en ' . Yii::$app->name . '.')
            ->send();

        if (!$result) {
            Yii::error('Error al enviar el correo después del inicio de sesión.', 'application');
        } else {
            echo 'Correo enviado exitosamente';
        }
    },
],


        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => [
                        'position' => \yii\web\View::POS_HEAD
                    ],
                    'js' => [
                        'jquery.min.js',
                    ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        'js/bootstrap.min.js',
                    ]
                ],
            ],
        ],
        'nifty'=> [
            'class' => 'app\components\NiftyComponent',
        ],
        'barcodegenerator'=> [
            'class' => 'app\components\BarcodeGeneratorComponent',
        ],

        'user' => [
            'identityClass' => 'app\models\user\UserIdentity',
            'enableAutoLogin' => true,
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'savePath' => '@app/runtime/session'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'formatter' => [
            'class'           => 'yii\i18n\Formatter',
            'dateFormat'      => 'php:Y-m-d',
            'datetimeFormat'  => 'php:Y-m-d h:i a',
            'timeFormat'      => 'php:h:i a',
            'defaultTimeZone' => 'America/Mexico_City',
            'locale'          => 'es-MX',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/translations',
                    'sourceLanguage' => 'es',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/translations',
                    'sourceLanguage' => 'es'
                ],
            ],
        ],



        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'imanolhuerta20012017@gmail.com',
                'password' => '1237894560',
                'port' => 587,
                'encryption' => 'tls',
            ],
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
            //'suffix' => '.html',
            'rules' => [
                '' => 'site/index',
                '<action:\w+>'=>'site/<action>',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => [
            '189.161.254.17',
            '127.0.0.1',
            '::1'
        ],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => [
            '127.0.0.1',
            '::1'
        ],
    ];
}

return $config;


