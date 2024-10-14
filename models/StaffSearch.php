<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StaffSearch represents the model behind the search form about `app\models\Staff`.
 */
class StaffSearch extends Staff
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'staffType'], 'integer'],
            [['firstName', 'lastName','phone','fax', 'email', 'date_created', 'date_updated', 'archived'], 'safe'],
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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'archived' => $this->archived
        ]);

        $query->andFilterWhere(['like', 'firstName', $this->firstName])
        ->andFilterWhere(['like', 'phone', $this->phone])
        ->andFilterWhere(['like', 'email', $this->email])
        ->andFilterWhere(['like', 'lastName', $this->lastName]);

        return $dataProvider;
    }
}
