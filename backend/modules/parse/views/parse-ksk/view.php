<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ParseKsk */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parse Ksks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-ksk-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<!--        --><?//= Html::a('Обновить ссылки на отчет с "kskforum.kz"', ['refresh', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
<!--        --><?//= Html::a('Обновить отчеты с "kskforum.kz"', ['import-report', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
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
            [
                'attribute' => 'parse_region_id',
                'value' => $model->parseRegion->name
            ],
            'name',
            'url_ksk:url',
            'url_otchet:url',
            'color',
            [
                'attribute' => 'updated_at',
                'value' => \backend\helpers\DateHelper::format(strtotime($model->updated_at.' +6 hours'))
            ],
            [
                'label' => 'Кол-во отчетов',
                'value' => sizeof($model->parseOtchets)
            ]
        ],
    ]) ?>

</div>
