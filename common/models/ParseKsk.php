<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "parse_ksk".
 *
 * @property integer $id
 * @property integer $parse_region_id
 * @property string $name
 * @property string $url_ksk
 * @property string $url_otchet
 * @property integer $color
 * @property string $updated_at
 *
 * @property ParseRegion $parseRegion
 * @property ParseOtchet[] $parseOtchets
 */
class ParseKsk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parse_ksk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parse_region_id'], 'required'],
            [['parse_region_id', 'color'], 'integer'],
            [['updated_at'], 'safe'],
            [['name', 'url_ksk', 'url_otchet'], 'string', 'max' => 255],
            [['parse_region_id'], 'exist', 'skipOnError' => true, 'targetClass' => ParseRegion::className(), 'targetAttribute' => ['parse_region_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parse_region_id' => 'Район',
            'name' => 'Наименование кск',
            'url_ksk' => 'Ссылка на кск',
            'url_otchet' => 'Ссылка на отчеты',
            'color' => 'Цвет',
            'updated_at' => 'Время последнего обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParseRegion()
    {
        return $this->hasOne(ParseRegion::className(), ['id' => 'parse_region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParseOtchets()
    {
        return $this->hasMany(ParseOtchet::className(), ['parse_ksk_id' => 'id']);
    }
}
