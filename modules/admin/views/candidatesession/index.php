<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidateSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $testSession->getTestSiteName().' - '.$testSession->getDateInfo().' - Candidates ';
$this->params['breadcrumbs'][] = $this->title;
$this->params['testSessionId'] = $testSession->id;

$searchParams = '';

$params = [];
if(isset($_GET['CandidateSessionSearch'])){
    foreach($_GET['CandidateSessionSearch'] as $key => $val){
        $params[] = 'CandidateSessionSearch['.$key.']='.$val;
        // }
    }
    
}
$params[] = 'i='.$_GET['i'];
$searchParams = implode('&', $params);
$this->params['queryParams'] = $searchParams;
$this->params['typeId'] = $testSession->getTestSessionTypeId();
?>
<script>
$(function() {
	$('.clear-sorting').on('click', RosterSession.reset);
	
	for(var x = 1 ; x < 4 ; x++){
		for( var i in RosterSession.sorts){
				var className = '';
				if(RosterSession.sorts[i] == 'firstName'){
					className = 'by-first-name';
				}else if(RosterSession.sorts[i] == 'lastName'){
					className = 'by-last-name';
				}else if(RosterSession.sorts[i] == 'certification'){
					className = 'by-certification';
				}
				$('.sort-option'+x+' .'+className).hide();

		}
	}
	for(var i = 0 ; i < 4 ; i++){
		$('.sort-option'+i+' .btn-info .text').html('Add New Sorting');
		$('.clear-sorting').data('sort-'+i, '');		
		$('.sort-option'+i).data('sort-type', '');	
	}
	
	RosterSession.setupUI('<?php echo isset($_GET['sort0']) ? $_GET['sort0'] : ''?>', '<?php echo isset($_GET['sort1']) ? $_GET['sort1'] : ''?>', '<?php echo isset($_GET['sort2']) ? $_GET['sort2'] : ''?>', '<?php echo isset($_GET['sort3']) ? $_GET['sort3'] : ''?>');
});
var RosterSession = {
	sorts : ['certification', 'firstName', 'lastName'],
	sortBy : function(type, dir, section, reload){
		if(type == 'certification'){
			var txt = dir == 'asc' ? 'New Certification First' : 'Recertification First';
			$('.sort-option'+section+' .btn-info .text').html(txt);
		}else if(type == 'firstName'){
			var txt = dir == 'asc' ? 'First Name Asc' : 'First Name Desc';
			$('.sort-option'+section+' .btn-info .text').html(txt);
		}else if(type == 'lastName'){
			var txt = dir == 'asc' ? 'Last Name Asc' : 'Last Name Desc';
			$('.sort-option'+section+' .btn-info .text').html(txt);
		}	

		RosterSession.hideFilter(section, type);

		$('.sort-option'+section).data('sort-type', type);
		$('.clear-sorting').data('sort-'+section, type+'-'+dir);	

		//we show the next
		var next = 	section + 1;
		if($('.sort-option'+next).length == 1){
			$('.sort-option'+next).show();
			//we hide all the rest that have been chosen
		}
		if(reload)
			RosterSession.doPageSort();	
	},
	hideFilter : function(section, type){
		for( var i in RosterSession.sorts){
			if(RosterSession.sorts[i] != type){
				var className = '';
				if(RosterSession.sorts[i] == 'firstName'){
					className = 'by-first-name';
				}else if(RosterSession.sorts[i] == 'lastName'){
					className = 'by-last-name';
				}else if(RosterSession.sorts[i] == 'certification'){
					className = 'by-certification';
				}
				$('.sort-option'+section+' .'+className).hide();
			}
		}
		var next = 	section + 1;
		if($('.sort-option'+next).length == 1 && $('.sort-option'+next).data('sort-type') == ''){
			//we hide other fields
			for( var i in RosterSession.sorts){
				if(RosterSession.sorts[i] != type){
					var className = '';
					if(RosterSession.sorts[i] == 'firstName'){
						className = 'by-first-name';
					}else if(RosterSession.sorts[i] == 'lastName'){
						className = 'by-last-name';
					}else if(RosterSession.sorts[i] == 'certification'){
						className = 'by-certification';
					}
					var isFound = false;
					for(var x = 0 ; x < next ; x++){
						if($('.sort-option'+x).data('sort-type') == RosterSession.sorts[i]){
							isFound = true;
							break;
						}
					}
					if(isFound == false)
						$('.sort-option'+next+' .'+className).show();
				}
			}
		}	 
	},
	reset : function(){
		for(var i = 0 ; i < 4 ; i++){
			$('.sort-option'+i+' .btn-info .text').html('Add New Sorting');
			$('.clear-sorting').data('sort-'+i, '');		
			$('.sort-option'+i).data('sort-type', '');	
		}
		
		$('.sort-option1, .sort-option2, .sort-option3').hide();		

			
		RosterSession.doPageSort();
	},
	doPageSort : function(){
		var url = '/admin/candidatesession?'+ '<?php echo $searchParams?>' + '&sort0='+$('.clear-sorting').data('sort-0') + '&sort1='+$('.clear-sorting').data('sort-1') + '&sort2='+$('.clear-sorting').data('sort-2') + '&sort3='+$('.clear-sorting').data('sort-3');
		window.location.href = url
		
	},
	setupUI : function(sort1, sort2, sort3, sort4){		
		if(sort1 !=''){
			var param = sort1.split('-');
			RosterSession.sortBy(param[0], param[1], 0, false);
		}
		if(sort2 !=''){
			var param = sort2.split('-');
			RosterSession.sortBy(param[0], param[1], 1, false);
		}
		if(sort3 !=''){
			var param = sort3.split('-');
			RosterSession.sortBy(param[0], param[1], 2, false);
		}
		if(sort4 !=''){
			var param = sort4.split('-');
			RosterSession.sortBy(param[0], param[1], 3, false);
		}
		
	}
}
</script>


