<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m220202_000023_update_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('user_profile', '{{user_profile}}');
        $this->alterColumn('{{user_profile}}', 'user_id', $this->integer()->unsigned()->notNull()->unique());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{user_profile}}', 'user_id', $this->integer()->unsigned()->notNull());
        $this->execute('ALTER TABLE user_profile ADD UNIQUE INDEX user_profile (id, user_id)');
    }
}
