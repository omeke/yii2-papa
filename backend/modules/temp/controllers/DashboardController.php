<?php

namespace backend\modules\temp\controllers;

use Yii;
use common\models\Group;
use backend\modules\temp\models\GroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class DashboardController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRefreshKsk($id)
    {
        return $this->redirect([
            '/temp/parse-region/import',
            'id' => $id,
            'from' => 'dashboard'
        ]);
    }

    public function actionRefreshReportLink($id)
    {
        return $this->redirect([
            '/temp/parse-region/refresh',
            'id' => $id,
            'from' => 'dashboard'
        ]);
    }

    public function actionRefreshReport($id)
    {
        return $this->redirect([
            '/temp/parse-region/import-report',
            'id' => $id,
            'offset' => 0,
            'limit' => 50,
            'from' => 'dashboard'
        ]);
    }
}
