<?php

namespace common\models\search;

use common\models\Transaction;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TransactionSearch represents the model behind the search form about `common\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    public $max_date;
    public $min_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['description'], 'safe'],
            [['logtime', 'min_date', 'max_date'], 'safe'],
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
        $query = Transaction::find();

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
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function searchTransaction($params)
    {
        if (!($this->load($params) && $this->validate())) {
            return new ActiveDataProvider([
                'query' => Transaction::find()->where(['user_id' => $this->user_id])->orderBy('logtime DESC'),
                'pagination' => [
                    'pageSize' => 5,
                ]
            ]);
        }

        $query = Transaction::find()->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'logtime', $this->min_date, $this->max_date])->orderBy('logtime DESC');
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);
    }
}
