<?php

use common\models\LeaveMessage;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\guest\models\LeaveMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Leave Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-message-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Leave Message', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'email:email',
            'message:ntext',
            [
                'format' => 'html',
                'attribute' => 'status',
                'value' => function($model) {
                    return Html::tag('span', LeaveMessage::statusNames()[$model->status], [
                        'class' => 'label label-'.LeaveMessage::statusClasses()[$model->status]
                    ]);
                },
                'filter' => LeaveMessage::statusNames()
            ],
            // 'user_ip',
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
