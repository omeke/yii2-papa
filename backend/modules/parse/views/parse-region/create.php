<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ParseRegion */

$this->title = 'Create Parse Region';
$this->params['breadcrumbs'][] = ['label' => 'Parse Regions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-region-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
