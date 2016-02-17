<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

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
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ]
        ];
    }

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
            [['title', 'short_text', 'text'], 'required'],
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

    public static function statusNames()
    {
        return [
            null => '(не указан)',
            self::STATUS_ON => 'Опубликован',
            self::STATUS_OFF => 'Скрыт'
        ];
    }

    public static function statusClasses()
    {
        return [
            null => 'default',
            self::STATUS_OFF => 'danger',
            self::STATUS_ON => 'success'
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->status === self::STATUS_ON) {
                $this->published_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }

    public function on() {
        $this->status = self::STATUS_ON;
        return $this->save();
    }

    public function off() {
        $this->status = self::STATUS_OFF;
        return $this->save();
    }

}
