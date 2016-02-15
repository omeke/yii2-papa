<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\parse\models\ParseKskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parse-ksk-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parse_region_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'url_ksk') ?>

    <?= $form->field($model, 'url_otchet') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
