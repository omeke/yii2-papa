<?php

use yii\db\Migration;
use yii\db\Schema;

class m160214_162631_create_leave_message extends Migration
{
    public function up()
    {
        $this->createTable('leave_message', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . " NOT NULL COMMENT 'Имя'",
            'email' => Schema::TYPE_STRING . " NOT NULL COMMENT 'Email'",
            'message' => Schema::TYPE_TEXT . " NOT NULL COMMENT 'Сообщение'",
            'status' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Статус'",
            'user_ip' => Schema::TYPE_STRING . " NULL COMMENT 'IP address'",
            'created_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время создания'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время изменения'"
        ], 'ENGINE InnoDB');
    }

    public function down()
    {
        $this->dropTable('leave_message');
    }
}
