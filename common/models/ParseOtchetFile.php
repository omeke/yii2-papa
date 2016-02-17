<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parse_otchet_file".
 *
 * @property integer $id
 * @property string $file_name
 * @property string $path
 * @property integer $parse_otchet_id
 *
 * @property ParseOtchet $parseOtchet
 */
class ParseOtchetFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_otchet_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file_name', 'path', 'parse_otchet_id'], 'required'],
            [['parse_otchet_id'], 'integer'],
            [['file_name', 'path'], 'string', 'max' => 255],
            [['parse_otchet_id'], 'exist', 'skipOnError' => true, 'targetClass' => ParseOtchet::className(), 'targetAttribute' => ['parse_otchet_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_name' => 'Имя файла',
            'path' => 'Путь',
            'parse_otchet_id' => 'Отчет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParseOtchet()
    {
        return $this->hasOne(ParseOtchet::className(), ['id' => 'parse_otchet_id']);
    }
}
