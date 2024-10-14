<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use app\helpers\UtilityHelper;
use yii\web\Request;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$titlePage = 'Students';

if ($testSessionIdMd5Encoded != '') {
    $titlePage = 'Select Candidate To Add to Test Session';
}

$this->title = $titlePage;
$this->params['breadcrumbs'][] = $this->title;
$this->params['testSessionIdMd5Encoded'] = $testSessionIdMd5Encoded;

?>

<div class="candidates-index" style="margin-top: 50px;">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header'=> UtilityHelper::tableSortHeader('Last Name', 'last_name'),
                'attribute'=>'last_name',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('First Name', 'first_name'),
                'attribute'=>'first_name',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Company', 'company_name'),
                'attribute'=>'company_name'
            ],
            [
                'header' => 'Amount Owed',
                'value' => function($candidate) {
                    return '$' . number_format($candidate->amountOwed, 2, ".", ',');
                }
            ],
            [
                'header' => 'Classes',
                'format' => 'html',
                'value' => function($candidate) {
                    $classes = $candidate->allTestSession;
                    $testSessionsStr = array_reduce($classes, function($acc, $class) {
                        $testSession = $class->testSession;
                        $testSite = $testSession->testSite;
                        $linkStr = '(' . $testSite->typeStr . ') ' . $testSite->name . ' - ' . $testSession->dateRange;
                        $linkHref = '/admin/testsession/spreadsheet?id=' . $testSession->id;
                        return $acc . '<li><a href="' . $linkHref . '">' . $linkStr .'</a></li>';
                    }, '');
                    return '<ul>' . $testSessionsStr . '</ul>';
                }
            ],
            [
                'header' => 'Grades',
                'format' => 'html',
                'value' => function($candidate) {
                    try {
                        $previousSessions = $candidate->getPreviousSessions()->orderBy(['date_created' => SORT_DESC])->all();
                        if (count($previousSessions) == 0) {
                            return '<ul><li>No Grades Available</li></ul>';
                        }

                        $keyStr = [
                            'W_EXAM_CORE' => 'Written Core',
                            'W_EXAM_TLL' => 'Written SW',
                            'W_EXAM_ADD_TLL' => 'Written SW',
                            'W_EXAM_TSS' => 'Written FX',
                            'W_EXAM_ADD_TSS' => 'Written FX',
                            'W_EXAM_LBC' => 'Written LBC',
                            'W_EXAM_ADD_LBC' => 'Written LBC',
                            'W_EXAM_LBT' => 'Written LBT',
                            'W_EXAM_ADD_LBT' => 'Written LBT',
                            'W_EXAM_BTF' => 'Written BTF',
                            'W_EXAM_ADD_BTF' => 'Written BTF',
                            'W_EXAM_TOWER' => 'Written Tower',
                            'W_EXAM_ADD_TOWER' => 'Written Tower',
                            'W_EXAM_OVERHEAD' => 'Written Overhead',
                            'W_EXAM_ADD_OVERHEAD' => 'Written Overhead',
                            'P_TELESCOPIC_TLL' => 'Practical SW',
                            'P_TELESCOPIC_TSS' => 'Practical FX',
                            'P_LATTICE' => 'Practical LBC',
                            'P_TOWER' => 'Practical Tower',
                            'P_OVERHEAD' => 'Practical Overhead'
                        ];

                        $gradeStr = [
                            '0' => '<span style="color: #a94442; font-weight: bold">Fail</span>',
                            '1' => '<span style="color: #3c763d; font-weight: bold">Pass</span>',
                            '2' => '<span style="color: #8a6d3b; font-weight: bold">Did Not Test</span>',
                            '3' => '<span style="color: #8a6d3b; font-weight: bold">SD</span>'
                        ];

                        $gradesStr = array_reduce($previousSessions, function($acc, $session) use ($keyStr, $gradeStr, $candidate) {
                            $sessionGradesArr = json_decode($session->craneStatus);

                            $sessionType = isset($session->testSession->practical_test_session_id) ? 'Written' : 'Practical';
                            $typeStr = '<a href="/admin/candidates/update?id=' . md5($candidate->id) . '">' . $sessionType . ' (' . $session->testSession->dateRange . ')</a>';

                            if (count($sessionGradesArr) == 0) {
                                if (isset($session->remarks)) {
                                    return $acc . '<li>' . $typeStr . '<br /><ul><li>Remarks: ' . $session->remarks .'</li></ul></li>';
                                };
                                return $acc;
                            }

                            $sessionGradesStr = array_reduce($sessionGradesArr, function($acc, $grade) use ($keyStr, $gradeStr) {
                                $gradeVal = isset($grade->val) ? $gradeStr[$grade->val] : 'No Result';
                                return $acc . '<li>' . $keyStr[$grade->key] . ': ' . $gradeVal .'</li>';
                            }, '');
                            $sessionGradesTpl = '<ul>' . $sessionGradesStr . '</ul>';

                            if (isset($session->remarks)) {
                                $sessionGradesTpl = '<ul>' . $sessionGradesStr . '<li>Remarks: ' . $grade->remarks . '</li></ul>';
                            }

                            return $acc . '<li>' . $typeStr . '<br />' . $sessionGradesTpl . '</li>';
                        }, '');

                        return '<ul>' . $gradesStr . '</ul>';
                    } catch (Exception $e) {
                        return '<span>Error: Unable to process candidate\'s grades</span>';
                    }
                }
            ],
            [
                'label' => 'Is Purchase Order',
                'filter' => Html::activeDropDownList($searchModel, 'isPurchaseOrder', [''=>'All',0=>'No', 1=>'Yes'], ['class'=>'form-control']),
                'headerOptions'=>['style'=>'width:80px;'],
                'value' => function($model){
                    return $model->isPurchaseOrder == 1 ? 'Yes' : 'No';
                }
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('PO Number', 'purchase_order_number'),
                'attribute'=>'purchase_order_number',
            ],
            [
                'header'=> UtilityHelper::tableSortHeader('Invoice Number', 'invoice_number'),
                'attribute'=>'invoice_number',
            ],
            [   'label'=>'',
                'format'=>'raw',
                'headerOptions'=>['class'=>'action-cell'],
                'value'=> function($model){return UtilityHelper::buildActionWrapper('/admin/candidates', md5($model->id), false, extraLinks($model, $this), addExtraLinks($model, $this));}
            ],
        ],
    ]); ?>

