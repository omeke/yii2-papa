<?php

use yii\db\Migration;
use yii\db\Schema;

class m160213_062114_create_parse_tables extends Migration
{
    public function up()
    {
        $this->createTable('parse_region', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . " NULL COMMENT 'Наименование района'",
            'url' => Schema::TYPE_STRING . " NULL COMMENT 'Ссылка на район'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время последнего обновления'"
        ]);
        $this->batchInsert('parse_region', ['id', 'name', 'url', 'updated_at'], [
            [1, 'Ауэзовский район', 'http://kskforum.kz/forum/2-auezovskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [2, 'Алатауский район', 'http://kskforum.kz/forum/3-alatauskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [3, 'Алмалинский район', 'http://kskforum.kz/forum/4-almalinskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [4, 'Бостандыкский район', 'http://kskforum.kz/forum/272-bostandykskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [5, 'Жетысуйский район', 'http://kskforum.kz/forum/6-zhetysujskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [6, 'Медеуский район', 'http://kskforum.kz/forum/7-medeuskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [7, 'Наурызбайский район', 'http://kskforum.kz/forum/1568-nauryzbajskij-rajon/', new \yii\db\Expression('utc_timestamp()')],
            [8, 'Турксибский район', 'http://kskforum.kz/forum/8-turksibskij-rajon/', new \yii\db\Expression('utc_timestamp()')]
        ]);

        $this->createTable('parse_ksk', [
            'id' => Schema::TYPE_PK,
            'parse_region_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Район'",
            'name' => Schema::TYPE_STRING . " NULL COMMENT 'Наименование кск'",
            'url_ksk' => Schema::TYPE_STRING . " NULL COMMENT 'Ссылка на кск'",
            'url_otchet' => Schema::TYPE_STRING . " NULL COMMENT 'Ссылка на отчеты'",
            'color' => Schema::TYPE_INTEGER . " NULL COMMENT '0 - black; 1 - green'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время последнего обновления'"
        ]);

        $this->createTable('parse_otchet', [
            'id' => Schema::TYPE_PK,
            'parse_ksk_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'КСК'",
            'name' => Schema::TYPE_STRING . " NULL COMMENT 'Наименование отчета'",
            'url_otchet' => Schema::TYPE_STRING . " NULL COMMENT 'Ссылка на отчет'",
            'posted_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время публикации'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Время последнего обновления'"
        ]);

        $this->addForeignKey('fk_parse_ksk', 'parse_ksk', 'parse_region_id', 'parse_region', 'id', 'restrict', 'restrict');
        $this->addForeignKey('fk_parse_otchet', 'parse_otchet', 'parse_ksk_id', 'parse_ksk', 'id', 'restrict', 'restrict');

    }

    public function down()
    {
        $this->dropForeignKey('fk_parse_ksk', 'parse_ksk');
        $this->dropForeignKey('fk_parse_otchet', 'parse_otchet');
        $this->dropTable('parse_region');
        $this->dropTable('parse_ksk');
        $this->dropTable('parse_otchet');
    }
}