<div class="candidate-session-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class='row'>
        <div class='col-xs-12'>
            <label class='control-label'><h4>Type: <?= $testSession->getTestSessionType() ?></h4></label>
        </div>
        <div class='col-xs-12'>
            <label class='control-label'><h4>Total Candidates: <?= $totalCandidates ?></h4></label>
        </div>
    </div>
    <p>
        <?= Html::a('Add Student', ['/admin/candidates?i='.md5($testSession->id)], ['class' => 'btn btn-success']) ?>
    </p>
    <div class='form-group'>
        <label>Sort By: </label>
        <button class="btn btn-info clear-sorting" data-sort-0='' data-sort-1='' data-sort-2='' data-sort-3='' type="button">Clear Sorting</button>
        <div class="btn-group sort-option0" data-sort-type=''> 
            
           <button style="width: 220px" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class='text'>Add New Sorting</span> <span class="caret"></span>
          </button>
            <ul class="dropdown-menu"> 
                <label class='by-certification'>&nbsp;By Certification/Recertifcation&nbsp;</label>
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','asc', 0, true)">New Certification First</a></li> 
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','desc', 0, true)">Recertification First</a></li>
                 <label  class='by-first-name'>&nbsp;By First Name&nbsp;</label> 
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','asc', 0, true)">First Name Asc</a></li>
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','desc', 0, true)">First Name Desc</a></li>
                 <label  class='by-last-name'>&nbsp;By Last Name&nbsp;</label> 
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','asc', 0, true)">Last Name Asc</a></li>
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','desc', 0, true)">Last Name Desc</a></li>    
            </ul> 
        </div> 
        
        <div class="btn-group sort-option1" data-sort-type='' style='display: none'>            
             <button style="width: 220px" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class='text'>Add New Sorting</span> <span class="caret"></span>
          </button>
            <ul class="dropdown-menu"> 
                <label class='by-certification'>&nbsp;By Certification/Recertifcation&nbsp;</label>
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','asc', 1, true)">New Certification First</a></li> 
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','desc', 1, true)">Recertification First</a></li>
                 <label  class='by-first-name'>&nbsp;By First Name&nbsp;</label> 
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','asc', 1, true)">First Name Asc</a></li>
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','desc', 1, true)">Last Name Desc</a></li>
                 <label  class='by-last-name'>&nbsp;By Last Name&nbsp;</label> 
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','asc', 1, true)">Last Name Asc</a></li>
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','desc', 1, true)">Last Name Desc</a></li>        
            </ul> 
        </div> 
        
        <div class="btn-group sort-option2" data-sort-type='' style='display: none'>            
             <button style="width: 220px" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class='text'>Add New Sorting</span> <span class="caret"></span>
          </button>
            <ul class="dropdown-menu"> 
                 <label class='by-certification'>&nbsp;By Certification/Recertifcation&nbsp;</label>
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','asc', 2, true)">New Certification First</a></li> 
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','desc', 2, true)">Recertification First</a></li>
                 <label  class='by-first-name'>&nbsp;By First Name&nbsp;</label> 
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','asc', 2, true)">First Name Asc</a></li>
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','desc', 2, true)">Last Name Desc</a></li>
                 <label  class='by-last-name'>&nbsp;By Last Name&nbsp;</label> 
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','asc', 2, true)">Last Name Asc</a></li>
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','desc', 2, true)">Last Name Desc</a></li>     
            </ul> 
        </div>
        
        <div class="btn-group sort-option3" data-sort-type='' style='display: none'>            
             <button style="width: 220px" type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class='text'>Add New Sorting</span> <span class="caret"></span>
          </button>
            <ul class="dropdown-menu"> 
                 <label class='by-certification'>&nbsp;By Certification/Recertifcation&nbsp;</label>
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','asc', 3, true)">New Certification First</a></li> 
                    <li><a class='by-certification' href="javascript: RosterSession.sortBy('certification','desc', 3, true)">Recertification First</a></li>
                 <label  class='by-first-name'>&nbsp;By First Name&nbsp;</label> 
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','asc', 3, true)">First Name Asc</a></li>
                    <li><a class='by-first-name' href="javascript: RosterSession.sortBy('firstName','desc', 3, true)">Last Name Desc</a></li>
                 <label  class='by-last-name'>&nbsp;By Last Name&nbsp;</label> 
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','asc', 3, true)">Last Name Asc</a></li>
                    <li><a class='by-last-name' href="javascript: RosterSession.sortBy('lastName','desc', 3, true)">Last Name Desc</a></li>   
            </ul> 
        </div>
    </div>

    <?php if(\Yii::$app->getSession()->hasFlash('success')){?>
<div class="">
<div class="alert alert-success">
    <?php echo \Yii::$app->getSession()->getFlash('success'); ?>
</div>
</div>
<?php } ?>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'header'=> UtilityHelper::tableSortHeader('Last Name', 'last_name'),
            'label' => 'Last Name',
            'attribute' => 'last_name',
            'value' => function ($model) {
                return $model->getLastName();
            }
        ], [
            'header'=> UtilityHelper::tableSortHeader('First Name', 'first_name'),
            'label' => 'First Name',
            'attribute' => 'first_name',
            'value' => function ($model) {
                return $model->getFirstName();
            }
        ], [
            'header'=> UtilityHelper::tableSortHeader('Application Type', 'application_type_id'),
            'label' => 'Application Type',
            'attribute' => 'application_type_id',
            'filter' => Html::activeDropDownList(
                $searchModel, 'application_type_id',
                UtilityHelper::getApplicationTypes(),
                ['class' =>' form-control', 'prompt' => 'Select Type']
            ),
            'value' => function ($model) {
                return $model->getCandidateApplicationTypeDesc();
            }
        ], [
            'header'=> UtilityHelper::tableSortHeader('Total Charges', 'amount', 'numeric'),
            'label' => 'Total Charges',
            'attribute' => 'amount',
            'headerOptions'=>['style' => 'width:100px'],
            'value' => function ($model) {
                $totals = $model->candidate->transactionTotals;
                if ($totals['totalNetPayable'] == 0) {
                    return '-';
                }

                return '$' . number_format($totals['totalNetPayable'], 2);
            }
        ], [
            'header'=> UtilityHelper::tableSortHeader('Promo Code', 'promoCode'),
            'label' => 'Promo Code',
            'attribute' => 'promoCode',
            'headerOptions' => ['style' => 'width:100px'],
            'value' => function ($model) {
                return $model->getCandidatePromoCode() == null ? ' - ' : $model->getCandidatePromoCode();
            }
        ], [
            'header'=> UtilityHelper::tableSortHeader('Is Purchase Order', 'isPurchaseOrder'),
            'label' => 'Is Purchase Order',
            'attribute' => 'isPurchaseOrder',
            'headerOptions' => ['style' => 'width:100px'],
            'filter' => Html::activeDropDownList(
                $searchModel,
                'isPurchaseOrder',
                ['' => 'All', 0 => 'No', 1 => 'Yes'],
                ['class' => 'form-control']
            ),
            'value' => function ($model) {
                return $model->getCandidateIsPurchaseOrder();
            }
        ], [
            'label' => '',
            'format'=>'raw',
            'headerOptions' => ['class' => 'action-cell'],
            'value' => function ($model) {
                return UtilityHelper::buildActionWrapper(
                    '/admin/candidates', md5($model->candidate_id),
                    false,
                    extraLinks($model, $this),
                    addExtraLinks($model, $this->params['testSessionId'])
                );
            }
        ]
    ]
]);
?>
</div>

