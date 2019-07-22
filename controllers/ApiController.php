<?php

declare(strict_types=1);

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

class SiteController extends Controller
{
    public function init(): void
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function behaviors(): array
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

    public function currencyIndex(): array
    {
        $query = Currency::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $currencies = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        if ($currencies === null) {
            throw new NotFoundHttpException();
        }
        return $currencies;
    }

    public function currencyView($id): array
    {
        $currency = Currency::find()->where(['id' => $id])->asArray()->one();
        if ($currency === null) {
            throw new NotFoundHttpException();
        }
        return $currency;
    }

}
