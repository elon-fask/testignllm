<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Candidates;
use app\models\ApplicationType;

/* @var $this yii\web\View */
/* @var $model app\models\CandidateSession */

$this->title = 'Student Notes: ' . $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['/admin/candidates']];
$this->params['breadcrumbs'][] = ['label' => $model->getFullName(), 'url' => ['/admin/candidates/view', 'id' => md5($model->id)]];
$this->params['breadcrumbs'][] = $this->title;

$candidate = $model;
?>

<div class="candidate-session-view">
    <h1>Student: <?php echo $model->getFullName() ?></h1>
    <?php echo $this->render('../partial/_subnav', ['active' => 'notes', 'candidate' => $candidate]); ?>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Notes:
            <i class="fa fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Click on a Note title to toogle the Note content"></i>
        </h4>
    </div>

    <div class="panel-body">
        <a href="javascript: CandidateNotes.addNotes('<?php echo md5($candidate->id) ?>')" class='btn btn-info'>
            <i class="fa fa-plus"></i> Add Notes
        </a>
        <?php if (count($notes) !== 0) { ?>
        <a href="#" class='btn btn-primary btn-expand-all'>
            <i class="fa fa-expand"></i> Expand All
        </a>
        <?php } ?>
    </div>
    <div class="list-body-info">
    <?php echo $this->render('_list', ['notes' => $notes, 'candidateID' => $candidate->id]) ?>
    </div>
</div>


<style>
    .edit-candidate-notes, .delete-candidate-notes {
        position: relative;
        top: -2px;
        margin: 0px 2px;
    }

    .candidates-notes .panel-heading .tooltip-inner {
        max-width: 300px;
    }

    .student-no-notes {
        padding: 25px;
    }
    .note-title{
        cursor:pointer;
        line-height: 34px;
        margin-bottom: 0;
        margin-top: 0;
    }
    .notes-details{
        display: none;
        padding: 15px 35px;
        list-style-type: disc;
    }
    .notes-details li:first-child{
        margin-bottom: 15px;
    }
    .note-title .fa{
        width: 15px;;
    }
    .note-actions{

    }
    .list-notes{
        margin-bottom: 0;
    }

    .list-notes li:last-child{
        border-bottom: 0;
    }

</style>


<script>
    $(function(){
        /* Event on note title -> Show/hide clicked note */
        $(document).on('click', '.note-title', function(e){
            e.preventDefault();
            var el = $(this);
            if(el.hasClass('expanded')){
                shrinkOne(el);
            } else {
                expandOne(el);
            }
        });

        /* Event on button Expand/shirnk ALL -> Show/hide note that are needed */
        $(document).on('click', '.btn-expand-all', function(e){
            e.preventDefault();
            var el = $(this);
            var items = $('.list-notes').find('.note-title');
            /* Case we alreday clicked on expand all == we want to shrink -> Shrink items & Upadate button to Expand= On */
            if( el.hasClass('expanded-all')){
                $.each(items, function(i,v){
                    shrinkOne($(v));
                });
                ExpandAllBtnOn(el);
            }else{
                /* Case Default -> Expand items & Upadate button to Expand= Off */
                $.each(items, function(i,v){
                    expandOne($(v));
                });
                ExpandAllBtnOff(el);
            }
        });

        /* Expand All btn On => Expand CTA */
        var ExpandAllBtnOn = function(btn){
            btn.removeClass('expanded-all');
            btn.html('<i class="fa fa-expand"></i> Expand All');
        };

        /* Expand All btn Off => Shrink CTA */
        var ExpandAllBtnOff = function(btn){
            btn.addClass('expanded-all');
            btn.html('<i class="fa fa-compress"></i> Reduce All');
        };

        /* Expand one Item
         *   SlideDown with callback : update classes, update icon & if all items are hidden => update All Button to Off
         * */
        var expandOne = function(el){
            el.parents('.list-group-item').find('.notes-details').slideDown(250, function () {
                el.find('.fa').addClass('fa-caret-down').removeClass('fa-caret-right').end()
                    .addClass('expanded');
                var nbrHidden = countHiddenItems();
                if (nbrHidden === 0 ){
                    ExpandAllBtnOff($('.btn-expand-all'));
                };
            });

        };

        /* Shrink one Item
         *   SlideUp with callback : update classes, update icon & at least one item is visible => update All Button to On
         * */
        var shrinkOne = function(el){
            el.parents('.list-group-item').find('.notes-details').slideUp(250, function () {
                el.find('.fa').addClass('fa-caret-right').removeClass('fa-caret-down').end()
                    .removeClass('expanded');
                var nbrHidden = countHiddenItems();
                if (nbrHidden !== 0 ){
                    ExpandAllBtnOn($('.btn-expand-all'));
                };
            });
        };

        /* countHiddenItems
         *  hidden == items that are nor expanded
         *  returns Int = number of hidden Items
         * */
        var countHiddenItems = function(){
            var items = $('.list-notes').find('.note-title');
            var hiddenItems = items.filter( function(i,v){
                if( !($(v).hasClass('expanded')) ){
                    return $(this);
                }
            });

            return hiddenItems.length;
        };

    });
</script>