<?php

use yii\db\Migration;
use yii\db\Schema;

class m160217_142011_create_static_page_and_blog extends Migration
{
    public function up()
    {
        $this->createTable('static_page', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Заголовок'",
            'text' => Schema::TYPE_TEXT . " NOT NULL COMMENT 'Тело'",
            'views' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0 COMMENT 'Промотры'",
            'status' => Schema::TYPE_SMALLINT . " NOT NULL DEFAULT 0 COMMENT 'Статус'",
            'published_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Публикация'",
            'created_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Дата создания'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Дата изменения'"
        ], 'ENGINE InnoDB');

        $this->createTable('blog', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING . "(255) NOT NULL COMMENT 'Заголовок'",
            'short_text' => Schema::TYPE_TEXT . " NOT NULL COMMENT 'Краткое тело'",
            'text' => Schema::TYPE_TEXT . " NOT NULL COMMENT 'Тело'",
            'views' => Schema::TYPE_INTEGER . " NOT NULL DEFAULT 0 COMMENT 'Промотры'",
            'status' => Schema::TYPE_SMALLINT . " NOT NULL DEFAULT 0 COMMENT 'Статус'",
            'published_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Публикация'",
            'created_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Дата создания'",
            'updated_at' => Schema::TYPE_DATETIME . " NULL COMMENT 'Дата изменения'"
        ], 'ENGINE InnoDB');
    }

    public function down()
    {
        $this->dropTable('static_page');
        $this->dropTable('blog');
    }
}
