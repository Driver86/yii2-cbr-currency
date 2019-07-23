<?php

namespace app\controllers;

use app\models\Currency;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * Class CurrencyController
 * @package app\controllers
 */
class CurrencyController extends Controller
{
    /**
     * {@inheritdoc}
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => ['class' => ErrorAction::class],
        ];
    }

    /**
     * Возвращаем список курсов валют с возможностью пагинации.
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionIndex()
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
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $currency = Currency::find()->where(['id' => $id])->asArray()->one();
        if ($currency === null) {
            throw new NotFoundHttpException();
        }
        return $currency;
    }
}
