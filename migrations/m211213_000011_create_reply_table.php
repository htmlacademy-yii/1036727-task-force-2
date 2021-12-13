<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reply}}`.
 */
class m211213_000011_create_reply_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reply}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dt_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'price' => $this->integer()->unsigned()->null(),
            'comment' => $this->string(255)->null(),
            'task_id' => $this->integer()->unsigned()->notNull(),
            'author_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-reply-task_id}}',
            '{{%reply}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-reply-city_id}}',
            '{{%reply}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reply}}');
    }
}
