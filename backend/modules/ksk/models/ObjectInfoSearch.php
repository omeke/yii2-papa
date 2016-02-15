<?php

namespace backend\modules\ksk\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ObjectInfo;

/**
 * ObjectInfoSearch represents the model behind the search form about `common\models\ObjectInfo`.
 */
class ObjectInfoSearch extends ObjectInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'amount_house', 'address_house', 'fullname_chairman', 'phone', 'object_id'], 'integer'],
            [['address_ksk'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ObjectInfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount_house' => $this->amount_house,
            'address_house' => $this->address_house,
            'fullname_chairman' => $this->fullname_chairman,
            'phone' => $this->phone,
            'object_id' => $this->object_id,
        ]);

        $query->andFilterWhere(['like', 'address_ksk', $this->address_ksk]);

        return $dataProvider;
    }
}
