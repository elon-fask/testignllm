<?php
use app\models\CandidateTransactions;
use app\models\TestSession;
use app\assets\ReactAccountBalanceAsset;

ReactAccountBalanceAsset::register($this);

$this->title = 'Account balance: '.$candidate->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['/admin/candidates']];
$this->params['breadcrumbs'][] = ['label' => $candidate->getFullName(), 'url' => ['/admin/candidates/view', 'id' => md5($candidate->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .table-account-student-details th{
        width: 200px;
        text-align: right;
        padding-right: 25px !important;
    }
    .table-account-student-details{
        margin-bottom: 0;
    }
</style>

<h1>Student: <?php echo $candidate->getFullName()?></h1>
<?= $this->render('./partial/_subnav', ['active' => 'account', 'candidate'=>$candidate]) ?>

<div id="react-entry"></div>

<script>

var candidate = <?= json_encode($candidateArr) ?>;
console.log(candidate)

</script>
