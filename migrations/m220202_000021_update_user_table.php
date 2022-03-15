<?php

use yii\db\Migration;

/**
 * Handles the update of table `{{%user}}`.
 */
class m220202_000021_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{user}}', 'password', $this->string(255)->null());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{user}}', 'password', $this->string(255)->notNull());
    }
}
