<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ChecklistTemplate;

/**
 * ChecklistTemplateSearch represents the model behind the search form about `app\models\ChecklistTemplate`.
 */
class ChecklistTemplateSearch extends ChecklistTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'isArchived'], 'integer'],
            [['name', 'date_created'], 'safe'],
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
        $query = ChecklistTemplate::find();

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
            'type' => $this->type,
            'isArchived' => $this->isArchived,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andWhere('type not in ('.self::TYPE_PRACTICAL.', '.self::TYPE_WRITTEN_CALENDAR_CHECKLIST.') ');

        return $dataProvider;
    }
}
