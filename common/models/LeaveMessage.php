<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "leave_message".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $message
 * @property integer $status
 * @property string $user_ip
 * @property string $created_at
 * @property string $updated_at
 */
class LeaveMessage extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_READ = 2;
    const STATUS_MARKED = 3;

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
        return 'leave_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'message', 'status'], 'required'],
            [['message'], 'string'],
            [['status'], 'integer'],
            [['email'], 'email'],
            [['created_at', 'updated_at', 'user_ip'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'message' => 'Сообщение',
            'status' => 'Статус',
            'user_ip' => 'IP address',
            'created_at' => 'Время создания',
            'updated_at' => 'Время изменения',
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert) && $this->canLeaveMessage();
    }

    /**
     * может ли оставить сообщение
     * @return bool
     */
    public function canLeaveMessage() {
        return self::find()
            ->where(['user_ip' => $this->user_ip])
            ->andWhere(['>', 'created_at', new Expression('NOW() - INTERVAL 1 HOUR')])
            ->count() < 5;
    }
}
