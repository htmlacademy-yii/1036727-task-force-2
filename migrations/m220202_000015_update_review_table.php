<?php

use yii\db\Migration;

/**
 * Handles the update of table `{{%reply}}`.
 */
class m220202_000015_update_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{review}}', 'rate', 'rating');
        $this->alterColumn('{{review}}', 'task_id', $this->integer()->unsigned()->notNull()->unique());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{review}}', 'rating', 'rate');
        $this->alterColumn('{{review}}', 'task_id', $this->integer()->unsigned()->notNull());
    }
}
