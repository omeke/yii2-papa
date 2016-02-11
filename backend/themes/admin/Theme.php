<?php

namespace backend\themes\admin;

use Yii;

/**
 * Class Theme
 * @package backend\themes\admin
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@backend/views' => '@backend/themes/admin/views',
        '@backend/modules' => '@backend/themes/admin/modules'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
            'sourcePath' => '@backend/themes/admin/assets',
            'css' => [
                'css/bootstrap.min.css'
            ]
        ];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
            'sourcePath' => '@backend/themes/admin/assets',
            'js' => [
                'js/bootstrap.min.js'
            ]
        ];
        Yii::$container->set('yii\grid\CheckboxColumn', [
            'checkboxOptions' => [
                'class' => 'simple'
            ]
        ]);
    }
}
