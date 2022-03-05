<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo_of_work}}`.
 */
class m220202_000019_drop_photo_of_work_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%photo_of_work}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%photo_of_work}}', [
            'id' => $this->primaryKey()->unsigned(),
            'path' => $this->string(128)->notNull()->unique(),
            'profile_id' => $this->integer()->unsigned()->notNull()
        ]);

        // add foreign key for table `{{%user_profile}}`
        $this->addForeignKey(
            '{{%fk-photo_of_work-profile_id}}',
            '{{%photo_of_work}}',
            'profile_id',
            '{{%user_profile}}',
            'id',
            'CASCADE'
        );
    }
}
