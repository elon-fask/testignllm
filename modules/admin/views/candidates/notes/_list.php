<?php if (count($notes) == 0) { ?>
    <p class="student-no-notes">
        No Notes recorded for this student.
        <a href="javascript: CandidateNotes.addNotes('<?php echo md5($candidateID) ?>')">
            Would you like to add Notes?
        </a>
    </p>     
<?php } else { ?>
        <ul class="list-group list-notes">
            <?php foreach ($notes as $index => $note) { ?>
                <li class="list-group-item">
                    <div class="row">
                        <h4 class="note-title col-xs-9"><i class="fa fa-caret-right"></i> #<?php echo ($index + 1)?> <?php echo $note->getSummary() ?></h4>
                        <div class="note-actions col-xs-3">
                            <a class='btn btn-danger pull-right delete-candidate-notes' href="javascript: CandidateNotes.deleteNotes('<?php echo $note->id ?>', '<?php echo md5($note->candidate_id) ?>')"><i class="fa fa-trash"></i>&nbsp;Delete</a>
                            <a class='btn btn-info pull-right edit-candidate-notes' href="javascript: CandidateNotes.editNotes('<?php echo $note->id ?>', '<?php echo md5($note->candidate_id) ?>')"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                        </div>
                    </div>

                    <ul class="notes-details">
                        <li><?php echo 'Last Updated: ' . date('m-d-Y H:i:s', strtotime($note->date_updated)) ?>
                            / <?php echo 'Created: ' . date('m-d-Y H:i:s', strtotime($note->date_created)) ?>
                        </li>
                        <li><?php echo $note->notes ?></li>
                    </ul>
                </li>
            <?php } ?>
        </ul>
<?php } ?>
