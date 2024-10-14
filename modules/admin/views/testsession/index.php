<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\helpers\UtilityHelper;
use app\models\TestSite;
use dosamigos\datepicker\DatePicker;
use app\models\TestSession;
use app\models\Candidates;
use app\models\ChecklistTemplate;
//use yii;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TestSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$candidate = false;
$titlePage = 'Test Sessions';

$isRetake = 0;
if (isset($_REQUEST['retake']) && $_REQUEST['retake'] == 1) {
    $isRetake = 1;
}

if ($md5CandidateId != '') {
    $candidates = Candidates::find()->where("md5(id) ='" . $md5CandidateId . "'")->all();
    if (count($candidates) != 0) {
        $candidate = $candidates[0];
        $titlePage = 'Move ' . $candidate->getFullName() . ' to Test Session';

        if ($isRetake == 1) {
            $titlePage = '(Retake) : Move ' . $candidate->getFullName() . ' to Test Session';
        }
    }
}

$this->title = $titlePage;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
$this->params['md5CandidateId'] = $md5CandidateId;


$searchParams = '';

$params = [];
if (isset($_GET['TestSessionSearch'])) {
    foreach ($_GET['TestSessionSearch'] as $key => $val) {
        $params[] = 'TestSessionSearch[' . $key . ']=' . $val;
    }
}
$searchParams = implode('&', $params);
$this->params['queryParams'] = $searchParams;

function buildCustomDeleteLink($siteId, $searchParams = false, $el, $type, $details)
{
    if ($searchParams !== false && $searchParams !== '') {
        $searchParams = '&' . $searchParams;
    }

    $selectHtml = '';

    if ($el->params['md5CandidateId'] != '') {
        $selectHtml = '<li>' .
            '<a class="select-roster" data-candidate-id="' . $el->params['md5CandidateId'] . '" data-session-id="' . md5($siteId) . '" href="javascript: SelectSession.choose(\'' . $el->params['md5CandidateId'] . '\', \'' . md5($siteId) . '\', \'' . $details['transferType'] . '\', \'' . $details['bothTestSessions'] . '\');">' .
            '<i class="fa fa-check" style="width:15px"></i>' .
            '<span style="font-size: 14px;">Move</span></a>' .
            '</li>';
    }

    if (UtilityHelper::isSuperAdmin()) {
        $selectHtml .= '<li>' .
            '<a class="select-roster" href="/admin/class-schedule/?id='.md5($siteId).'">' .
            '<i class="fa fa-calendar-check-o" style="width:15px"></i>' .
            '<span style="font-size: 14px;"> Manage Class</span></a>' .
            '</li>';

        return $selectHtml . '<li>' .
        '<a class="link-delete-session-async" data-id="'.$siteId.'" href="/admin/testsession/deleteasync">' .
        '<i class="fa fa-trash" style="width:15px"></i>' .
        '<span style="font-size: 14px;"> Delete</span></a>' .
        '</li>';
    }
    return $selectHtml;
}

