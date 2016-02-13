<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ParseKsk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parse-ksk-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parse_region_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_ksk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_otchet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
