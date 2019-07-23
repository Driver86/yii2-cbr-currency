<?php

use yii\db\Migration;

/**
 * Class m150101_185401_create_currency_table
 */
class m150101_185401_create_currency_table extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(),
            'code' => $this->string(3)->unique(),
            'name' => $this->string(),
            'rate' => $this->float()->unsigned(),
        ]);
    }

    /**
     * @return void
     */
    public function down()
    {
        $this->dropTable('currency');
    }
}
