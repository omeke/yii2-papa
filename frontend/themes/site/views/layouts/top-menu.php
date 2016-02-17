<?php

/**
 * Top menu view.
 *
 * @var \yii\web\View $this View
 */

use frontend\themes\site\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => isset($footer) ? 'pull-right' : 'nav navbar-nav navbar-right'
        ],
        'items' => [
            [
                'label' => 'Документы',
                'url' => ['/temp/default/index']
            ],
            [
                'label' => 'Контакты',
                'url' => ['/temp/default/contact']
            ],
            [
                'label' => 'Полезная информация',
                'url' => ['/temp/default/temp']
            ],
//            [
//                'label' => 'Лента новостей',
//                'url' => ['/blogs/default/index']
//            ],

//            [
//                'label' => Yii::t('site', 'Blogs'),
//                'url' => ['/blogs/default/index']
//            ],
//            [
//                'label' => Yii::t('site', 'Contacts'),
//                'url' => ['/site/default/contacts']
//            ],
//            [
//                'label' => Yii::t('site', 'Sign In'),
//                'url' => ['/users/guest/login'],
//                'visible' => Yii::$app->user->isGuest
//            ],
//            [
//                'label' => Yii::t('site', 'Sign Up'),
//                'url' => ['/users/guest/signup'],
//                'visible' => Yii::$app->user->isGuest
//            ],
//            [
//                'label' => Yii::t('site', 'Settings'),
//                'url' => '#',
//                'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown">{label} <i class="icon-angle-down"></i></a>',
//                'visible' => !Yii::$app->user->isGuest,
//                'items' => [
//                    [
//                        'label' => Yii::t('site', 'Edit profile'),
//                        'url' => ['/users/user/update']
//                    ],
//                    [
//                        'label' => Yii::t('site', 'Change email'),
//                        'url' => ['/users/user/email']
//                    ],
//                    [
//                        'label' => Yii::t('site', 'Change password'),
//                        'url' => ['/users/user/password']
//                    ]
//                ]
//            ],
//            [
//                'label' => Yii::t('site', 'Sign Out'),
//                'url' => ['/users/user/logout'],
//                'visible' => !Yii::$app->user->isGuest
//            ]
        ]
    ]
);