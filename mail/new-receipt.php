<?php 
use app\models\TestSession; ?>
<?php 
$siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
?>
<html>
<head></head>
<body>
<?php if($testSession){?>
<p>Test Session Name: <b><?php echo $testSession->getFullTestSessionDescription()?></b></p>
<?php }else{?>
<p>General Receipt</p>
<?php }?>
<p>New Receipt Details:</p>
<p>Vendor Name: <?php echo $receipt->vendorName?></p>
<p>Amount: $<?php echo $receipt->amount?></p>
<p>Description: <?php echo $receipt->description;?></p>

</body>
</html>