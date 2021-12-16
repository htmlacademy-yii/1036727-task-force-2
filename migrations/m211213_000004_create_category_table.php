<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m211213_000004_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(64)->notNull(),
            'inner_name' => $this->string(64)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
