<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ParseOtchet */

$this->title = 'Create Parse Otchet';
$this->params['breadcrumbs'][] = ['label' => 'Parse Otchets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-otchet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
