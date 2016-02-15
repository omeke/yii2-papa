<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\LeaveMessage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Leave Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-message-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Пометить как помечанный', ['mark', 'id' => $model->id, 'status' => \common\models\LeaveMessage::STATUS_MARKED], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Пометить как закрытый', ['mark', 'id' => $model->id, 'status' => \common\models\LeaveMessage::STATUS_CLOSED], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'email:email',
            'message:ntext',
            [
                'attribute' => 'status',
                'value' => \common\models\LeaveMessage::statusNames()[$model->status]
            ],
            'user_ip',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
