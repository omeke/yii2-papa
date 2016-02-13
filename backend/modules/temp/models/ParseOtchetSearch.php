<?php

namespace backend\modules\temp\models;

use common\models\ParseKsk;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ParseOtchet;

/**
 * ParseOtchetSearch represents the model behind the search form about `common\models\ParseOtchet`.
 */
class ParseOtchetSearch extends ParseOtchet
{
    public $region_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parse_ksk_id', 'region_id'], 'integer'],
            [['name', 'url_otchet', 'posted_at', 'updated_at'], 'safe'],
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
        $query = ParseOtchet::find();
        $query->joinWith(['parseKsk']);

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
            ParseKsk::tableName().'.parse_region_id' => $this->region_id,
            'parse_ksk_id' => $this->parse_ksk_id,
            'posted_at' => $this->posted_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'url_otchet', $this->url_otchet]);

        return $dataProvider;
    }
}
