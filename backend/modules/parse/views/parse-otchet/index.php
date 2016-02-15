<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\parse\models\ParseOtchetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Parse Otchets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-otchet-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Parse Otchet', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'region_id',
                'value' => 'parseKsk.parseRegion.name',
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\ParseRegion::find()->all(), 'id', 'name')
            ],
            [
                'attribute' => 'parse_ksk_id',
                'value' => 'parseKsk.name',
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\ParseKsk::find()->all(), 'id', 'name')
            ],
            'name',
            'url_otchet:url',
            'posted_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
