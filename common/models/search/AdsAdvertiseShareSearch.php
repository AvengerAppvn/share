<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AdsAdvertiseShare;

/**
 * AdsAdvertiseShareSearch represents the model behind the search form about `common\models\AdsAdvertiseShare`.
 */
class AdsAdvertiseShareSearch extends AdsAdvertiseShare
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ads_id', 'province_id', 'age_id', 'speciality_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
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
        $query = AdsAdvertiseShare::find();

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
            'ads_id' => $this->ads_id,
            'province_id' => $this->province_id,
            'age_id' => $this->age_id,
            'speciality_id' => $this->speciality_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
