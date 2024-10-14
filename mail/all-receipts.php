<?php use app\models\ChecklistItemTemplate;  use app\models\TestSession; ?>
<?php 
$siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
?>
<html>
<head></head>
<body>
<?php
$sessionGrouping = []; 
foreach($allReceipts as $receipt){
    if(!isset($sessionGrouping[$receipt->testSessionId])){
        $sessionGrouping[$receipt->testSessionId] = [];
    }
    
    $sessionGrouping[$receipt->testSessionId][] = $receipt;
}
?>

<p>Monthly Receipts:</p>
<?php if(count($allReceipts) == 0){?>
No receipts
<?php }else{?>
<ul>
    <?php foreach($sessionGrouping as $testSessionId => $receipts){
            $testSession = TestSession::findOne($testSessionId);
        ?>
            <li>
            <?php if($testSession){?>
            <p>Session Name: <?php echo $testSession->getPartialTestSessionDescription()?></p>
            <?php }else{?>
            <p>General Receipt</p>
            <?php }?>
                <ul><?php
            foreach($receipts as $receipt){    
                $photoPath = '';
                $path =  '/images/session/'.md5($receipt->testSessionId).'/receipts/'.$receipt->filename;
                if(is_file( realpath(\Yii::$app->basePath) .'/web'.$path)){
                    $photoPath = $siteUrl.'/'.$path;
                }
                
            ?>
<li>
    <?php if($photoPath != ''){?>
    <p>Receipt Photo:<a href="<?php echo $photoPath?>">Click Here</a></p>
    <?php }?>
    <p>Vendor Name: <?php echo $receipt->vendorName?></p>
    <p>Amount: $<?php echo $receipt->amount?></p>
    <p>Description: <?php echo $receipt->description;?></p>
</li>
<?php 
            }            
        ?>
</ul>
            </li>
    <?php }?>
    
    
</ul>
<?php }?>

</body>
</html>
