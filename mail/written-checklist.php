<html>
<head></head>
<body>
<p><b>Written Session Checklist: <?php echo $testSessionName?></b></p>
<br /><br />
<?php foreach($checklistItems as $item){?>
<p><?php echo $item->name.' - '.$item->val?></p>
<?php }?>
</body>
</html>