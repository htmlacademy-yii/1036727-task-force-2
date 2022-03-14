<?php

use yii\db\Migration;

/**
 * Handles the update of table `{{%user_profile}}`.
 */
class m220202_000020_update_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{user_profile}}', 'birthday', $this->date()->null());
        $this->renameColumn('{{user_profile}}', 'show_contacts', 'private_contacts');
        $this->alterColumn('{{user_profile}}', 'private_contacts', $this->boolean()->notNull()->defaultValue(0));
        $this->dropColumn('{{user_profile}}', 'address');
        $this->dropColumn('{{user_profile}}', 'contact_skype');
        $this->dropColumn('{{user_profile}}', 'notice_message');
        $this->dropColumn('{{user_profile}}', 'notice_actions');
        $this->dropColumn('{{user_profile}}', 'notice_review');
        $this->dropColumn('{{user_profile}}', 'show_profile');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{user_profile}}', 'birthday', $this->timestamp()->null());
        $this->alterColumn('{{user_profile}}', 'private_contacts', $this->boolean()->notNull()->defaultValue(1));
        $this->renameColumn('{{user_profile}}', 'private_contacts', 'show_contacts');
        $this->addColumn('{{user_profile}}', 'address', $this->string(128)->null());
        $this->addColumn('{{user_profile}}', 'contact_skype', $this->string(128)->null()->unique());
        $this->addColumn('{{user_profile}}', 'notice_message', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{user_profile}}', 'notice_actions', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{user_profile}}', 'notice_review', $this->boolean()->notNull()->defaultValue(0));
        $this->addColumn('{{user_profile}}', 'show_profile', $this->boolean()->notNull()->defaultValue(1));
    }
}
