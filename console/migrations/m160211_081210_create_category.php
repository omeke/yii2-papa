<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m160211_081210_create_category extends Migration
{
    public function up()
    {
        $this->createTable('group', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . " NOT NULL comment 'Название группы'",
            'parent_id' => Schema::TYPE_INTEGER . " NULL comment 'Родительская группа'"
        ], 'ENGINE InnoDB');

        $this->createTable('object', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . " NOT NULL comment 'Название объекта'",
            'group_id' => Schema::TYPE_INTEGER . " NULL comment 'Группа'"
        ], 'ENGINE InnoDB');

        $this->createTable('object_info', [
            'id' => Schema::TYPE_PK,
            'address_ksk' => Schema::TYPE_STRING . " NOT NULL comment 'адрес КСК'",
            'amount_house' => Schema::TYPE_STRING . " NULL comment 'Кол-во домов'",
            'address_house' => Schema::TYPE_STRING . " NULL comment 'Адреса домов'",
            'fullname_chairman' => Schema::TYPE_STRING . " NULL comment 'Ф.И.О. председателя'",
            'phone' => Schema::TYPE_STRING . " NULL comment 'Телефон'",
            'object_id' => Schema::TYPE_INTEGER . " NOT NULL comment 'Объект'"
        ], 'ENGINE InnoDB');

        $this->addForeignKey('fk_object', 'object', 'group_id', 'group', 'id', 'restrict', 'restrict');
        $this->addForeignKey('fk_object_info', 'object_info', 'object_id', 'object', 'id', 'restrict', 'restrict');
    }

    public function down()
    {
        $this->dropForeignKey('fk_object_info', 'object_info');
        $this->dropForeignKey('fk_object', 'object');
        $this->dropTable('group');
        $this->dropTable('object');
        $this->dropTable('object_info');
    }
}
