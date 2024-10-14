<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApplicationType;

/**
 * ApplicationTypeSearch represents the model behind the search form about `app\models\ApplicationType`.
 */
class ApplicationTypeSearch extends ApplicationType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'keyword', 'description', 'date_created', 'date_updated'], 'safe'],
            [['price', 'iaiFee', 'lateFee','app_type'], 'number'],
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
        $query = ApplicationType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if(isset($params['ApplicationTypeSearch']['app_type']) && $params['ApplicationTypeSearch']['app_type'] == 3){
            unset($params['ApplicationTypeSearch']['app_type']);
        }
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $showArchived = isset($params['showArchived']) && $params['showArchived'];

        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'iaiFee' => $this->iaiFee,
            'lateFee' => $this->lateFee,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
            'app_type' => $this->app_type,
            'isArchived' => ($showArchived ? null : false)
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
