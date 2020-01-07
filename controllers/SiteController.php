<?php

namespace app\controllers;

use app\models\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Telegram\Bot\Api;
use app\models\Time;

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

    public function beforeAction($action)
    {
        if ($action->id === 'data') {
            $this->enableCsrfValidation = false;
        }
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
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
    public function actionChangeStatus()
    {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');

            $time = Time::find()->where(['id' => $id])->one();
            ($time->is_reserved == 0) ? $time->is_reserved = 1 : $time->is_reserved = 0;
            $time->update();
        }
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
        $response = $request->data;
        $temp_now = $response['fact']['temp'];
        $feels_like = $response['fact']['feels_like'];
        $day_temp_min = $response['forecasts'][0]['parts']['day']['temp_min'];
        $day_temp_max = $response['forecasts'][0]['parts']['day']['temp_max'];
        $evening_temp_min = $response['forecasts'][0]['parts']['evening']['temp_min'];
        $evening_temp_max = $response['forecasts'][0]['parts']['evening']['temp_max'];

        $message = 'Сейчас на улице ' . $temp_now . '&#176;.' . PHP_EOL .
            'Ощущается как ' . $feels_like . '&#176;.' . PHP_EOL .
            'Днем температура ' . $day_temp_min . '-' . $day_temp_max . '&#176;.' . PHP_EOL .
            'Вечером ' . $evening_temp_min . '-' . $evening_temp_max . '&#176;.';

        $telegram = new Api('684171945:AAHYpXchYNmqx0FT0lKMx0Q_1FWy1S6jXuE');
        $telegram->sendMessage([
            'chat_id' => '-271018918',
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

    }

    public function actionData()
    {
        $this->enableCsrfValidation = false;
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $city = $post['city'];
            $data_client = new Client();
            $request = $data_client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setMethod('post')
                ->setUrl('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address')
                ->setHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json', 'Authorization' => 'Token 012184357e822066e736bd13933fc09629b66b16'])
                ->setData(['query' => $city, 'count' => 1, 'bounds' => 'city'])
                ->send();
            $response = $request->data;
            $geo_lat = $response["suggestions"][0]["data"]["geo_lat"];
            $geo_lon = $response["suggestions"][0]["data"]["geo_lon"];
            $weather = $this->getWeather($geo_lat, $geo_lon);
            return json_encode($weather);
        }
    }

    public function getWeather($geo_lat, $geo_lon)
    {
        $yandex_token = 'db768440-186b-4490-b54d-253adeff4286';
        $client = new Client();
        $request = $client->createRequest()
            ->setMethod('get')
            ->setUrl('https://api.weather.yandex.ru/v1/forecast?')
            ->setHeaders(['X-Yandex-API-Key' => $yandex_token])
            ->setData(['lat' => $geo_lat, 'lon' => $geo_lon, 'lang' => 'ru_RU', 'limit' => 1])
            ->send();
        $response = $request->data;
        $temp_now = $response['fact']['temp'];
        return $temp_now;
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
            return $this->redirect('../admin');
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