<?php
function extraLinks ($model, $el) {
    $rest = [
        [
            'label' => 'Account Balance',
            'url' => '/admin/candidates/account-balance?id='.md5($model->candidate_id).'&i='.md5($el->params['testSessionId']),
            'ico' => ' fa-usd '
        ]
    ];

    array_push($rest, [
        'label' => 'Assign to Class',
        'url' => 'javascript: assignClass("'.base64_encode($model->test_session_id).'", "'.base64_encode($model->candidate_id).'")',
        'ico' => ' fa-calendar-plus-o '
    ]);

    return $rest;
}

function addExtraLinks($model, $testSessionId){
    $linkDelHTML = '<i class="fa fa-trash" style="width:15px"></i><span style="font-size: 14px;"> Cancel Session</span>';
    $linkDel = Html::a(
        $linkDelHTML,
        ['/admin/candidatesession/delete',
        'id' => md5($model->id),
        'i' => md5($testSessionId)],
        [
            'class' => 'link-delete',
            'data-confirm' => "Are you sure you want to cancel this student from the session?",
            'data-method' => 'post'
        ]
    );

    $linkDownAppForm = "";

    if ($model->getCandidate()->all()[0]->hasAppForms()) {
        $linkDownApp = '<i class="fa fa-download" style="width:15px"></i><span style="font-size: 14px;"> Download App Forms</span>';
        $linkDownAppForm = Html::a(
            $linkDownApp,
            ['/register/form', 'cId' => base64_encode($model->candidate_id), 'i' => md5($model->candidate_id)],
            ['class' => 'link-downloadAppF', 'target' => '_blank']
        );
        $linkDownAppForm = '<li>' . $linkDownAppForm . '</li>';
    }

    return $linkDownAppForm . '<li>' . $linkDel . '</li>';
}
?>

<style>
    .candidate-session-index table tr th{
        vertical-align: middle;
    }
</style>
