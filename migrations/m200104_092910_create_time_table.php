<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%time}}`.
 */
class m200104_092910_create_time_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%time}}', [
            'id' => $this->primaryKey(),
            'time' => $this->string(),
            'day_id' => $this->integer(),
            'is_reserved' => $this->integer()->defaultValue(0)
        ]);

        $this->addForeignKey(
            'fk-time-day_id',
            '{{%time}}',
            'day_id',
            'day',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-time-day_id', '{{%time}}');
        $this->dropTable('{{%time}}');
    }
}
