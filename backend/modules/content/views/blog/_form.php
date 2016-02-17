<?php

use common\models\Blog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget as Imperavi;

/* @var $this yii\web\View */
/* @var $model common\models\Blog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="blog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'short_text')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'minHeight' => 300,
                'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
                'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
                'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'text')->widget(
        Imperavi::className(),
        [
            'settings' => [
                'minHeight' => 300,
                'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
                'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
                'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
            ]
        ]
    ) ?>

    <?= $form->field($model, 'status')->dropDownList(Blog::statusNames()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
