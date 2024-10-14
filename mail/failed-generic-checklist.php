<?php use app\models\ChecklistItemTemplate;  use app\models\TestSession;
use app\models\ChecklistTemplate;
 ?><html>

<head></head>
<body>
<?php 
$siteUrl = isset(\Yii::$app->params['crane.admin.url']) ? \Yii::$app->params['crane.admin.url'] : '';
?>
<p>Test Site Name: <b><?php echo $testSiteName?></b></p>
<p>Test Session Name: <b><?php echo $sessionName?></b></p>
<p>Here are the following Test Session action items that need your attention:</p>

<?php 
foreach($failedTypes as $itemType){
    $typeDesc = ChecklistTemplate::getTypes()[$itemType];
?>
<h3><?php echo $typeDesc?></h3>
<ul>
<?php 
    foreach($failedItems as $failItem){
        $item = $failItem['item'];
        $checkListItem = $failItem['checkListItem'];
        if($item->type == $itemType){
            
        ?>
        <li>Item: <b><?php echo $checkListItem->name?></b>
        <br />
        <a href='<?php echo $failItem['resolve']?>'>Resolve Here</a>
        </li>
        
        <?php 
        }
       
    }
?>
</ul>
<?php 
}
?>

</body>
</html>