<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PromoCodes;

/**
 * PromoCodesSearch represents the model behind the search form about `app\models\PromoCodes`.
 */
class PromoCodesSearch extends PromoCodes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'assignedToName','isPurchaseOrder', 'date_created', 'date_updated', 'archived'], 'safe'],
            [['discount'], 'number'],
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
        $query = PromoCodes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if(isset($params['PromoCodesSearch']['archived']) && $params['PromoCodesSearch']['archived'] == 2){
            unset($params['PromoCodesSearch']['archived']);
        }
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'discount' => $this->discount,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'isPurchaseOrder' => $this->isPurchaseOrder,
            'archived' => $this->archived,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'assignedToName', $this->assignedToName]);

        return $dataProvider;
    }
}
