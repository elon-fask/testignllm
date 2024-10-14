<?php 
use app\models\TestSite;
$totalCount = count($testSites);
?>
<?php if($totalCount == 0){?>
<h2>No Enrolled Sessions</h2>
<?php }else{?>

<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Test Site</th>
            <th>Enrolled Students</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($testSites as $testSiteId => $num){
        $testSite = TestSite::findOne($testSiteId);
            ?>
        <tr class="" data-id="<?php echo $testSite->id?>">
            <td><a href="/admin/testsite/update?id=<?php echo $testSite->id?>"><?php echo $testSite->getTestSiteLocation()?></a></td>
            <td><?php echo $num?></td>
        </tr>
        <?php }?>
        
    </tbody>
</table>

<?php }?>
<div class="">
To view other sessions, <a href="/admin/calendar">click here to go to Calendar Page</a>.
</div>