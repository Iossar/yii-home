<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%day}}`.
 */
class m200104_090618_create_day_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%day}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%day}}');
    }
}
