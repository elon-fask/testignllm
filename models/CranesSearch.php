<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cranes;

/**
 * CranesSearch represents the model behind the search form about `app\models\Cranes`.
 */
class CranesSearch extends Cranes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cad', 'weightCerts', 'loadChart', 'manual', 'certificate', 'preChecklistId', 'postChecklistId', 'isDeleted'], 'integer'],
            [['model', 'manufacturer', 'unitNum', 'serialNum', 'certificateExpirateDate', 'companyOwner', 'date_created'], 'safe'],
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
        $query = Cranes::find();

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
            'cad' => $this->cad,
            'weightCerts' => $this->weightCerts,
            'loadChart' => $this->loadChart,
            'manual' => $this->manual,
            'certificate' => $this->certificate,
            'preChecklistId' => $this->preChecklistId,
            'postChecklistId' => $this->postChecklistId,
            'date_created' => $this->date_created,
            'isDeleted' => $this->isDeleted,
        ]);

        $query->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'manufacturer', $this->manufacturer])
            ->andFilterWhere(['like', 'unitNum', $this->unitNum])
            ->andFilterWhere(['like', 'serialNum', $this->serialNum])
            ->andFilterWhere(['like', 'certificateExpirateDate', $this->certificateExpirateDate])
            ->andFilterWhere(['like', 'companyOwner', $this->companyOwner]);

        return $dataProvider;
    }
}
