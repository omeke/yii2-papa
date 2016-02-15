<?php
/**
 * Created by PhpStorm.
 * User: Bekarys
 * Date: 14-Feb-16
 * Time: 19:33
 */

namespace common\helpers;


use common\models\Group;
use common\models\Region;

class KskObjectDataHelper
{
    static $alphabet = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С',
        'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'];

    public static function getData()
    {
        $data_size = 0;
        $data = [];

        foreach (Region::find()->all() as $region) {
            /* @var $region Region */

            $data[$data_size] = [
                'title' => $region->name,
                'folder' => true,
                'expanded' => true,
                'key' => 'r-' . $region->id,
                'children' => [
                ]
            ];

            foreach (Group::find()->all() as $group) {
                /* @var $group Group */
                $objects = [];
                foreach ($group->objects as $object) {
                    if ($object->region_id === $region->id) {
                        $objects[] = [
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
                }
                if (sizeof($objects) > 0) {
                    $data[$data_size]['children'][] = [
                        'title' => $group->name,
                        'folder' => true,
                        'expanded' => false,
                        'count' => sizeof($objects),
                        'key' => 'g-' . $group->id,
                        'children' => $objects
                    ];
                }
            }
            $data[$data_size]['count'] = sizeof($data[$data_size]['children']);
            $data_size++;

        }

        return $data;
    }

}