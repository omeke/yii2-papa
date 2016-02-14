<?php
/**
 * Created by PhpStorm.
 * User: Bekarys
 * Date: 14-Feb-16
 * Time: 19:33
 */

namespace common\helpers;


use common\models\Group;

class KskObjectDataHelper
{
    static $alphabet = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С',
        'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'];

    public static function getData()
    {
        $data_size = 0;
        $data = [];

        foreach (Group::find()->all() as $group) {
            /* @var $group Group */

            $data[$data_size] = [
                'title' => $group->name,
                'folder' => true,
                'expanded' => true,
                'key' => 'g-' . $group->id,
                'children' => [
                ]
            ];

            foreach ($group->objects as $object) {
                /* @var $object Object */

                $data[$data_size]['children'] = [
                    'title' => $object->name,
                    'folder' => true,
                    'expanded' => false,
                    'count' => 2,
                    'key' => 'o-' . $object->id,
                    'children' => [
                        ['title' => 'Информация', 'key' => 'i-' . $object->id],
                        ['title' => 'Документы', 'key' => 'd-' . $object->id]
                    ]
                ];

            }
            $data[$data_size]['count'] = sizeof($data[$data_size]['children']);
        }

        return $data;
    }

}