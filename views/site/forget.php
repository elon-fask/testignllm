
<div class="row">
    <div class="col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">
    <form method="POST" action="/site/forget">
        <fieldset>
            <h1>Recover Password</h1>
           
<?php if(isset($error)){?>
<div class="alert alert-danger"><?php echo $error?></div>
<?php }?>
<?php if(isset($message)){?>
<div class="alert alert-success"><?php echo $message?></div>
<?php }?>
            <div class="form-group">
                <label class="control-label">Username / Email</label>
                <input required type="text" id="username" name="username" class="form-control" placeholder="Username / Email"/>
            </div>

            <div class="form-group text-center">
                <a href="/site/login">Back to Login</a>&nbsp;&nbsp;&nbsp;<input type="submit" id="gologin" class="btn btn-default btn-login" value="Submit"/>
            </div>
        </fieldset>
    </form>
    </div>
</div>

<style>

</style>

