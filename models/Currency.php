<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Currency
 *
 * @property int $id ID
 * @property string $code Трёхзначный код валюты
 * @property string $name Название валюты
 * @property float $rate Трёхзначный код валюты
 * @package app\models
 */
class Currency extends ActiveRecord
{
    /**
     * Перед сохранением модели проверяем корректность $rate
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->rate = (float)strtr($this->rate, [',' => '.']);
            return true;
        } else {
            return false;
        }
    }
}
