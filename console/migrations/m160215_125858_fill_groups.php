<?php

use yii\db\Migration;

class m160215_125858_fill_groups extends Migration
{
    public function up()
    {
        $this->batchInsert('group', ['name'], [
            ['А'], ['Б'], ['В'], ['Г'], ['Д'], ['Е'], ['Ё'], ['Ж'], ['З'], ['И'], ['Й'], ['К'], ['Л'], ['М'], ['Н'],
            ['О'], ['П'], ['Р'], ['С'], ['Т'], ['У'], ['Ф'], ['Х'], ['Ц'], ['Ч'], ['Ш'], ['Щ'], ['Ъ'], ['Ы'], ['Ь'],
            ['Э'], ['Ю'], ['Я']
        ]);
    }

    public function down()
    {
        echo 'you can not migrate/down sorry :(';
        return false;
    }

}
