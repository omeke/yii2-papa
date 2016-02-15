<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ParseOtchet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parse Otchets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-otchet-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'parse_ksk_id',
            'name',
            'url_otchet:url',
            'posted_at',
            'updated_at',
        ],
    ]) ?>

</div>
