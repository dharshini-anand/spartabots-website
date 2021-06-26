<h2 id="inlogin-header">Reset Password</h2>
<small style="text-align:center;display:block;"><?php echo blog_title() ?></small>
<div id="inlogin-area">
    <div class="inlogin-form-center" style="margin-top:15px">
        <form method="POST">
            <div id="inlogin-form-inputs">
                <?php if ($response) echo $response; ?>
                <div class="inlogin-input">
                    <input type="password" name="password1" placeholder="New password" <?php echo ($activate ? '' : 'disabled') ?> />
                    <div class="clearfix"></div>
                </div>
                <div class="inlogin-input">
                    <input type="password" name="password2" placeholder="Confirm password" <?php echo ($activate ? '' : 'disabled') ?> />
                    <div class="clearfix"></div>
                </div>
                <input type="hidden" name="email" value="<?php echo $email; ?>" />
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </div>
            <div id="inlogin-form-actions">
                <input type="submit" name="submit" value="Reset password" <?php echo ($activate ? '' : 'disabled style="background:#dadada"') ?> />
                <a style="display:block;margin-top:15px" rel="home" href="<?php echo site_url() ?>">Back to home</a>
                <a style="display:block;" rel="home" href="<?php echo site_url() ?>login">Back to Login</a>
            </div>
        </form>
    </div>
</div>