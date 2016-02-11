<?php

return [
    'id' => 'app-frontend',
    'name' => 'КСК-АУЕЗОВ',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'temp/default/index',
    'modules' => [
        'site' => [
            'class' => 'vova07\site\Module'
        ],
        'blogs' => [
            'controllerNamespace' => 'vova07\blogs\controllers\frontend'
        ],
        'temp' => [
            'class' => 'frontend\modules\temp\Temp',
        ]
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'sdi8s#fnj98jwiqiw;qfh!fjgh0d8f',
            'baseUrl' => ''
        ],
        'urlManager' => [
            'rules' => [
                '' => 'temp/default/index',
                '<_a:(about|contacts|captcha)>' => 'site/default/<_a>'
            ]
        ],
        'view' => [
            'theme' => 'frontend\themes\site\Theme'
        ],
        'errorHandler' => [
            'errorAction' => 'site/default/error'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning']
                ]
            ]
        ],
        'i18n'=>[
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => 'frontend/themes/site/messages',
                    'forceTranslation' => false,
                    'fileMap' => [
                        'site' => 'site.php',
                    ],
                ]
            ],
        ]
    ],
    'params' => require(__DIR__ . '/params.php')
];
