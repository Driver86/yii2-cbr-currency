<?php

namespace app\controllers;

use app\models\Currency;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ApiController
 * @package app\controllers
 */
class ApiController extends Controller
{
    /**
     * Отключаем сессии ради Bearer
     * Формат ответов этого контроллёра устанавливаем в json
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    /**
     * Активируем авторизацию Bearer
     * и доступ к контроллёру только авторизованным пользователям
     * Так же доступ к методам котроллёра только через get
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'currencies' => ['get'],
                    'currency' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Возвращаем список курсов валют с возможностью пагинации.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function currencyIndex()
    {
        $query = Currency::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 2]);
        $currencies = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        if ($currencies === null) {
            throw new NotFoundHttpException();
        }
        return $currencies;
    }

    /**
     * Возвращаем курс для указанной валюты.
     *
     * @param $code
     * @return array
     * @throws NotFoundHttpException
     */
    public function currencyView($code)
    {
        $currency = Currency::find()->where(['code' => $code])->asArray()->one();
        if ($currency === null) {
            throw new NotFoundHttpException();
        }
        return $currency;
    }

}
