<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Uploads;

/**
 * UploadsSearch represents the model behind the search form about `app\models\Uploads`.
 */
class UploadsSearch extends Uploads
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'isDeleted', 'uploaded_by'], 'integer'],
            [['name', 'description', 'date_created'], 'safe'],
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
        $query = Uploads::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'isDeleted' => $this->isDeleted,
            'uploaded_by' => $this->uploaded_by,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
