<?php

use app\models\ChecklistItemTemplate;
?>

<form id="fulfill-checklist-form">
    <?php foreach ($checklists as $checklist) { ?>
        <div class="col-md-12 checklist-container"
        data-template-id="<?= $checklist['id'] ?>"
        data-checklist-name="<?= $checklist['name'] ?>"
        data-checklist-type="<?= $checklist['type'] ?>">
            <h4><?= $checklist['name'] ?></h4>
            <ol>
                <?php for ($i = 0; count($checklist['checklistItems']) > $i; $i++) { ?>
                    <?php
                    $checklistItem = $checklist['checklistItems'][$i];
                    $itemId = $checklistItem->id;
                    $itemType = $checklistItem->itemType;
                    $itemName = $checklistItem->name;
                    $itemDescription = $checklistItem->description;
                    $itemFailingScore = $checklistItem->failingScore;
                    ?>
                    <li class="col-md-6 checklist-item" style="margin-bottom: 40px;">
                        <div><?= $itemName ?></div>
                        <?php if ($itemType == ChecklistItemTemplate::TYPE_PASS_FAIL) { ?>
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="1">
                                        Pass
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="0">
                                        Fail
                                    </label>
                                </div>
                            </div>
                        <?php } else if ($itemType == ChecklistItemTemplate::TYPE_NUMBER) { ?>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <input required
                                           type="number"
                                           class="form-control
                                           checklist-input"
                                           data-item-id="<?= $itemId ?>"
                                           data-item-type="<?= $itemType ?>"
                                           data-item-name="<?= $itemName ?>"
                                           data-item-description="<?= $itemDescription ?>"
                                           data-item-failing-score="<?= $itemFailingScore ?>"
                                           data-item-sequence="<?= $i ?>"
                                           id="options-<?= $itemId ?>">
                                </div>
                                <label for="options-<?= $itemId ?>" class="col-sm-2 control-label">Amount/Number</label>
                            </div>
                        <?php } else if ($itemType == ChecklistItemTemplate::TYPE_RATE_CONDITION) { ?>
                            <div class="form-group">
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="0">
                                        0
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="1">
                                        1
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="2">
                                        2
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="3">
                                        3
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input required
                                               type="radio"
                                               class="checklist-input"
                                               data-item-id="<?= $itemId ?>"
                                               data-item-type="<?= $itemType ?>"
                                               data-item-name="<?= $itemName ?>"
                                               data-item-description="<?= $itemDescription ?>"
                                               data-item-failing-score="<?= $itemFailingScore ?>"
                                               data-item-sequence="<?= $i ?>"
                                               name="options-<?= $itemId ?>"
                                               value="4">
                                        4
                                    </label>
                                </div>
                            </div>
                        <?php } else if ($itemType == ChecklistItemTemplate::TYPE_RATE_FULLNESS) { ?>
                            <div class="form-group" style="margin: 20px 0 20px 0;">
                                <input required
                                       type="hidden"
                                       class="slider-input checklist-input"
                                       data-item-id="<?= $itemId ?>"
                                       data-item-type="<?= $itemType ?>"
                                       data-item-name="<?= $itemName ?>"
                                       data-item-description="<?= $itemDescription ?>"
                                       data-item-failing-score="<?= $itemFailingScore ?>"
                                       data-item-sequence="<?= $i ?>"
                                       name="options<?= $itemId ?>"
                                       value="0" />
                            </div>
                        <?php } ?>
                        <button type="button" class="btn btn-info toggle-note">Add Note</button>
                        <div class="form-group add-note" style="display: none; margin-top: 10px;">
                            <label for="options-<?= $itemId ?>" class="col-sm-2 control-label">Note</label>
                            <div class="col-sm-10">
                                <input type="text"
                                       class="form-control checklist-note"
                                       data-item-id="<?= $itemId ?>"
                                       data-item-type="<?= $itemType ?>"
                                       data-item-name="<?= $itemName ?>"
                                       data-item-description="<?= $itemDescription ?>"
                                       data-item-failing-score="<?= $itemFailingScore ?>"
                                       data-item-sequence="<?= $i ?>"
                                       id="options-<?= $itemId ?>">
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ol>
        </div>
    <?php } ?>
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-success">
            <span>Save Checklist</span>
            <span class="spinner" style="display: none;">
                &nbsp;
                <i class="fa fa-circle-o-notch fa-spin"></i>
            </span>
        </button>
    </div>
</form>

<script>
    $('.toggle-note').click(function() {
        $(this).siblings('.add-note').toggle();
    });

    $('#fulfill-checklist-form').on('submit', function(event) {
        event.preventDefault();
        var spinner = $('.spinner');
        spinner.toggle();
        var checklists = [];

        $('.checklist-container').each(function() {
            var checklist = $(this);
            var checklistItemValues = [];

            var checklistInputs = checklist.find('input[type="radio"]:checked, input[type="number"], .slider-input');

            checklistInputs.each(function() {
                var checklistItem = $(this);
                checklistItemValues[checklistItem.data('item-sequence')] = {
                    'checklist-item-id': checklistItem.data('item-id'),
                    'type': checklistItem.data('item-type'),
                    'value': checklistItem.val(),
                    'name': checklistItem.data('item-name'),
                    'description': checklistItem.data('item-description'),
                    'failing-score': checklistItem.data('item-failing-score')
                }
            });

            var checklistNotes = checklist.find('.checklist-note');

            checklistNotes.each(function() {
               var checklistNote = $(this);
               checklistItemValues[checklistNote.data('item-sequence')]['note'] = checklistNote.val();
            });

            checklists.push({
                'template-id': checklist.data('template-id'),
                'name': checklist.data('checklist-name'),
                'type': checklist.data('checklist-type'),
                'checklist-items': checklistItemValues
            });
        });

        $.ajax({
            url: '/admin/testsession/save-checklists',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data: JSON.stringify({
                'test-session-id': '<?= $testSession->id ?>',
                'checklists': checklists
            }),
            success: function(response) {
                console.log(response);
            },
            complete: function() {
                spinner.toggle();
            }
        })
    });
</script>