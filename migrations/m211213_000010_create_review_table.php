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
            'user_id' => $this->integer()->unsigned()->notNull(),
            'author_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-review-user_id}}',
            '{{%review}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-review-author_id}}',
            '{{%review}}',
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
        $this->dropTable('{{%review}}');
    }
}