</div>

<?php
function addExtraLinks($model, $el){
    $selectHtml = '';
    if($el->params['testSessionIdMd5Encoded'] != ''){
        $selectHtml = '<li>'.
            '<a class="select-roster" data-candidate-id="'.md5($model->id).'" data-session-id="'.$el->params['testSessionIdMd5Encoded'].'" href="javascript: SelectSession.choose(\''.md5($model->id).'\', \''.$el->params['testSessionIdMd5Encoded'].'\');">'.
            '<i class="fa fa-check" style="width:15px"></i>'.
            '<span style="font-size: 14px;">Select</span></a>'.
            '</li>';
    }

    if ($model->disregard) {
        return $selectHtml.'<li>
        <i style="width:15px" class="fa fa-trash"></i>
        <span style="font-size: 14px;">Student Not Signing Up</span>
        </li>';
    }

    if (!$model->disregard && $model->hasNoSession()) {
        return $selectHtml.'<li><a class="mark-student-not-signing-up"
        href="#"
        data-id="'.md5($model->id).'">
        <i style="width:15px" class="fa fa-trash"></i><span style="font-size: 14px;">Mark Student Not Signing Up </span>
        </a></li>';
    }

    return $selectHtml;
}
function extraLinks($model, $el){
    $rest= [
        [
            'label'=>'Account Balance',
            'url'=>'/admin/candidates/payment?id='.md5($model->id),
            'ico'=>'fa-usd'
        ]
    ];

    if($model->hasAppForms()) {
        array_push( $rest, [
                'label' => 'Download App Forms',
                'url' => '/register/form?cId='.base64_encode($model->id).'&i='.md5($model->id),
                'ico' => 'fa-download',
                'target' => '_blank'
            ]
        );
    }

    array_push( $rest, [
            'label' => 'Clone Application',
            'url' => '/admin/candidates/create?id='.md5($model->id),
            'ico' => 'fa-copy'
        ]
    );

    return $rest;
}
?>

<script>
    <?php if ($this->params['testSessionIdMd5Encoded'] != '') { ?>
    $('.pop-content li a').hide();
    $('li a.select-roster').show();
    <?php } ?>

    $('#main-container').removeClass('container');
    $('#main-container').addClass('container-fluid');
    $('#main-container').css('margin-top', '10px');

    $(document).on('click', '.mark-student-not-signing-up', function(e) {
        e.preventDefault();
        var el = $(this);
        var cId = el.data('id');

        $.confirm({
            title:'Student Not Signing up',
            confirmButton: 'Yes, Mark as Not Signing up',
            cancelButton:'No, Cancel',
            content: 'Are you sure you want to mark this student as not signing up?',
            confirm: function() {
                markStudentNotSigningUp(cId, false, '');
            }
        });
    });
</script>

<style>
    .pop-content ul{width: 125px;}
    .candidates-index table tr th{vertical-align: middle}
</style>
