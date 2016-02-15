<?php

namespace frontend\modules\temp\controllers;

use common\helpers\KskObjectDataHelper;
use common\models\Group;
use common\models\LeaveMessage;
use common\models\Object;
use common\models\ObjectInfo;
use yii\helpers\Json;
use yii\web\Controller;

/**
 * Default controller for the `temp` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $data = [];
        foreach (Group::find()->all() as $group) {
            /* @var $group Group */
            $childs = [];
            foreach ($group->objects as $object) {
                /* @var $object Object */
                $childs[] = [
                    'title' => trim($object->name),
                    'folder' => true,
                    'expanded' => false,
                    'key' => 'o-' . $object->id,
                    'children' => [
                        ['title' => 'Информация', 'key' => 'i-' . $object->id],
                        ['title' => 'Документы', 'key' => 'd-' . $object->id]
                    ]
                ];

            }
            $data[] = [
                'title' => $group->name,
                'folder' => true,
                'expanded' => true,
                'key' => 'g-' . $group->id,
                'children' => $childs
            ];
        }


        return $this->render('index', [
            'data' => KskObjectDataHelper::getData()
//            'data' => $data
        ]);
    }

    public function actionInfo() {
        /* @var $model ObjectInfo*/
        if (($model = ObjectInfo::findOne(\Yii::$app->request->post('id'))) !== null) {
            return Json::encode([
                'success' => true,
                'data' => $model->getAttributes()
            ]);
        }
        return Json::encode([
            'success' => false
        ]);
    }

    public function actionDoc() {
        /* @var $model Object*/
        if (($model = Object::findOne(\Yii::$app->request->post('id'))) !== null) {
            $files = [];
            foreach ($model->files as $file) {
                $files[] = $file->hash . '.' . $file->type;
            }
            return Json::encode([
                'success' => true,
                'data' => $files
            ]);
        }
        return Json::encode([
            'success' => false
        ]);
    }

    public function actionContact()
    {
        $model = new LeaveMessage();
        $model->status = LeaveMessage::STATUS_NEW;

        if ($model->load(\Yii::$app->request->post())) {
            $model->user_ip = \Yii::$app->request->userIP;
            if ($model->save()) {
                \Yii::$app->session->setFlash('info', 'В скором времени вам ответят');
            }
        }

        return $this->render('contact', [
            'model' => $model
        ]);
    }
}
