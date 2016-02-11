<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "object_info".
 *
 * @property integer $id
 * @property string $address_ksk
 * @property integer $amount_house
 * @property integer $address_house
 * @property integer $fullname_chairman
 * @property integer $phone
 * @property integer $object_id
 *
 * @property Object $object
 */
class ObjectInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_ksk', 'object_id'], 'required'],
            [['amount_house', 'address_house', 'fullname_chairman', 'phone', 'object_id'], 'integer'],
            [['address_ksk'], 'string', 'max' => 255],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => Object::className(), 'targetAttribute' => ['object_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_ksk' => 'адрес КСК',
            'amount_house' => 'Кол-во домов',
            'address_house' => 'Адреса домов',
            'fullname_chairman' => 'Ф.И.О. председателя',
            'phone' => 'Телефон',
            'object_id' => 'Объект',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(Object::className(), ['id' => 'object_id']);
    }
}
