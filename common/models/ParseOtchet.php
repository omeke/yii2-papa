<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parse_otchet".
 *
 * @property integer $id
 * @property integer $parse_ksk_id
 * @property string $name
 * @property string $url_otchet
 * @property string $posted_at
 * @property string $updated_at
 *
 * @property ParseKsk $parseKsk
 */
class ParseOtchet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_otchet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parse_ksk_id'], 'required'],
            [['parse_ksk_id'], 'integer'],
            [['posted_at', 'updated_at'], 'safe'],
            [['name', 'url_otchet'], 'string', 'max' => 255],
            [['parse_ksk_id'], 'exist', 'skipOnError' => true, 'targetClass' => ParseKsk::className(), 'targetAttribute' => ['parse_ksk_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parse_ksk_id' => 'КСК',
            'name' => 'Наименование отчета',
            'url_otchet' => 'Ссылка на отчет',
            'posted_at' => 'Время публикации',
            'updated_at' => 'Время последнего обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParseKsk()
    {
        return $this->hasOne(ParseKsk::className(), ['id' => 'parse_ksk_id']);
    }
}
