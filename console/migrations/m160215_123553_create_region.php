<?php

use yii\db\Migration;
use yii\db\Schema;

class m160215_123553_create_region extends Migration
{
    public function up()
    {
        $this->createTable('region', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . " NOT NULL COMMENT 'Район'",
            'created_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время создания'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время изменения'"
        ], 'ENGINE InnoDB');

        $this->insert('region', [
            'id' => 1,
            'name' => 'КСК Ауэзовского района',
            'created_at' => new \yii\db\Expression('NOW()'),
            'updated_at' => new \yii\db\Expression('NOW()')
        ]);
        $this->addColumn('object', 'region_id', Schema::TYPE_INTEGER . " NULL COMMENT 'Район'");
        $this->update('object', ['region_id' => 1]);
        $this->addForeignKey('fk_object_region', 'object', 'region_id', 'region', 'id', 'restrict', 'restrict');
    }

    public function down()
    {
        $this->dropForeignKey('fk_object_region', 'object');
        $this->dropColumn('object', 'region_id');
        $this->dropTable('region');
    }
}
