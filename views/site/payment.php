<?php
use app\models\CandidateTransactions;
use app\models\TestSession;

$this->title = 'Account balance: '.$candidate->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['/admin/candidates']];
$this->params['breadcrumbs'][] = ['label' => $candidate->getFullName(), 'url' => ['/admin/candidates/view', 'id' => md5($candidate->id)]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if(isset($redirectUrl) && $redirectUrl != ''){?>
<script>
redirectTo('<?php echo $redirectUrl?>');
</script>
<?php }?>
