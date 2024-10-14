<html>
<head></head>
<body>
<p>Dear Admin,</p>
<p>User Account <?php echo $username?> has reported an unauthorized password notification.</p>
<p>Reset Password Details: </p>
<p>IP Address: <?php echo $reset->ip_address?><br />
Date Request: <?php echo $reset->date_created?><br />
</p>

</body>
</html>