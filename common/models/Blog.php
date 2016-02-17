<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blog".
 *
 * @property integer $id
 * @property string $title
 * @property string $short_text
 * @property string $text
 * @property integer $views
 * @property integer $status
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 */
class Blog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'short_text', 'text', 'created_at', 'updated_at'], 'required'],
            [['short_text', 'text'], 'string'],
            [['views', 'status'], 'integer'],
            [['published_at', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'short_text' => 'Краткое тело',
            'text' => 'Тело',
            'views' => 'Промотры',
            'status' => 'Статус',
            'published_at' => 'Публикация',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }
}
