<?php if (isset($error)) { ?>
	<center class="inlogin-form-center">
        <div class="error-message" style="margin-bottom:0;margin-top:25px;"><?php echo $error?></div>
    </center>
<?php } ?>
<div id="form-status"></div>
<h2 id="inlogin-header">Resend verification</h2>
<small style="text-align:center;display:block;"><?php echo blog_title() ?></small>
<div id="inlogin-area">
    <div class="inlogin-form-center" style="margin-top:15px">
        <form method="POST">
            <div id="inlogin-form-inputs">
                <?php if ($verify_message) echo $verify_message; ?>
                <div class="inlogin-input">
                    <input type="text" name="email" placeholder="Email" />
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="inlogin-form-actions">
                <input type="submit" name="submit" value="Resend verification">
                <a style="display:block;margin-top:15px" rel="home" href="<?php echo site_url() ?>">Back to home</a>
                <a style="display:block;" rel="home" href="<?php echo site_url() ?>login">Back to Login</a>
            </div>
        </form>
    </div>
</div>