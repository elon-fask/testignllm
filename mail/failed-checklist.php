<?php use app\models\ChecklistItemTemplate;  use app\models\TestSession; ?><html>
<head></head>
<body>
<p>Test Site Name: <b><?php echo $testSiteName?></b></p>
<p>Here are the following Test Site action items that need your attention:</p>
<ul>
    <?php foreach($discrepancyList as $discrepancy){
        $checkListItemId = $discrepancy->checklistItemId;
        $checklistItem = ChecklistItemTemplate::findOne($checkListItemId);
        $sessionName = '-';
        $notes = '-';
        if($discrepancy->testSessionId > 0){
            $testSession = TestSession::findOne($discrepancy->testSessionId);
            if($testSession){
                $sessionName = $testSession->getPartialTestSessionDescription();
            }            
        }
        if($discrepancy->notes != null && $discrepancy->notes != ''){
            $notes = $discrepancy->notes;
        }
        ?>
    <li>Item: <b><?php echo $checklistItem->name?></b><br />Session: <b><?php echo $sessionName?></b><br />Notes: <b><?php echo $notes?></b><br /></li>
    <?php }?>
</ul>
<?php 
$siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
?>
<p>To clear the items, please click <a target='_blank' href="<?php $siteUrl?>/resolve?id=<?php echo $discrepancy->testSiteId?>">here</a></p>
</body>
</html>
