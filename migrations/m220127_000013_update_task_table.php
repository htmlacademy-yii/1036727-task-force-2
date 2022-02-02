<?php

use yii\db\Migration;

/**
 * Handles the update of table `{{%task}}`.
 */
class m220127_000013_update_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{task}}', 'address', 'location');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{task}}', 'location', 'address');
    }
}
