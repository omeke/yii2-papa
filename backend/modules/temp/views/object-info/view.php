<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ObjectInfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Object Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="object-info-view">

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
            'address_ksk',
            'amount_house',
            'address_house',
            'fullname_chairman',
            'phone',
            'object_id',
        ],
    ]) ?>

</div>
