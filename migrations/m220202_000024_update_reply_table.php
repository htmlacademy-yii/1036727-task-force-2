<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reply}}`.
 */
class m220202_000024_update_reply_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE reply ADD UNIQUE INDEX task_user (task_id, user_id)');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('task_user', '{{reply}}');
    }
}
