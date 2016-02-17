<?php

namespace backend\modules\parse\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ParseOtchetFile;

/**
 * ParseOtchetFileSearch represents the model behind the search form about `common\models\ParseOtchetFile`.
 */
class ParseOtchetFileSearch extends ParseOtchetFile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parse_otchet_id'], 'integer'],
            [['file_name', 'path'], 'safe'],
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
        $query = ParseOtchetFile::find();

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
            'parse_otchet_id' => $this->parse_otchet_id,
        ]);

        $query->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
