<div class='row'>
<?php
if(count($craneList) == 0){
?>
<div class='col-xs-12'>
    <h2>No Cranes to Grade</h2>
    </div>
<?php
}else{

?>
            <div class='col-xs-12'>
                <table class='table table-condensed'>
                    <thead>
                        <tr>
                            <th>Crane Type</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($craneList as $key => $info){
                    ?>
                        <tr>
                            <td><?php echo $info['name']?></td>
                            <td class='pass-fail-cranes' data-key='<?php echo $info['key']?>' data-name='<?php echo $info['name']?>' style="display: flex; flex-flow: column;">
                                <label><input value='1' data-key='<?php echo $info['key']?>' name='<?php echo $info['name']?>' type='radio' />&nbsp;Pass</label>
                                <label><input value='0' data-key='<?php echo $info['key']?>' name='<?php echo $info['name']?>' type='radio' />&nbsp;Fail</label>
                                <label><input value='2' data-key='<?php echo $info['key']?>' name='<?php echo $info['name']?>' type='radio' />&nbsp;Did Not Test</label>
                                <label><input value='3' data-key='<?php echo $info['key']?>' name='<?php echo $info['name']?>' type='radio' />&nbsp;Self Disqualified</label>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class='col-xs-12'>
                <button class='btn btn-success btn-save-grade-session pull-right'>Save</button>
            </div>
<?php

}
?>
</div>
<style>
td.required{
	background-color: #f2dede;
}
</style>
