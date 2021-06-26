<script>
function submitSignupForm() {
    $.post('/register-account', {
        user: $('[name=user]').val(),
        password1: $('[name=password1]').val(),
        password2: $('[name=password2]').val(),
        email: $('[name=email]').val(),
        realname: $('[name=realname]').val()
    }, function(data) {
        $('#form-status').html(data);
    });
}
</script>

<?php if (isset($error)) { ?>
	<center class="inlogin-form-center">
        <div class="error-message" style="margin-bottom:0;margin-top:25px;"><?php echo $error?></div>
    </center>
<?php } ?>
<div id="form-status"></div>
<h2 id="inlogin-header">Register Account</h2>
<small style="text-align:center;display:block;"><?php echo blog_title() ?></small>
<div id="inlogin-area">
    <form method="POST" action="register-account" onsubmit="submitSignupForm();return false">
        <div id="inlogin-form-inputs">
            <center class="inlogin-form-center">
                <div class="inlogin-input">
                    <label for="inlogin-user">Username</label>
                    <input type="text" required id="inlogin-user" placeholder="Username" name="user"/>
                    <div class="clearfix"></div>
                </div>
                <div class="inlogin-input">
                    <label for="inlogin-email">Email</label>
                    <input type="email" required id="inlogin-email" placeholder="Email" name="email" style="font-size: 14px;"/>
                    <div class="clearfix"></div>
                </div>
                <div class="inlogin-input">
                    <label for="inlogin-realname">Real name</label>
                    <input type="text" required id="inlogin-realname" placeholder="Real name" name="realname"/>
                    <div class="clearfix"></div>
                </div>
                <div class="inlogin-input">
                    <label for="inlogin-password1">Password</label>
                    <input type="password" required id="inlogin-password1" placeholder="Password" name="password1"/>
                    <div class="clearfix"></div>
                </div>
                <div class="inlogin-input">
                    <label for="inlogin-password2">Confirm Password</label>
                    <input type="password" required id="inlogin-password2" placeholder="Confirm password" name="password2"/>
                    <div class="clearfix"></div>
                </div>
            </center>
        </div>
        <div id="inlogin-form-actions">
            <center class="inlogin-form-center">
                <input type="submit" name="submit" value="Register Account"/>
            </center>
            <div class="inlogin-form-center" style="margin-top:15px">
                <a rel="home" href="<?php echo site_url() ?>">Back to home</a>
            </div>
        </div>
        <?php if(login()): ?>
        <div class="inlogin-form-center notify-message" style="margin-bottom:10px;margin-top:15px;">
            You are already logged in. Please do not creating extra accounts unless necessary.
        </div>
        <?php endif; ?>
        <div class="inlogin-form-center notify-message" style="margin-bottom:10px;margin-top:15px;">
            Never recieved a verification email?<br/>
            <a href="<?php echo site_url() ?>resend-verification">Resend verification</a>
        </div>
    </form>
</div>