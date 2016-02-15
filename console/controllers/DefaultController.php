<?php

namespace console\controllers;

use common\models\Group;
use common\models\Object;
use common\models\ParseKsk;
use common\models\ParseOtchet;
use common\models\ParseRegion;
use console\helpers\NokogiriHelper;
use yii\helpers\Json;
use \yii\console\Controller;

/**
 * Default controller for the `temp` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionChangeGroup()
    {
        $count = 0;
        foreach (Object::find()->all() as $object) {
            /** @var $object Object */

            $group = Group::find()->where(['name' => mb_substr($object->name, 0, 1)])->one();

            if ($group) {
                \Yii::$app->db->createCommand()->update('object', ['group_id' => $group->id], ['id' => $object->id])->execute();
                $count ++;
            }
        }
        echo "done - {$count}\n";
    }
}
