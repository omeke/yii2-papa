<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ParseOtchetFile */

$this->title = 'Create Parse Otchet File';
$this->params['breadcrumbs'][] = ['label' => 'Parse Otchet Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-otchet-file-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
