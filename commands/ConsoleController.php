<?php

namespace app\controllers;

use app\models\Currency;
use yii\console\Controller;
use yii\console\ExitCode;
use GuzzleHttp\Client;

/**
 * Class ConsoleController
 * @package app\commands
 */
class CurrencyController extends Controller
{
    /**
     * Парсим XML-ответ от cbr.ru
     * Обновляем данные в базе
     *
     * @return int
     */
    public function actionCurrencyUpdate()
    {
        $client = new Client();
        $response = $client->get('http://www.cbr.ru/scripts/XML_daily.asp');
        foreach ($response->xml() as $valute) {
            $currency = Currency::find()->where(['code' => $valute->CharCode])->one() ?? new Currency();
            $currency->code = (string) $valute->CharCode;
            $currency->name = (string) $valute->Name;
            $currency->rate = (string) $valute->Value;
            $currency->save();
        }
        return ExitCode::OK;
    }
}
