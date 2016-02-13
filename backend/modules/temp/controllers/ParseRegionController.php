<?php

namespace backend\modules\temp\controllers;

use common\models\ParseKsk;
use Yii;
use common\models\ParseRegion;
use backend\modules\temp\models\ParseRegionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParseRegionController implements the CRUD actions for ParseRegion model.
 */
class ParseRegionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ParseRegion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParseRegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ParseRegion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ParseRegion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ParseRegion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ParseRegion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ParseRegion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /**
     * Парсим (full_info without url_otchet) kskforum.kz на указанный район
     * @param integer $id
     * @return mixed
     */
    public function actionImport($id, $from = null)
    {
        $model = $this->findModel($id);
        $model->importKsk();
        if (!is_null($from) && $from == 'dashboard') {
            return $this->redirect(['/temp/dashboard/index']);
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Парсим (url_otchet) kskforum.kz на указанный район
     * @param integer $id
     * @return mixed
     */
    public function actionRefresh($id, $from = null)
    {
        $model = $this->findModel($id);
        $model->refreshUrl();
        if (!is_null($from) && $from == 'dashboard') {
            return $this->redirect(['/temp/dashboard/index']);
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Парсим (otchet) kskforum.kz на указанный район
     * @param $id
     * @param $offset
     * @param $limit
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionImportReport($id, $offset, $limit, $from = null)
    {
        $model = $this->findModel($id);
        if ($model->importReport($offset, $limit)) {
            return $this->redirect(['import-report',
                'id' => $model->id, 'offset' => $offset + $limit, 'limit' => $limit, $from]);
        }
        if (!is_null($from) && $from == 'dashboard') {
            return $this->redirect(['/temp/dashboard/index']);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }


    /**
     * Finds the ParseRegion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ParseRegion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ParseRegion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
