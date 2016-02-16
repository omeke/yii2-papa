<?php

use yii\db\Migration;
use yii\db\Schema;

class m160216_131508_alt_parse_otchet extends Migration
{
    public function up()
    {
        $this->addColumn('parse_otchet', 'text', Schema::TYPE_TEXT . " NULL COMMENT 'Отчет'");

        $this->createTable('parse_otchet_file', [
            'id' => Schema::TYPE_PK,
            'file_name' => Schema::TYPE_STRING . " NOT NULL COMMENT 'Имя файла'",
            'path' => Schema::TYPE_STRING . " NOT NULL COMMENT 'Путь'",
            'parse_otchet_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Отчет'"
        ], 'ENGINE InnoDB');

        $this->addForeignKey('fk_parse_otchet_file', 'parse_otchet_file', 'parse_otchet_id', 'parse_otchet', 'id', 'restrict', 'restrict');
    }

    public function down()
    {
        $this->dropForeignKey('fk_parse_otchet_file', 'parse_otchet_file');
        $this->dropTable('parse_otchet_file');
        $this->dropColumn('parse_otchet', 'text');
    }
}
