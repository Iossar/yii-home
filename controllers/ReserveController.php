<?php

namespace app\controllers;

use Telegram\Bot\Api;
use Yii;
use app\models\Time;
use app\models\Day;
use app\models\TimeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TimeController implements the CRUD actions for Time model.
 */
class ReserveController extends Controller
{
    /**
     * {@inheritdoc}
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

    public function actionIndex()
    {
        $days = Day::find()->where(['>=', 'date', date('Y-m-d', strtotime('today'))])->all();
        return $this->render('index', [
            'days' => $days
        ]);
    }

    public function actionTime($id)
    {
        $times = Time::find()->where(['day_id' => $id])->all();
        return $this->render('time', [
            'times' => $times
        ]);
    }

    public function actionOrder($id)
    {
        $time = Time::findOne($id);
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();
            $name = $post['name'];
            $phone = $post['phone'];
            $time = Time::findOne($id);
            $message = 'Новая заявка от ' . $name . PHP_EOL .
                'Дата ' . $time->day->date . ' ' . $time->time . PHP_EOL .
                'Номер для связи ' . $phone;
            $telegram = new Api('684171945:AAHYpXchYNmqx0FT0lKMx0Q_1FWy1S6jXuE');
            $telegram->sendMessage([
                'chat_id' => '-397495395',
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
            $time->is_reserved = 1;
            $time->name = $post['name'];
            $time->phone = $post['phone'];
            $time->update();
            Yii::$app->session->setFlash('contactFormSubmitted');
        }
        return $this->render('order', [
            'time' => $time,
        ]);
    }
}
