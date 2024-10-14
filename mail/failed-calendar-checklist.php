<?php use app\models\ChecklistItemTemplate;  use app\models\TestSession; ?>
<?php 
$siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
?>
<html>
<head></head>
<body>
<p>Test Site Name: <b><?php echo $testSiteName?></b></p>
<p>Here are the following Test Session action items that need your attention:</p>
<ul>
    <?php foreach($failedItems['practical-sessions'] as $testSessionId => $checklistItems){
            $testSession = TestSession::findOne($testSessionId);
        ?>
            <li><p>Session Name: <?php echo $testSession->getPartialTestSessionDescription()?></p>
                <ul><?php
            foreach($checklistItems as $checkListItem){               
            ?>
<li>Item: <b><?php echo $checkListItem->name?></b></li>
<?php 
            }            
        ?>
</ul>
                <p>To clear the items, please click <a target='_blank' href="<?php $siteUrl?>/resolve/calendar?id=<?php echo $testSessionId?>">here</a></p>
            </li>
    <?php }?>
    
    <?php foreach($failedItems['written-sessions'] as $testSessionId => $checklistItems){
            $testSession = TestSession::findOne($testSessionId);
        ?>
            <li><p>Session Name: <?php echo $testSession->getPartialTestSessionDescription()?></p>
                <ul>
        <?php
            foreach($checklistItems as $checkListItem){               
            ?>
                    <li>Item: <b><?php echo $checkListItem->name?></b></li>
            <?php 
            }
            
        ?>
                </ul>
                <p>To clear the items, please click <a target='_blank' href="<?php $siteUrl?>/resolve/calendar?id=<?php echo $testSessionId?>">here</a></p>
            </li>
    <?php }?>
</ul>


</body>
</html>
