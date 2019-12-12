<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Telegram\Bot\Api;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTelegram()
    {
        $yandex_token = 'db768440-186b-4490-b54d-253adeff4286';
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod('get')
            ->setUrl('https://api.weather.yandex.ru/v1/forecast?')
            ->setHeaders(['X-Yandex-API-Key' => $yandex_token])
            ->setData(['lat' => '52.7', 'lon' => '41.4', 'lang' => 'ru_RU', 'limit' => 1])
            ->send();
        $response = $request->data['fact'];
        $temp = $response['temp'];
        $feels_like = $response['feels_like'];
        $message = 'Сейчас на улице ' . $temp . ' градусов.' . PHP_EOL .
            'Ощущается как ' . $feels_like . ' .';


        $telegram = new Api('684171945:AAHYpXchYNmqx0FT0lKMx0Q_1FWy1S6jXuE');
        $telegram->sendMessage([
            'chat_id' => '-271018918',
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

    }


    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
