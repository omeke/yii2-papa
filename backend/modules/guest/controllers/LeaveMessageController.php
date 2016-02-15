<?php

namespace backend\modules\guest\controllers;

use Yii;
use common\models\LeaveMessage;
use backend\modules\guest\models\LeaveMessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LeaveMessageController implements the CRUD actions for LeaveMessage model.
 */
class LeaveMessageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'mark'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['superadmin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all LeaveMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeaveMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeaveMessage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->markAs(LeaveMessage::STATUS_READ);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * пометить как
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionMark($id, $status)
    {
        $model = $this->findModel($id);
        $model->markAs($status);
        return $this->redirect(['index']);
    }

    /**
     * Finds the LeaveMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeaveMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeaveMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
