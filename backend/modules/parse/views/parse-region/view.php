<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ParseRegion */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parse Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-region-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?//= Html::a('Импорт списк кск с "kskforum.kz"', ['import', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
<!--        --><?//= Html::a('Обновить ссылки на отчет с "kskforum.kz"', ['refresh', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
<!--        --><?//= Html::a('Обновить отчеты с "kskforum.kz"', ['import-report', 'id' => $model->id, 'offset' => 0, 'limit' => 50], ['class' => 'btn btn-warning']) ?>
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
            'name',
            'url:url',
            [
                'label' => 'Количество кск',
                'value' => sizeof($model->parseKsks)
            ],
            [
                'label' => 'updated_at',
                'value' => strtotime($model->updated_at)
            ]
        ],
    ]) ?>

</div>
