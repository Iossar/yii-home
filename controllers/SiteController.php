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
    public function actionDump() {
        return $this->renderPartial('dump');
        $quotes_ru = Yii::$app->db->createCommand('SELECT * FROM quotes_ru')->queryAll();
        $category_ru = Yii::$app->db->createCommand('SELECT id, title_ru FROM category')->queryAll();
        $category_en = Yii::$app->db->createCommand('SELECT id, title_en FROM category')->queryAll();
        $quotes_data = [];
        foreach ($quotes_ru as $quote) {
            $quotes_data[] = $quote;
        }
        $encoded = json_encode($quotes_data,JSON_UNESCAPED_UNICODE);
        $filename = __DIR__ . '/../web/results.json';
        $fp = fopen($filename, 'w');
        fwrite($fp, $encoded);
        header('X-Sendfile: ' . $filename);
        //header("Content-Type: image/png");
        //header("Content-Length: " . filesize($filename));
        header("Content-Disposition: attachment; filename =" . 'json.json' . ";charset=UTF-8");
        readfile($filename);

        //fwrite($fp, $encoded);
        //fclose($fp);
    }

    public function actionChat() {
        return $this->render('chat');
    }
}
