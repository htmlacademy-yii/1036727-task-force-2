<?php

use yii\db\Migration;

/**
 * Handles the update of table `{{%reply}}`.
 */
class m220202_000016_update_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{task}}', 'expire', $this->date()->null());
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{task}}', 'expire', $this->timestamp()->null());
    }
}
