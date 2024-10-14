<h3>Matched Application Types:</h3>
<?php if(count($list) == 0){?>
<div class='alert alert-danger'>No Matched Found</div>
<?php }else{?>
<table class='table table-striped table-condensed'>
<thead>
    <tr>
        <th>Name</th>
        <th>Keyword</th>
        <th>Description</th>
        <th>Price</th>
        <th>NCCCO Testing Services Fee</th>
        <th>Late Fee</th>
    </tr>
</thead>
<tbody>
<?php foreach($list as $appType){?>
<tr>
    <td><a href="/admin/application/update?id=<?php echo $appType->id?>"><?php echo $appType->name?></a></td>
    <td><?php echo $appType->keyword?></td>
    <td><?php echo $appType->description?></td>
    <td><?php echo $appType->price?></td>
    <td><?php echo $appType->iaiFee?></td>
    <td><?php echo $appType->lateFee?></td>
</tr>
<?php }?>
</tbody>
</table>
<?php }?>