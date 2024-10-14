<?php
use yii\helpers\Html;
use app\models\Candidates;
use app\models\ApplicationType;
use app\assets\ReactStudentFilesAsset;

ReactStudentFilesAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = 'Student Files: ' . $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['/admin/candidates']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => ['/admin/candidates/view', 'id' => md5($model->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="candidate-session-view">
    <h1>Student: <?= $model->getFullName() ?></h1>
    <?= $this->render('../partial/_subnav', ['active' => 'files', 'candidate' => $model]) ?>
</div>

<div id="react-entry"></div>

<script>
var candidate = <?= json_encode($candidate) ?>;
var CANDIDATE_PHOTO_BASE_URL = '<?= $candidatePhotoBaseUrl ?>';
var TEST_SESSION_PHOTO_BASE_URL = '<?= $testSessionPhotoBaseUrl ?>';
</script>
