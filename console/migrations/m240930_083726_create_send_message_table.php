<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%send_message}}`.
 */
class m240930_083726_create_send_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%send_message}}', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(255)->notNull(),
            'status' => $this->integer()->defaultValue(0),
            'push_time' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%send_message}}');
    }
}
