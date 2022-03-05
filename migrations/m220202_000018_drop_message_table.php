<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%message}}`.
 */
class m220202_000018_drop_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%message}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%message}}', [
            'id' => $this->primaryKey()->unsigned(),
            'dt_add' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'content' => $this->string(255)->notNull(),
            'read_status' => $this->boolean()->notNull()->defaultValue(0),
            'sender_id' => $this->integer()->unsigned()->notNull(),
            'recipient_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message-sender_id}}',
            '{{%message}}',
            'sender_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-message-recipient_id}}',
            '{{%message}}',
            'recipient_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }
}
