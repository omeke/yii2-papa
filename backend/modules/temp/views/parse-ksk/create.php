<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ParseKsk */

$this->title = 'Create Parse Ksk';
$this->params['breadcrumbs'][] = ['label' => 'Parse Ksks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-ksk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
