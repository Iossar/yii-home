<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\components\jobs\TableJob;
use app\daemons\ChatServer;
use app\daemons\EchoServer;
use app\models\User;
use consik\yii2websocket\WebSocketServer;
use Telegram\Bot\Api;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
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

    public function actionTest($name, $age, $gender)
    {
        $model = new User();
        $model->name = $name;
        $model->age = $age;
        $model->gender = $gender;
        Yii::$app->queue->delay(5)->push(new TableJob([
            'model' => $model,
        ]));
    }

    public function actionStartWs($port = null)
    {
        $server = new ChatServer();
        if ($port) {
            $server->port = $port;
        }
        $server->start();
    }
}
