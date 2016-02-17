<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ParseOtchetFile */

$this->title = 'Update Parse Otchet File: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Parse Otchet Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="parse-otchet-file-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
