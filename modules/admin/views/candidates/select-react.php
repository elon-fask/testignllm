<?php
// use app\assets\ReactStudentTransferAsset;
// ReactStudentTransferAsset::register($this);
?>

<div id="react-entry">Testing</div>

<script>
$('#reminder-modal .modal-dialog').addClass('modal-lg').css('width', '90%');

var candidate = <?= json_encode($candidate) ?>;
var applicationTypes = <?= json_encode($applicationTypes) ?>;
var currentTestSession = <?= json_encode(isset($currentTestSession) ? $currentTestSession : []) ?>;
var currentTestSessionCounterpart = <?= json_encode(isset($currentTestSessionCounterpart) ? $currentTestSessionCounterpart : []) ?>;
var incomingTestSession = <?= json_encode($incomingTestSession) ?>;
var incomingTestSessionCounterpart = <?= json_encode(isset($incomingTestSessionCounterpart) ? $incomingTestSessionCounterpart : []) ?>;
var isRescheduleOnly = !<?= $isRetake ?>;
var transferType = '<?= $transferType ?>';
var bothTestSessions = '<?= $bothTestSessions ?>';
</script>

<script src="/js/react/bundle.vendor.js"></script>
<script src="/js/react/bundle.studentTransfer.js"></script>
