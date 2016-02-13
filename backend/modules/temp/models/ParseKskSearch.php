<?php

namespace backend\modules\temp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ParseKsk;

/**
 * ParseKskSearch represents the model behind the search form about `common\models\ParseKsk`.
 */
class ParseKskSearch extends ParseKsk
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parse_region_id', 'color'], 'integer'],
            [['name', 'url_ksk', 'url_otchet', 'updated_at'], 'safe'],
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
        $query = ParseKsk::find();

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
            'parse_region_id' => $this->parse_region_id,
            'color' => $this->color,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url_ksk', $this->url_ksk])
            ->andFilterWhere(['like', 'url_otchet', $this->url_otchet]);

        return $dataProvider;
    }
}
