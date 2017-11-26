<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171126_200532_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'country_code' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-unique-country_code-phone',
            'user',
            'country_code, phone',
            1
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
