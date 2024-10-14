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
window.location.href = '<?php echo $redirectUrl?>';
</script>
<?php }?>

<?php if(isset($message) && $message !== false){?>
 <div class="alert alert-success"><?php echo $message?></div>
<?php }?>

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
<?php echo $this->render('./partial/_subnav', ['active' => 'account', 'candidate'=>$candidate]); ?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Student Details</h4>
    </div>
    <div class="panel-body">
    <table class="table table-condensed table-account-student-details">
        <tr style="font-size: 1.5em;">
            <th>Name</th>
            <td><?= $candidate->getFullName() ?></td>
        </tr>

        <tr>
            <th>Phone</th>
            <td><?= $candidate->phone ?></td>
        </tr>

        <tr>
            <th>Application Type</th>
            <td><?= $candidate->getApplicationTypeDesc() ?></td>
        </tr>

        <tr>
            <th>Price</th>
            <td>$<?= number_format($candidate->transactionTotals['totalNetPayable'], 2, ".", ',') ?></td>
        </tr>

        <tr>
            <th>Remaining Amount</th>
            <td>$<?= number_format($candidate->transactionTotals['totalAmountOwed'], 2, ".", ',') ?></td>
        </tr>

        <tr>
            <th>PO Number</th>
            <td><?= $candidate->purchase_order_number ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <ul class="list-unstyled list-inline" style="padding-left: 200px; margin-top: 10px;">
                    <li><a href="javascript: void(0)" class="btn btn-primary add-charge" data-candidate-id="<?php echo $candidate->id?>">Add Charge</a><br /></li>
                    <li><a href="javascript: void(0)" class="btn btn-primary add-refund" data-candidate-id="<?php echo $candidate->id?>">Add Refund</a></li>
                    <li><a href="javascript: void(0)" class="btn btn-primary add-payment" data-candidate-id="<?php echo md5($candidate->id)?>">Receive Payment</a></li>
                    <li><a href="javascript: void(0)" class="btn btn-primary remove-charge" data-candidate-id="<?php echo $candidate->id?>">Remove Charge</a></li>
                </ul>

            </td>
        </tr>
    </table>
    </div>
</div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>Payment / Charge Detail History</h4>
    </div>
      <div class="panel-body" id="payment-details">
        <?php
        $paymentsList = $candidate->getPaymentLists();
        $totalPayment = 0;
        $totalCharged = 0;
        $totalRefunded = 0;
        $totalPromo = 0;
        $totalTransferred = 0;
        $totalRemovedCharge = 0;
        if($paymentsList == null || count($paymentsList) == 0){?>
        <div class="alert alert-danger">No Payment Transactions</div>
        <?php }else{?>
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($paymentsList as $transaction){

                        if($transaction->paymentType == CandidateTransactions::TYPE_STUDENT_CHARGE){
                            $totalCharged += $transaction->amount;
                        }else if($transaction->paymentType == CandidateTransactions::TYPE_CASH 
    || $transaction->paymentType == CandidateTransactions::TYPE_INTUIT
    || $transaction->paymentType == CandidateTransactions::TYPE_RECEIVABLES_OTHER
	|| $transaction->paymentType == CandidateTransactions::TYPE_CHEQUE
	|| $transaction->paymentType == CandidateTransactions::TYPE_ELECTRONIC_PAYMENT){
                            $totalPayment += $transaction->amount;
                        }else if($transaction->paymentType == CandidateTransactions::TYPE_TRANSFER){
                            $totalPayment -= $transaction->amount;
                        }else if($transaction->paymentType == CandidateTransactions::TYPE_REFUND){
                            $totalRefunded += $transaction->amount;
                        }else if($transaction->paymentType == CandidateTransactions::TYPE_DISCOUNT){
                            $totalRemovedCharge += $transaction->amount;
                        }else if($transaction->paymentType == CandidateTransactions::TYPE_PROMO){
                            $totalPromo += $transaction->amount;
                        }
                        $color = '';
                        if($transaction->paymentType == CandidateTransactions::TYPE_STUDENT_CHARGE){
							$color = 'color: red';
						}else if($transaction->paymentType == CandidateTransactions::TYPE_REFUND){
							$color = 'color: blue';
						}
                    ?>
                    <tr style="<?php echo  $color?>">
                        <th><?php echo date('m-d-Y', strtotime($transaction->date_created))?></th>
                        <th><?php echo $transaction->getPaymentTypeDesc()?></th>
                        <th><?php echo '$'.number_format($transaction->amount, 2, '.', ',')?></th>
                        <th><span class='update-remark-<?php echo $transaction->id?>'><?php echo nl2br($transaction->remarks)?></span> <a href="javascript: CandidatePayment.updateRemark(<?php echo $transaction->id?>)"><i class='fa fa-pencil' style='margin-left: 10px;'></i></a></th>
                    </tr>
                    <?php }?>
                     <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Payments</td>
                        <td><?php echo '$'.number_format($totalPayment, 2, '.', ',')?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Promo</td>
                        <td><?php echo '$'.number_format($totalPromo, 2, '.', ',')?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Charged</td>
                        <td><?php echo '$'.number_format($totalCharged, 2, '.', ',')?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Removed Charge</td>
                        <td><?php echo '$'.number_format($totalRemovedCharge, 2, '.', ',')?></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Refunded</td>
                        <td><?php echo '$'.number_format($totalRefunded, 2, '.', ',')?></td>
                        <td>&nbsp;</td>
                    </tr>
                    
                </tbody>
            </table>
            <?php }?>
    </div>
  </div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4>File Attachments</h4>
    </div>
    <div class="panel-body">
        <div class="clearfix">
                <div class="form-group file-attachments">
                    <?php echo $this->render('file-attachments', ['candidate'=>$candidate, 'isView' => false, 'showApplication' => false, 'showPayment' => true]);?>
                </div>
        </div>
<!--         <div class="row">-->
            <form id="upload" method="post" action="/admin/candidates/attachments" enctype="multipart/form-data">
                <div id="drop">
                    Drop Here
                    <a>Browse</a>
                    <input type="file" name="upl" multiple />
                    <input type="hidden" name="candidateId" value="<?php echo md5($candidate->id)?>" />
                    <input type="hidden" name="paymentFile" value="1" />
                    <input type="hidden" name="showApplication" value="0" />
                </div>
                <ul>
                    <!-- The file uploads will be shown here -->
                </ul>
            </form>
<!--         </div>-->
    </div>
</div>
