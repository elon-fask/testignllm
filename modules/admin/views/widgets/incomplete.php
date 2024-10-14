<?php
$list = $items['list'];
$totalCount = $items['count'];
?>
<?php if($totalCount == 0){?>
<h2>No Application</h2>
<?php }else{?>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>Name</th>
            <th>Step</th>
            <th>Phone</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($list as $candidate){?>
        <tr class="incomplete-info" data-id="<?php echo $candidate->id?>">
            <td><?php echo $candidate->getFullName()?></td>
            <td><?php echo intval($candidate->registration_step)+2 .' of 5' ?></td>
            <td><?php echo $candidate->phone?></td>
            <td class="action-cell">
            <a class="show-action" href="#"><i class="fa fa-cogs"></i> Actions</a>
                    <div style="display: none" class="pop-content">
                        <ul style="list-style-type: none; margin: 0; padding: 0;width: 175px;">
                            <li><a href="/admin/candidates/update?id=<?php echo md5($candidate->id)?>" class=""><i style="width:15px" class="fa fa-pencil"></i> Edit Application</a></li>
                            <li><a class="mark-student-not-signing-up" href="#"  data-id="<?php echo md5($candidate->id)?>" class="link-delete"><i style="width:15px" class="fa fa-trash"></i> Student Not Signing Up</a></li>
                        </ul>
                    </div>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>

<?php }?>

<div class="incomplete-pagination" data-total-pages="<?php echo ceil($totalCount / 10)?>" data-current-page="<?php echo isset($currentPage) ? $currentPage : 1?>">

</div>

<script>
    $(function() {
        $(document).off('click','.mark-student-not-signing-up');
        $(document).on('click','.mark-student-not-signing-up', function(e) {
            e.preventDefault();
            var el = $(this);
            var cId = el.data('id');

            $.confirm({
                title:'Student Not Signing up',
                confirmButton: 'Yes, Mark as Not Signing up',
                cancelButton:'No, Cancel',
                content: 'Are you sure you want to mark this student as not signing up?',
                confirm: function() {
                    markStudentNotSigningUp(cId, false, '');
                }
            });
        });
    });
</script>
