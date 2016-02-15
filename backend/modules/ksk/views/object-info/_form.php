<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObjectInfo */
/* @var $form yii\widgets\ActiveForm */

$object_list = \common\models\Object::find()->all();
?>

<div class="object-info-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address_ksk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount_house')->textInput() ?>

    <?= $form->field($model, 'address_house')->textInput() ?>

    <?= $form->field($model, 'fullname_chairman')->textInput() ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'object_id')->dropDownList(
        \yii\helpers\ArrayHelper::map($object_list, 'id', 'name'),
        ['prompt' => '(не установлен)']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
