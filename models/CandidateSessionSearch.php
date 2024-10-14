<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CandidateSession;

/**
 * CandidateSessionSearch represents the model behind the search form about `app\models\CandidateSession`.
 */
class CandidateSessionSearch extends CandidateSession
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'candidate_id', 'test_session_id', 'application_type_id'], 'integer'],
            [['date_created', 'date_updated', 'promoCode','isPurchaseOrder', 'candidateName', 'first_name', 'last_name'], 'safe']
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
        $query = CandidateSession::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['candidateName' => [
                'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
                'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
                'default' => SORT_DESC
            ],
                'first_name' => [
                    'asc' => ['first_name' => SORT_ASC],
                    'desc' => ['first_name' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'last_name' => [
                    'asc' => ['last_name' => SORT_ASC],
                    'desc' => ['last_name' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'application_type_id' => [
                    'asc' => ['application_type.name' => SORT_ASC],
                    'desc' => ['application_type.name' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'promoCode' => [
                    'asc' => ['referralCode' => SORT_ASC],
                    'desc' => ['referralCode' => SORT_DESC],
                    'default' => SORT_DESC
                ],
                'isPurchaseOrder' => [
                    'asc' => ['isPurchaseOrder' => SORT_ASC],
                    'desc' => ['isPurchaseOrder' => SORT_DESC],
                    'default' => SORT_DESC
                ],
            ],
                'enableMultiSort' => true,
            ],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['candidate']);
        $query->join("LEFT JOIN", 'application_type', 'application_type.id = application_type_id');
        $query->andFilterWhere([
            'id' => $this->id,
            'candidate_id' => $this->candidate_id,
            'test_session_id' => $this->test_session_id,
            'application_type_id' => $this->application_type_id,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'isPurchaseOrder' => $this->isPurchaseOrder,
        ]);

        $query->andFilterWhere(['like', 'referralCode', $this->promoCode])
            ->andFilterWhere(['like', "first_name", $this->first_name])
            ->andFilterWhere(['like', "last_name", $this->last_name]);

        for ($x = 0 ; $x < 4 ; $x++) {
            if(isset($params['sort'.$x]) && $params['sort'.$x] != ''){
                $query->addOrderBy($params['sort'.$x]);

            }
        }

        return $dataProvider;
    }
}