function extraLinks($model, $el)
{
    $rest = [
        [
            'label' => 'View Roster',
            'url' => '/admin/candidatesession?i=' . md5($model->id),
            'ico' => 'fa-list',
        ]
    ];

    if ($el->params['md5CandidateId'] != '') {
        array_push($rest, [
                'label' => 'Select',
                'url' => '/admin/candidates/select?id=' . $el->params['md5CandidateId'] . '&i=' . md5($model->id),
                'ico' => 'fa-check'
            ]
        );
    }

    array_push($rest, [
    'label' => 'ChecklistTemplate',
    'url' => 'javascript: ChecklistTemplate.viewSession('.$model->id.');',
    'ico' => 'fa-list-alt'
        ]
    );
    
    return $rest;
}
?>
<!--<style>-->
<!--    tbody tr>td:nth-child(4) form{-->
<!--        display: none;-->
<!--    }-->
<!--</style>-->
<div class="test-session-index" data-is-retake='<?php echo $isRetake?>'>

    <?php if (isset($s) && $s == '0' && $s !== false) { ?>
        <div class="alert alert-danger">Unable to delete test session, there are students enrolled in the session</div>
    <?php } else if (isset($s) && $s == '1' && $s !== false) { ?>
        <div class="alert alert-success">Delete Successful</div>
    <?php } ?>

    <div class="row row-header">
        <div class="col-xs-12 col-md-8">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">

            <?php
            $sesTypes = [TestSite::TYPE_PRACTICAL => 'Practical', TestSite::TYPE_WRITTEN => 'Written'];
            $isMoveStudent = false;
            if ($md5CandidateId != '') {
                $isMoveStudent = true;
                if ($sesDefType != '' && $sesDefType == TestSite::TYPE_PRACTICAL) {
                    $sesTypes = [TestSite::TYPE_PRACTICAL => 'Practical'];
                } else if ($sesDefType != '' && $sesDefType == TestSite::TYPE_WRITTEN) {
                    $sesTypes = [TestSite::TYPE_WRITTEN => 'Written'];
                }
            }
            ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'School',
                        'attribute' => 'school',
                        'filter' => Html::activeDropDownList($searchModel, 'school', [TestSession::SCHOOL_CCS => TestSession::SCHOOL_CCS, TestSession::SCHOOL_ACS => TestSession::SCHOOL_ACS], ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->school;
                        },
                    ],
                    [
                        'label' => 'Session Type',
                        'filter' => Html::activeDropDownList($searchModel, 'session_type', $sesTypes, ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->getTestSessionType();
                        },
                    ],
                    [
                        'label' => 'Test Site',
                        'attribute' => 'test_site_id',
                        'header' => UtilityHelper::tableSortHeader('Test Site', 'test_site_id'),
                        'filter' => Html::activeDropDownList($searchModel, 'test_site_id', UtilityHelper::getAllTestSites(), ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->getTestSiteName();
                        },
                    ],
                        /*wroten from me*/
                    [
                        'label' => 'Nickname',
                        'attribute' => 'nick_id',
                        'header' => UtilityHelper::tableSortHeader('Nickname', 'nick_id'),
                        'filter' => Html::activeDropDownList($searchModel, 'nick_id', UtilityHelper::getAllTestSites('nick_id'), ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                           // return $model->getTestSiteName('nick_id');
                             return Html::beginForm($action = '', $method = 'post', $options = []). Html::activeInput('text',$model,'nick_id', $options = ['id'=>base64_encode($model->id),'value'=>trim($model->getTestSiteName('nick_id'))]).Html::endForm();
                        },
                    ],
                    [
                        'label' => 'Enrollment Type',
                        'filter' => Html::activeDropDownList($searchModel, 'enrollmentType', UtilityHelper::getEnrollmentTypes(), ['class' => 'form-control', 'prompt' => 'Show All']),
                        'value' => function ($model) {
                            return $model->getEnrollmentTypeDescription();
                        },
                    ],
                    [
                        'attribute' => 'start_date',
                        'header' => UtilityHelper::tableSortHeader('Start Date', 'start_date', 'numeric'),
                        'value' => function ($model) {
                            return $model->getStartDateDisplay();
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'start_date',
                            'template' => '{input}{addon}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'mm-dd-yyyy',
                                'orientation'=> 'bottom'
                            ]
                        ]),
                    ],
                    [
                        'attribute' => 'end_date',
                        'header' => UtilityHelper::tableSortHeader('End Date', 'end_date', 'numeric'),
                        'value' => function ($model) {
                            return $model->getEndDateDisplay();
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'end_date',
                            'template' => '{input}{addon}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'mm-dd-yyyy',
                                'orientation'=> 'bottom'
                            ]
                        ]),
                    ],
                    [
                        'attribute' => 'registeredCandidates',
                        'header' => UtilityHelper::tableSortHeader('Candidates', 'start_date', 'numeric'),
                        'headerOptions' => ['style' => 'width:120px'],
                        'format' => 'raw',
                        'value' => function ($model) {
                            return "<a href='/admin/candidatesession?i=" . md5($model->id) . "'>" . $model->getNumberOfRegisteredCandidates() . '</a>';
                        },
                    ],
                    ['label' => '',
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'action-cell'],
                        'value' => function ($model) use($transferType, $bothTestSessions) {
                            return UtilityHelper::buildActionWrapper('/admin/testsession', $model->id, false,
                                extraLinks($model, $this),
                                buildCustomDeleteLink($model->id, $this->params['queryParams'], $this, $model->getTestSessionTypeId(), [
                                    'transferType' => $transferType,
                                    'bothTestSessions' => $bothTestSessions
                                ])
                            );
                        },
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
<!--    /*wroten from me*/-->
<script>

    $(document).ready(function () {
        $('tbody tr>td:nth-child(4)').each(function () {
            var html = $(this).text();
            $(this).html(html);

        })
        $('tbody tr>td:nth-child(4) input').blur(function (e) {
            var newval = $(this).val();
            var el = $(this).attr('id');
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: "<?php echo Yii::$app->getUrlManager()->createUrl('/admin/testsession/updateloc') ?>",
                data: {newval:newval,el:el},
                success: function (res) {
                }
            })
            e.preventDefault();
        })
    })
</script>

<?php if ($this->params['md5CandidateId'] != '') { ?>
    <script>
        $(function () {
            $('.pop-content li a').hide();
            $('li a.select-roster').show()
        });
    </script>
<?php } ?>
