<div class="clearfix">

    <div class="form-group text-right">
        <button type="button" class="btn btn-info btn-add-note" data-checklistid="<?php echo $cheklistId;?>">New Note</button>
    </div>

    <?php if (count($notes) == 0) { ?>
        <div class='alert alert-danger'>No Notes</div>
    <?php } else { ?>
        <ul class="list-group">
            <?php foreach ($notes as $note) { ?>
                <li class="list-group-item">
                    <b>Note:</b> <?php echo $note->note ?>
                    <br/>
                    <br/>
                    <b>By:</b> <?php echo $note->getFullName() ?>
                    <br/>
                    <b>Created:</b> <?php echo date('m-d-Y H:i', strtotime($note->date_created)) ?>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <div class="form-group text-right">
        <button type="button" class="btn " data-dismiss="modal">Close</button>
    </div>
</div>