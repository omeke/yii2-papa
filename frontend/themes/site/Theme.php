<?php

namespace frontend\themes\site;

use Yii;

/**
 * Class Theme
 * @package frontend\themes\admin
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@frontend/views' => '@frontend/themes/site/views',
        '@frontend/modules' => '@frontend/themes/site/modules'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
            'sourcePath' => '@frontend/themes/site/assets',
            'css' => [
                'css/bootstrap.min.css'
            ]
        ];
        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
            'sourcePath' => '@frontend/themes/site/assets',
            'js' => [
                'js/bootstrap.min.js'
            ]
        ];
    }
}
