<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Currency extends ActiveRecord
{
    public function beforeSave($insert)
    {
        $this->rate = (float) strtr($this->rate, [',' => '.']);
        return parent::beforeSave($insert);
    }
}
