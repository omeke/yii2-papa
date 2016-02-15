<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\parse\models\ParseKskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Parse Ksks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parse-ksk-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Parse Ksk', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'parse_region_id',
                'value' => 'parseRegion.name',
                'filter' => ArrayHelper::map(\common\models\ParseRegion::find()->all(), 'id', 'name')
            ],
            'name',
            'url_ksk:url',
            'url_otchet:url',
            // 'color',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
