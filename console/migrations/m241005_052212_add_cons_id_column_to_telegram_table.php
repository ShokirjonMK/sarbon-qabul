<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%telegram}}`.
 */
class m241005_052212_add_cons_id_column_to_telegram_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('telegram' , 'cons_id' , $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
