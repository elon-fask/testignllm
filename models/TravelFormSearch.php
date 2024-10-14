<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TravelForm;

/**
 * TravelFormSearch represents the model behind the search form of `app\models\TravelForm`.
 */
class TravelFormSearch extends TravelForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hotel_required', 'car_rental_required'], 'integer'],
            [['name', 'destination_loc', 'destination_date', 'destination_time', 'return_loc', 'return_date', 'return_time', 'comment', 'notes', 'created_at', 'updated_at'], 'safe'],
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
        $query = TravelForm::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC
                ]
            ]
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
            'destination_date' => $this->destination_date,
            'return_date' => $this->return_date,
            'hotel_required' => $this->hotel_required,
            'car_rental_required' => $this->car_rental_required,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'destination_loc', $this->destination_loc])
            ->andFilterWhere(['like', 'destination_time', $this->destination_time])
            ->andFilterWhere(['like', 'return_loc', $this->return_loc])
            ->andFilterWhere(['like', 'return_time', $this->return_time])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
