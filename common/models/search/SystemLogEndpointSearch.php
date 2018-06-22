<?php

namespace common\models\search;

use common\models\SystemLogEndpoint;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SystemLogEndpointSearch represents the model behind the search form about `app\models\SystemLog`.
 */
class SystemLogEndpointSearch extends SystemLogEndpoint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['action', 'method', 'param', 'result'], 'safe'],
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
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SystemLogEndpoint::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'action' => $this->action,
            'method' => $this->method,
        ]);

        $query->andFilterWhere(['like', 'param', $this->param])
            ->andFilterWhere(['like', 'result', $this->result]);

        return $dataProvider;
    }
}
