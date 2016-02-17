<?php

/**
 * Sidebar menu layout.
 *
 * @var \yii\web\View $this View
 */

use backend\themes\admin\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
            [
                'label' => Yii::t('admin', 'Dashboard'),
                'url' => Yii::$app->homeUrl,
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl
            ],
            [
                'label' => Yii::t('admin', 'Guest'),
                'url' => '#',
                'icon' => 'fa-question',
                'visible' => Yii::$app->user->can('superadmin'),
                'active' => strpos(Yii::$app->requestedRoute, 'guest/') === 0,
                'items' => [
                    [
                        'label' => Yii::t('admin', 'Contact'),
                        'url' => ['/guest/leave-message/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/contact/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                ]
            ],
            [
                'label' => Yii::t('admin', 'Ksk'),
                'url' => '#',
                'icon' => 'fa-question',
                'visible' => Yii::$app->user->can('superadmin'),
                'active' => strpos(Yii::$app->requestedRoute, 'ksk/') === 0,
                'items' => [
                    [
                        'label' => Yii::t('admin', 'Groups'),
                        'url' => ['/ksk/group/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/group/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                    [
                        'label' => Yii::t('admin', 'Regions'),
                        'url' => ['/ksk/region/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/region/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                    [
                        'label' => Yii::t('admin', 'Objects'),
                        'url' => ['/ksk/object/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/object/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                    [
                        'label' => Yii::t('admin', 'Objects Info'),
                        'url' => ['/ksk/object-info/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/object-info/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ]
                ]
            ],
            [
                'label' => Yii::t('admin', 'Parse'),
                'url' => '#',
                'icon' => 'fa-question',
                'visible' => Yii::$app->user->can('superadmin'),
                'active' => strpos(Yii::$app->requestedRoute, 'parse/') === 0,
                'items' => [
                    [
                        'label' => Yii::t('admin', 'Regions'),
                        'url' => ['/parse/parse-region/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/parse-region/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                    [
                        'label' => Yii::t('admin', 'Ksk'),
                        'url' => ['/parse/parse-ksk/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/parse-ksk/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                    [
                        'label' => Yii::t('admin', 'Otchet'),
                        'url' => ['/parse/parse-otchet/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/parse-otchet/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ],
                    [
                        'label' => Yii::t('admin', 'File'),
                        'url' => ['/parse/parse-otchet-file/index'],
                        'active' => strpos(Yii::$app->requestedRoute, '/parse-otchet-file/') !== false,
                        'visible' => Yii::$app->user->can('superadmin'),
                    ]
                ]
            ],
            [
                'label' => Yii::t('admin', 'Users'),
                'url' => ['/users/default/index'],
                'icon' => 'fa-group',
                'visible' => Yii::$app->user->can('administrateUsers') || Yii::$app->user->can('BViewUsers'),
            ],
            [
                'label' => Yii::t('admin', 'Blogs'),
                'url' => ['/blogs/default/index'],
                'icon' => 'fa-book',
                'visible' => Yii::$app->user->can('administrateBlogs') || Yii::$app->user->can('BViewBlogs'),
            ],
            [
                'label' => Yii::t('admin', 'Comments'),
                'url' => ['/comments/default/index'],
                'icon' => 'fa-comments',
                'visible' => Yii::$app->user->can('administrateComments') || Yii::$app->user->can('BViewCommentsModels') || Yii::$app->user->can('BViewComments'),
                'items' => [
                    [
                        'label' => Yii::t('admin', 'Comments'),
                        'url' => ['/comments/default/index'],
                        'visible' => Yii::$app->user->can('administrateComments') || Yii::$app->user->can('BViewComments'),
                    ],
                    [
                        'label' => Yii::t('admin', 'Models management'),
                        'url' => ['/comments/models/index'],
                        'visible' => Yii::$app->user->can('administrateComments') || Yii::$app->user->can('BViewCommentsModels'),
                    ]
                ]
            ],
            [
                'label' => Yii::t('admin', 'Access control'),
                'url' => '#',
                'icon' => 'fa-gavel',
                'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles') || Yii::$app->user->can('BViewPermissions') || Yii::$app->user->can('BViewRules'),
                'items' => [
                    [
                        'label' => Yii::t('admin', 'Permissions'),
                        'url' => ['/rbac/permissions/index'],
                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewPermissions')
                    ],
                    [
                        'label' => Yii::t('admin', 'Roles'),
                        'url' => ['/rbac/roles/index'],
                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles')
                    ],
                    [
                        'label' => Yii::t('admin', 'Rules'),
                        'url' => ['/rbac/rules/index'],
                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRules')
                    ]
                ]
            ],
        ]
    ]
);