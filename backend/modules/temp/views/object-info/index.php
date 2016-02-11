<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\temp\models\ObjectInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Object Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="object-info-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Object Info', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'address_ksk',
            'amount_house',
            'address_house',
            'fullname_chairman',
            // 'phone',
            // 'object_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
