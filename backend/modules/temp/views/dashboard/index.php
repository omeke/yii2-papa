<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $region \common\models\ParseRegion */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

$parse_regions = \common\models\ParseRegion::find()->all();
?>
<div class="group-index">
    <table class="table table-bordered table-hover">
        <tbody>
        <tr>
            <th>Район</th>
            <th>Кол-во кск</th>
            <th>Кол-во ссылок</th>
            <th>Кол-во отчетов</th>
            <th>Дата</th>
        </tr>
        <?php foreach ($parse_regions as $region): ?>
            <tr>
                <td><?= $region->name ?></td>
                <td><?= \common\models\ParseKsk::find()
                        ->where(['parse_region_id' => $region->id])
                        ->count() . ' '
//                    . Html::a(
//                        '<span class="fa fa-refresh"></span>',
//                        ['refresh-ksk', 'id' => $region->id],
//                        ['class' => 'pull-right'])
                    ?>
                </td>
                <td><?= \common\models\ParseKsk::find()
                        ->where(['parse_region_id' => $region->id])
                        ->andWhere(['IS NOT', 'url_otchet', null])
                        ->count() . ' '
//                    . Html::a(
//                        '<span class="fa fa-retweet"></span>',
//                        ['refresh-report-link', 'id' => $region->id],
//                        ['class' => 'pull-right', 'title' => 'link'])
                    ?>
                </td>
                <td><?= sizeof($region->parseOtchets) . ' '
//                    . Html::a(
//                        '<span class="fa fa-refresh"></span>',
//                        ['refresh-report', 'id' => $region->id],
//                        ['class' => 'pull-right'])
                    ?>
                </td>
                <td><?= \backend\helpers\DateHelper::format(strtotime($region->updated_at . ' +6 hours')) ?></td>
                <!--                <td>11-7-2014</td>-->
                <!--                <td><span class="label label-success">Approved</span></td>-->
                <!--                <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>-->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


</div>
