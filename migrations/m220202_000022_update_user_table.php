<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m220202_000022_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('user_ft_search', '{{user}}');
        $this->dropIndex('task_ft_search', '{{user}}');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('ALTER TABLE user ADD FULLTEXT INDEX user_ft_search (name)');
        $this->execute('ALTER TABLE user ADD FULLTEXT INDEX task_ft_search (name)');
    }
}
