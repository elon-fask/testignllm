<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Candidates;

/**
 * CandidatesSearch represents the model behind the search form about `app\models\Candidates`.
 */
class CandidatesSearch extends Candidates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'application_type_id'], 'integer'],
            [['first_name','isPurchaseOrder', 'last_name','registration_step', 'birthday', 'middle_name', 'email', 'phone', 'address', 'city', 'state', 'zip', 'company_name', 'company_fax', 'company_phone', 'company_address', 'company_city', 'company_state', 'company_zip', 'contact_person', 'purchase_order_number', 'invoice_number', 'date_created', 'date_updated', 'contactEmail'], 'safe'],
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

        $query = Candidates::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($params['combinedQuery']) && isset($params['query']) && $params['combinedQuery'] == 1) {
            $query->where(['like', 'last_name', $params['query']]);
            $query->orWhere(['like', 'first_name', $params['query']]);
            $query->orWhere(['like', 'email', $params['query']]);

            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'application_type_id' => $this->application_type_id,
            'isPurchaseOrder' => $this->isPurchaseOrder,
            'purchase_order_number' => $this->purchase_order_number,
            'invoice_number' => $this->invoice_number
        ]);

        if (isset($params['startDate']) && isset($params['endDate'])) {
            $startDate = new \DateTime($params['startDate']);
            $endDate = new \DateTime($params['endDate']);

            $query->andFilterWhere(['>=', 'date_created', $startDate->format('Y-m-d H:i:s')]);
            $query->andFilterWhere(['<=', 'date_created', $endDate->format('Y-m-d H:i:s')]);
        }

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'middle_name', $this->middle_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zip', $this->zip])
            ->andFilterWhere(['like', 'birthday', $this->birthday])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'company_fax', $this->company_fax])
            ->andFilterWhere(['like', 'company_phone', $this->company_phone])
            ->andFilterWhere(['like', 'company_address', $this->company_address])
            ->andFilterWhere(['like', 'company_city', $this->company_city])
            ->andFilterWhere(['like', 'company_state', $this->company_state])
            ->andFilterWhere(['like', 'company_zip', $this->company_zip])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'contactEmail', $this->contactEmail]);

        return $dataProvider;
    }
}
