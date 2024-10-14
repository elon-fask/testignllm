<html>
<head></head>
<body>
<p>Dear Admin,</p>
<br />
<p>User Account <?php echo $username?> has triggered password reset more than 3 time today.</p>
<?php foreach($resets as $index => $reset){?>
<p><?php echo $index+1?></p>
<p>IP Address: <?php echo $reset->ip_address?><br />
Date Request: <?php echo $reset->date_created?><br />
</p>
<?php }?>
</body>
</html>