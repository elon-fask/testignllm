

<html>
<head></head>
<body>
<p>Dear Admin,</p>
<br />
<h2>Here's a list of unfinished registration leads for <?php echo date('Y-m-d', strtotime('now'))?>:</h2>
<?php 
if(count($candidates) == 0){
?>
    <h3>None for today</h3>
<?php     
}else{
?>
<ul>
    <?php foreach($candidates as $candidate){?>
        <li>
            <p>Name: <?php echo $candidate['first_name'].' '.$candidate['last_name'] ?></p>
            <p>Email: <?php echo $candidate['email'] ?></p>
            <p>Phone: <?php echo $candidate['phone'] ?></p>
        </li>
    <?php }?>
</ul>
<?php     
}
?>
</body>
</html>