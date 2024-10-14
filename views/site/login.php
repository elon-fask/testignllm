
<div class="row">
    <div class="col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3">
    <form method="POST" action="/site/login">
        <fieldset>
            <h1>Log In</h1>
           
<?php if(isset($error)){?>
<div class="alert alert-danger"><?php echo $error?></div>
<?php }?>
<?php if(isset($inactive) && $inactive){?>
<div class="alert alert-danger"><?php echo $error2?>
<ul>
<li><a data-id="<?php echo md5($id)?>" class='resend-activation' href="javascript: void(0);">Resend Activation Email</a></li>
<li>Click <a href="/register">here</a> to create a new account with a different email</li>
</ul>
</div>
<?php }?>
<?php if(isset($message)){?>
<div class="alert alert-success"><?php echo $message?></div>
<?php }?>


            <div class="form-group">
                <label class="control-label">Username / Email</label>
                <input required type="text" id="username" name="username" class="form-control" placeholder="Username / Email"/>
            </div>

            <div class="form-group">
                <label class="control-label">Password</label>
                <input required type="password" id="password" class="form-control" name="password" placeholder="Password"/>
            </div>

            <div class="form-group text-center">
                <a href="/site/forget">Forget Password</a>&nbsp;&nbsp;&nbsp;<input type="submit" id="gologin" class="btn btn-default btn-login" value="Login"/>
            </div>
        </fieldset>
    </form>
    </div>
</div>

