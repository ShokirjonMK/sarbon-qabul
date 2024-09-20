<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%direction}}`.
 */
class m240920_045943_add_access_ball_column_to_direction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('direction' , 'access_ball' , $this->float()->defaultValue(60));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
