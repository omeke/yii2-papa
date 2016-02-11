<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\temp\models\ObjectInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="object-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'address_ksk') ?>

    <?= $form->field($model, 'amount_house') ?>

    <?= $form->field($model, 'address_house') ?>

    <?= $form->field($model, 'fullname_chairman') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'object_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
