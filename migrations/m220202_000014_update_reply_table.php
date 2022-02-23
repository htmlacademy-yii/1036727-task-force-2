<?php

use yii\db\Migration;

/**
 * Handles the update of table `{{%reply}}`.
 */
class m220202_000014_update_reply_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{reply}}', 'denied', $this->boolean()->notNull()->defaultValue(0));
        $this->renameColumn('{{reply}}', 'price', 'payment');
        $this->renameColumn('{{reply}}', 'author_id', 'user_id');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{reply}}', 'denied');
        $this->renameColumn('{{reply}}', 'payment', 'price');
        $this->renameColumn('{{reply}}', 'user_id', 'author_id');
    }
}
