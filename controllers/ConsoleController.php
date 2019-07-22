<?php

namespace app\commands;

use app\models\Currency;
use yii\console\Controller;
use yii\console\ExitCode;
use GuzzleHttp\Client;

class ConsoleController extends Controller
{
    public function actionCurrencyUpdate()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://www.cbr.ru/scripts/XML_daily.asp');
        foreach ($response->xml() as $valute) {
            $currency = Currency::find()->where(['code' => $valute->CharCode]);
            if ($currency === null) {
                $currency = new Currency();
                $currency->code = $valute->CharCode;
                $currency->name = $valute->Name;
                $currency->rate = $valute->Value;
            }
        }

        return ExitCode::OK;
    }
}
