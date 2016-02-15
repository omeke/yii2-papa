<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ParseRegion */

$this->title = 'Update Parse Region: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parse Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parse-region-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
