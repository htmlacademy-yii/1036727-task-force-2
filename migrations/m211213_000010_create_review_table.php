<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%review}}`.
 */
class m211213_000010_create_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%review}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dt_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'rate' => $this->integer()->unsigned()->notNull(),
            'comment' => $this->string(255)->notNull(),
            'task_id' => $this->integer()->unsigned()->notNull(),
        ]);

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-review-task_id}}',
            '{{%review}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%review}}');
    }
}
