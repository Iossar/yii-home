<?php

use yii\db\Migration;

/**
 * Class m200105_104459_add_customer_columns
 */
class m200105_104459_add_customer_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%time}}', 'name', $this->string());
        $this->addColumn('{{%time}}', 'phone', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%time}}', 'name');
        $this->dropColumn('{{%time}}', 'phone');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200105_104459_add_customer_columns cannot be reverted.\n";

        return false;
    }
    */
}
