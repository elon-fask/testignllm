<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TestSession;

/**
 * TestSessionSearch represents the model behind the search form about `app\models\TestSession`.
 */
class TestSessionSearch extends TestSession
{
	private $curDate = '';
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'test_site_id','nick_id', 'staff_id', 'instructor_id', 'test_coordinator_id'], 'integer'],    /*wroten from me nick_id*/
            [['enrollmentType', 'start_date', 'end_date', 'date_created', 'date_updated', 'session_type', 'school'], 'safe'],
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
        $query = TestSession::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        		'sort' => ['attributes' => ['session_type' => [
	        		'asc' => ['type' => SORT_ASC, 'type' => SORT_ASC],
	        		'desc' => ['type' => SORT_DESC, 'type' => SORT_DESC],
	        		'default' => SORT_DESC
        		],
        		'test_site_id' => [
	        		'asc' => ['test_site.name' => SORT_ASC],
	        		'desc' => ['test_site.name' => SORT_DESC],
	        		'default' => SORT_DESC
        		],
                    /*wroten from me*/
                    'nick_id' => [
                        'asc' => ['test_site.nickname' => SORT_ASC],
                        'desc' => ['test_site.nickname' => SORT_DESC],
                        'default' => SORT_DESC
                    ],


        		'enrollmentType' => [
	        		'asc' => ['enrollmentType' => SORT_ASC],
	        		'desc' => ['enrollmentType' => SORT_DESC],
	        		'default' => SORT_DESC
        		],
        		'start_date' => [
        		'asc' => ['start_date' => SORT_ASC],
        		'desc' => ['start_date' => SORT_DESC],
        		'default' => SORT_DESC
        		],
        		'end_date' => [
        		'asc' => ['end_date' => SORT_ASC],
        		'desc' => ['end_date' => SORT_DESC],
        		'default' => SORT_DESC
        		],
        		]
        		]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'test_site_id' => $this->test_site_id,
            'nick_id' => $this->nick_id,    /*wroten from me*/
        	'staff_id' => $this->staff_id,
            'instructor_id' => $this->instructor_id,
            'test_coordinator_id' => $this->test_coordinator_id,
            'school' => $this->school,
            //'start_date' => $this->start_date,
            //'end_date' => $this->end_date,
            'date_created' => $this->date_created,
            'date_updated' => $this->date_updated,
        ]);
        $query->joinWith('testSite');
        if($this->session_type != ''){
            $query->andWhere('test_site.type = '.$this->session_type);
         //   $query->
        }
        $hasDate = false;
		//
		if($this->start_date != ''){
		    //this->start_date -> Y-m-d
		    $dateInfo = explode('-', $this->start_date);
		    $query->andWhere("start_date >= '".$dateInfo[2].'-'.$dateInfo[0].'-'.$dateInfo[1]."'");
		    $hasDate = true;
		}
		if($this->end_date != ''){
		    $dateInfo = explode('-', $this->end_date);
		    $query->andWhere("end_date <= '".$dateInfo[2].'-'.$dateInfo[0].'-'.$dateInfo[1]."'");
		    $hasDate = true;
		}
		
		if(!$hasDate){
		    $query->andWhere("end_date >= '".date('Y-m-d', strtotime('now'))."'");
		}
		if(isset($params['exclude']) && count($params['exclude']) > 0){
		    $query->andWhere("test_session.id not in (".implode(',', $params['exclude']).")");
		}
        $query->andFilterWhere(['like', 'test_session.enrollmentType', $this->enrollmentType]);
        //$query->orderBy('start_date asc');
        return $dataProvider;
    }
}
