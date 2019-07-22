<?php

use yii\db\Migration;

class m150101_185401_create_news_table extends Migration
{
    public function up()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'code' => $this->string(3)->unique(),
            'name' => $this->string(),
            'rate' => $this->float()->unsigned(),
        ]);
    }

    public function down()
    {
        $this->dropTable('currency');
    }
}
