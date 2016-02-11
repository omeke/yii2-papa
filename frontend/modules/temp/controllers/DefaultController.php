<?php

namespace frontend\modules\temp\controllers;

use common\models\Group;
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
//        $data[] = [
//            'key' => 1002,
//            'title' => 'sample',
//            'folder' => true,
//            'expanded' => false,
//            'children' => [
//                'key' => 1001,
//                'title' => 'sub_sample',
//                'folder' => true,
//                'active' => false,
//                'expanded' => false,
//                'children' => [
//                    ['title' => 'item1', 'key' => 1000],
//                    ['title' => 'item2', 'key' => 2000]
//                ]
//            ]
//        ];
        foreach (Group::find()->all() as $group) {
            /* @var $group Group */
            $childs = [];
            foreach ($group->objects as $object) {
                /* @var $object Object */
                $first_let = mb_substr(trim($object->name), 0, 1);
                $first_let !== false ?: $first_let = '#';

//                if (!isset($childs[$first_let])) {
//                    $childs[$first_let] = [
//                        'title' => $first_let,
//                        'folder' => true,
//                        'expanded' => false,
//                        'children' => []
//                    ];
//                }
                $childs[] = [
//                $childs[$first_let]['children'][] = [
//                    'title' => $object->name, 'key' => $object->id
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
            'data' => $data
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
}
