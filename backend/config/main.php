<?php

Yii::setAlias('backend', dirname(__DIR__));

return [
    'id' => 'app-backend',
    'name' => 'Yii2-Start',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'temp/dashboard/index',
    'modules' => [
        'temp' => [
            'class' => \backend\modules\temp\Temp::className(),
        ],
        'admin' => [
            'class' => 'vova07\admin\Module'
        ],
        'users' => [
            'controllerNamespace' => 'vova07\users\controllers\backend'
        ],
        'blogs' => [
            'isBackend' => true
        ],
        'comments' => [
            'isBackend' => true
        ],
        'rbac' => [
            'class' => 'vova07\rbac\Module',
            'isBackend' => true
        ]
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => '7fdsf%dbYd&djsb#sn0mlsfo(kj^kf98dfh',
            'baseUrl' => ''
        ],
        'urlManager' => [
            'rules' => [
                '' => 'temp/dashboard/index',
                '<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>'
            ]
        ],
        'view' => [
            'theme' => 'backend\themes\admin\Theme'
        ],
        'errorHandler' => [
            'errorAction' => 'admin/default/error'
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
                    'basePath' => 'backend/themes/site/messages',
                    'forceTranslation' => false,
                    'fileMap' => [
                        'admin' => 'admin.php',
                    ],
                ]
            ],
        ]
    ],
    'params' => require(__DIR__ . '/params.php')
];
