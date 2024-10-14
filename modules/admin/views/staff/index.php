<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\assets\ReactStaffPageAsset;
use app\models\Staff;
use app\helpers\UtilityHelper;
use app\models\User;
use app\models\UserRole;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// ReactStaffPageAsset::register($this);

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$mergePrimaryId = $mergePrimaryId ?? null;
$mergeUserPrimary = $mergeUserPrimary ?? null;
?>
    <div class="staff-index">

        <div class="row row-header">
            <div class="col-xs-12 col-md-8">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="col-xs-12 col-md-4">
                <?= Html::a('<i class="fa fa-plus"></i> Create Staff', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'header' => UtilityHelper::tableSortHeader('First Name', 'first_name'),
                            'attribute' => 'first_name',
                        ],
                        [
                            'header' => UtilityHelper::tableSortHeader('Last Name', 'last_name'),
                            'attribute' => 'last_name',
                        ],
                        [
                            'header' => UtilityHelper::tableSortHeader('Phone', 'workPhone', 'numeric'),
                            'attribute' => 'workPhone',
                        ],
                        [
                            'format' => 'raw',
                            'header' => UtilityHelper::tableSortHeader('Email', 'email'),
                            'attribute' => 'email',
                            'value' => function ($model) {
                                return Html::mailto($model->email);
                            }
                        ],
                        [
                            'label' => 'Staff Roles',
                            'value' => function ($model) {
                                $rolesDesc = array_map(function ($role) {
                                    return UserRole::ROLES_DESC[$role];
                                }, $model->roles);

                                return implode(', ', $rolesDesc);
                            },
                        ],
                        [
                            'label' => 'Is Active',
                            'filter' => Html::activeDropDownList($searchModel, 'active', [0 => 'No', 1 => 'Yes', '' => 'All'], ['class' => 'form-control', 'style' => 'width:100px;']),
                            'value' => function ($model) {
                                return $model->active == 1 ? 'Yes' : 'No';
                            },
                        ],
                        ['label' => '',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'action-cell'],
                            'value' => function ($model) use($merge, $mergePrimaryId, $mergeUserPrimary) {
                                return UtilityHelper::buildActionWrapper('/admin/staff', $model->id, false, null, ($model->active != 1) ? extraLinks($model, true, $merge, $mergePrimaryId, $mergeUserPrimary) : extraLinks($model, false, $merge, $mergePrimaryId, $mergeUserPrimary));
                            },
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

    <form action="/admin/staff/delete" method="post" id="form-archive-staff">
        <input type='hidden' id='staffId' name='id' value=""/>
    </form>
    
     <form action="/admin/staff/undelete" method="post" id="form-unarchive-staff">
        <input type='hidden' id='staffId' name='id' value=""/>
    </form>

    <div id="react-entry"></div>
<?php

function extraLinks($model, $archived = false, $merge = false, $mergePrimaryId, $mergeUserPrimary)
{
    $archiveClassStr = $archived ? 'unarchive' : 'archive';
    $archiveTextStr = $archived ? 'Un-archive' : 'Archive';

    if ($merge) {
        $name = $mergeUserPrimary->first_name . ' ' . $mergeUserPrimary->last_name;
        $ret = '<li><a class="staff-merge-with" href="/admin/staff/merge?primary=' . $mergePrimaryId . '&secondary=' . $model->id . '">';
        $ret .= '<i class="fa fa-users" style="width:15px"></i> Merge with ' . $name . '</a></li>';
    } else {
        $ret = '<li><a class="staff-merge" href="/admin/staff?merge=1&merge_primary=' . $model->id . '" data-staffid="' . $model->id . '">';
        $ret .= '<i class="fa fa-users" style="width:15px"></i> Merge User</a></li>';
    }

    $ret .= '<li><a class="staff-' . $archiveClassStr . '" href="javascript: void(0);" data-staffid="'. $model->id .'">';
    $ret .= '<i class="fa fa-trash" style="width:15px"></i>' . $archiveTextStr . '</a></li>';

    return $ret;
}

?>

<script>
var users = <?= json_encode($users) ?>;
</script>
