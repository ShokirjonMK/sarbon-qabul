<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram}}`.
 */
class m241005_054132_add_time_column_to_telegram_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('telegram' , 'time' , $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
