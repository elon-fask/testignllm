<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'photo', 'active'], 'integer'],
            [['first_name', 'last_name', 'username', 'password', 'homePhone', 'cellPhone', 'workPhone', 'city', 'state', 'zip', 'address1', 'date_created', 'date_updated', 'email', 'staffType'], 'safe'],
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
            'role' => $this->role,
            'photo' => $this->photo,
            'active' => $this->active,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
        ]);

        if (isset($params['merge_primary'])) {
            $query->andWhere(['<>', 'id', $params['merge_primary']]);
        }

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'homePhone', $this->homePhone])
            ->andFilterWhere(['like', 'cellPhone', $this->cellPhone])
            ->andFilterWhere(['like', 'workPhone', $this->workPhone])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zip', $this->zip])
            ->andFilterWhere(['like', 'address1', $this->address1]);
        $query->andWhere("username != 'root'");
        return $dataProvider;
    }
}
