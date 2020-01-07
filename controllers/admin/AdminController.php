<?php

namespace app\controllers\admin;


use app\controllers\SiteController;
use yii\web\Controller;
use Yii;
use app\models\TimeSearch;
use app\models\Time;

class AdminController extends Controller
{
    public function actionAdmin()
    {
        $role = Yii::$app->user->identity->role ?? null;
        if ($role != null && $role == 'admin') {
            $searchModel = new TimeSearch();
            $searchParams = Yii::$app->request->queryParams;
            $dataProvider = $searchModel->searchForAdmin($searchParams);
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel
            ]);
        } else {
            return $this->redirect('site/login');
        }
    }

    public function actionChangeStatus()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('value');
            $time = Time::fineOne($id);
            ($time->is_reserved == 0) ? $time->is_reserved = 1 : $time->is_reserved = 0;
            $time->update();
        }
    }
}