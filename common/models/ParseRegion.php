<?php

namespace common\models;

use backend\helpers\NokogiriHelper;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "parse_region".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $updated_at
 *
 * @property ParseKsk[] $parseKsks
 */
class ParseRegion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_region';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_at'], 'safe'],
            [['name', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование района',
            'url' => 'Ссылка на район',
            'updated_at' => 'Время последнего обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParseKsks()
    {
        return $this->hasMany(ParseKsk::className(), ['parse_region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParseOtchets()
    {
        return $this->hasMany(ParseOtchet::className(), ['parse_ksk_id' => 'id'])
            ->via('parseKsks');
    }

    /**
     * парсим "кскфорум"
     * выкачиваем всю информация по текущему району кроме ссылки на отчет
     */
    public function importKsk()
    {
        $changes = [];
        $news = [];
        $errors = [];
        $updated = 0;
        $created = 0;
        $saw = new NokogiriHelper(file_get_contents($this->url));
        foreach ($saw->get('ol.subforums li a') as $a) {
            $color = 0;
            $name = trim($a['title']);
            if (isset($a['font'])) {
                $name = trim($a['font'][0]['#text'][0]);
                $color = 1;
            }
            $model = ParseKsk::find()->where(['name' => $name, 'parse_region_id' => $this->id])->one();
            if ($model === null) {
                $model = new ParseKsk();
                $model->name = $name;
                $model->parse_region_id = $this->id;
            }

            $update = false;
            if (!$model->isNewRecord && trim($a['href']) != '' && $model->url_ksk != $a['href']) {
                $changes[] = "({$model->name}) {$model->url_ksk} -> " . $a['href'];
                $model->url_ksk = $a['href'];
                $update = true;
            }
            if (!$model->isNewRecord && $model->color != $color) {
                $changes[] = "({$model->name}) {$model->color} -> " . $color;
                $model->color = $color;
                $update = true;
            }

            if ($update || $model->isNewRecord) {
                $model->updated_at = new \yii\db\Expression('utc_timestamp()');
                if ($model->isNewRecord) {
                    $news[] = $model->name;
                    $created += $model->save() ? 1 : 0;
                } else {
                    $updated += $model->save() ? 1 : 0;
                }
                if ($model->hasErrors()) {
                    $errors[] = $model->errors;
                }
            }
        }

        if ($created + $updated > 0) {
            $this->updated_at = new \yii\db\Expression('utc_timestamp()');
            $message = '';
            $message .= $created > 0 ? "Новых записей - {$created}<br>" : '';
            $message .= $updated > 0 ? "Обновлено записей - {$updated}<br>" : '';

            $message .= sizeof($news) > 0 ? "Новые - [<br>" : '';
            foreach ($news as $new) {
                $message .= "<pre>{$new}</pre><br>";
            }
            $message .= sizeof($news) > 0 ? "]<br>" : '';

            $message .= sizeof($changes) > 0 ? "Обновление - [<br>" : '';
            foreach ($changes as $change) {
                $message .= "<pre>{$change}</pre><br>";
            }
            $message .= sizeof($changes) > 0 ? "]<br>" : '';

            Yii::$app->session->setFlash('info', $message);
            $this->save();
        }

        if (sizeof($errors) > 0) {
            Yii::$app->session->setFlash('warning', Json::encode($errors));
        }
    }

    /**
     * парсим "кскфорум"
     * обновляем ссылки на отчет
     */
    public function refreshUrl()
    {
        $i = 0;
        $errors = [];
        $models = ParseKsk::find()->where(['parse_region_id' => $this->id, 'url_otchet' => null])->all();
        foreach ($models as $model) {
            /* @var $model ParseKsk */
            $saw = new NokogiriHelper(file_get_contents($model->url_ksk));

            $ok = false;
            foreach ($saw->get('.col_c_forum a') as $a) {
                if (strpos(mb_strtolower($a['#text'][0]), 'отчеты') !== false) {
                    $model->url_otchet = $a['href'];
                    $ok = true;
                    break;
                }
            }

            if ($ok) {
                $model->updated_at = new \yii\db\Expression('utc_timestamp()');
                if ($model->save()) {
                    $i++;
                } else {
                    $errors[] = $model->errors;
                }
            }

        }

        if ($i > 0) {
            $this->updated_at = new \yii\db\Expression('utc_timestamp()');
            $this->save();
        }

        $total = sizeof($models);
        Yii::$app->session->setFlash('info', "Обновлено записей - {$i} из {$total}<br>");

        if (sizeof($errors) > 0) {
            Yii::$app->session->setFlash('warning', Json::encode($errors));
        }
    }

    /**
     * парсим "кскфорум"
     * обновляем отчеты
     */
    public function importReport($offset, $limit)
    {
        $saved = 0;
        $errors = [];
        $models = ParseKsk::find()
            ->where(['parse_region_id' => $this->id])
            ->andWhere(['IS NOT', 'url_otchet', null])
            ->offset($offset)
            ->limit($limit)
            ->all();

        foreach ($models as $model) {
            /* @var $model ParseKsk */
            $saw = new NokogiriHelper(file_get_contents($model->url_otchet));
            foreach ($saw->get('.col_f_content') as $forum_name) {
                $h4s = $forum_name['h4'];
                if (isset($forum_name['span'][0]['span'])) {
                    $posted_at = $forum_name['span'][0]['span'][0]['#text'][0];
                } else {
                    $posted_at = $forum_name['span'][1]['span'][0]['#text'][0];
                }
                foreach ($h4s as $h4) {
                    $a = $h4['a'][0];
                    $name = trim($a['span'][0]['#text'][0]);
                    $url = $a['href'];
                    $model_otchet = ParseOtchet::find()->where(['name' => $name])->one();
                    if (is_null($model_otchet)) {
                        $model_otchet = new ParseOtchet();
                        $model_otchet->parse_ksk_id = $model->id;
                        $model_otchet->name = $name;
                        $model_otchet->url_otchet = $url;
                        $model_otchet->posted_at = date('Y-m-d H:i:s', strtotime($posted_at));
                        $model_otchet->updated_at = new \yii\db\Expression('utc_timestamp()');
                        $model_otchet->save() ? $saved++ : $errors[] = $model_otchet->errors;
                    }
                }
            }
        }

        if ($saved > 0) {
//            Yii::$app->session->setFlash('info', "Создано записей - {$saved}<br>");
            $this->updated_at = new \yii\db\Expression('utc_timestamp()');
            $this->save();
        }

        if (sizeof($errors) > 0) {
//            Yii::$app->session->setFlash('warning', Json::encode($errors));
        }

        return sizeof($models) > 0;
    }

}
