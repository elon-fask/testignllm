<?php
use app\models\TestSite;
use app\models\TestSession;
use app\models\Cranes;
use app\models\ChecklistItemTemplate;
$list = $items['list'];
$totalCount = $items['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Warnings</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Test Site</th>
            <th>Crane</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $testSessionChecklistItem){
            $checklistItem = ChecklistItemTemplate::findOne($testSessionChecklistItem->checkListItemId);
            $testSession = TestSession::findOne($testSessionChecklistItem->testSessionId);
            $craneName = 'N/A';
            $prefix = '';
            if($testSessionChecklistItem->craneId > 0){
                $crane = Cranes::findOne($testSessionChecklistItem->craneId);
                $craneName = $crane->getDescription();
                $prefix = '&craneId='.$testSessionChecklistItem->craneId;
            }
            $resolveUrl = '/admin/checklist/session?type='.$testSessionChecklistItem->type.$prefix.'&id='.md5($testSessionChecklistItem->testSessionId);
            
            ?>
        <tr  data-id="<?php echo $testSessionChecklistItem->id?>">
            <td><?php echo $testSession->getTestSiteAddress()?></td>
            <td><?php echo $craneName?></td>
            <td><?php echo $checklistItem->name.': '.$testSessionChecklistItem->displayStatus()?></td>
            <td class="action-cell">
                <a href="<?php echo $resolveUrl?>">Resolve</a>
                    
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>

<div class="failed-pagination" data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>

