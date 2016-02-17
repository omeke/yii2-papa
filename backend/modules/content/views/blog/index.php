<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\content\models\BlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Blogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Blog', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'short_text:ntext',
            'text:ntext',
            'views',
            // 'status',
            // 'published_at',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {on} {off}',
                'buttons' => [
                    'on' => function($url, $model) {
                        if ($model->status === \common\models\Blog::STATUS_OFF) {
                            return Html::a('<span class="glyphicon glyphicon-ok"></span>', $url);
                        }
                        return '';
                    },
                    'off' => function ($url, $model) {
                        if ($model->status === \common\models\Blog::STATUS_ON) {
                            return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url);
                        }
                        return '';
                    }
                ]
            ],
        ],
    ]); ?>
</div>
