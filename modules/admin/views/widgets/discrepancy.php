<?php 
$list = $discrepancyList['list'];
$totalCount = $discrepancyList['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Discrepancy</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Test Site Name</th>
            <th>Item Name</th>
            <th>Date</th>            
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $discrepancy){?>
        <tr class="" data-id="<?php echo $discrepancy->id?>">
            <td><a href="/admin/reports/discrepancy?id=<?php echo $discrepancy->testSiteId?>"><?php echo $discrepancy->getSiteName()?></a></td>
            <td><?php echo $discrepancy->getName();?></td>
            <td><?php echo date('m-d-Y', strtotime($discrepancy->date_created))?></td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>
<div class="discrepancy-pagination" data-user-id='<?php echo \Yii::$app->user->id?>' data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>