<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Telegram\Bot\Api;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;
use app\models\Day;
use app\models\Time;

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
        $response = $request->data;
        $temp_now = $response['fact']['temp'];
        $feels_like = $response['fact']['feels_like'];
        $day_temp_min = $response['forecasts'][0]['parts']['day']['temp_min'];
        $day_temp_max = $response['forecasts'][0]['parts']['day']['temp_max'];
        $evening_temp_min = $response['forecasts'][0]['parts']['evening']['temp_min'];
        $evening_temp_max = $response['forecasts'][0]['parts']['evening']['temp_max'];

        $message = PHP_EOL . 'Сейчас на улице ' . $temp_now . '&#176;.' . PHP_EOL .
            'Ощущается как ' . $feels_like . '&#176;.' . PHP_EOL .
            'Днем температура ' . $day_temp_min . '-' . $day_temp_max . '&#176;.' . PHP_EOL .
            'Вечером ' . $evening_temp_min . '-' . $evening_temp_max . '&#176;.'
        ;

        $telegram = new Api('684171945:AAHYpXchYNmqx0FT0lKMx0Q_1FWy1S6jXuE');
        $telegram->sendMessage([
            'chat_id' => '-271018918',
            'text' => $message,
            'parse_mode' => 'HTML',
        ]);

    }

    public function actionFillDays()
    {
        $period = new \DatePeriod(
            new \DateTime(date('Y-m-d', strtotime('today'))),
            new \DateInterval('P1D'),
            new \DateTime(date('Y-m-d', strtotime('today + 8 days'))));
        foreach ($period as $value) {
            $day = new Day;
            $day->date = $value->format('Y-m-d');
            $day->save();
        }
    }

    public function actionFillTime()
    {
        $days = Day::find()->where(['>=', 'date', date('Y-m-d', strtotime('today'))])->all();
        $hours = [
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
        ];
        foreach ($days as $day) {
            foreach ($hours as $hour) {
                $time = new Time;
                $time->time = $hour;
                $time->day_id = $day->id;
                $time->save();
            }
        }
    }
}
