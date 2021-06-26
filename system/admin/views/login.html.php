<?php if(!login()) {?>
    <h2 id="inlogin-header">Member Login</h2>
    <small style="text-align:center;display:block;"><?php echo blog_title() ?></small>
	<div id="inlogin-area">
		<form method="POST" action="login">
            <?php if ($_REQUEST['continue']) {
                echo '<input name="continue" type="hidden" value="'.$_REQUEST['continue'].'" />';
            } ?>
			<div id="inlogin-form-inputs" style="padding-bottom:15px">
				<center class="inlogin-form-center">
                    <?php if ($error) echo '<div class="error-message" style="margin-bottom:0;box-shadow:none">'.$error.'</div>'; ?>
					<div class="inlogin-input">
						<label for="inlogin-user">Username or email</label>
						<input type="text" required id="inlogin-user" placeholder="Username or email" name="user"/>
						<div class="clearfix"></div>
					</div>
					<div class="inlogin-input">
						<label for="inlogin-password">Password</label>
						<input type="password" required id="inlogin-password" placeholder="Password" name="password"/>
						<div class="clearfix"></div>
					</div>
				</center>
			</div>
            <div class="inlogin-check-input inlogin-form-center" style="padding-bottom:15px;display:none">
                <input type="checkbox" id="inlogin-remember-me" name="remember-me" value="1">
                <label for="inlogin-remember-me" style="line-height:17px;font-size:14px;">Remember Me</label>
            </div>
			<div id="inlogin-form-actions">
				<center class="inlogin-form-center">
					<input type="submit" name="submit" value="Login"/>
				</center>
                <div class="inlogin-form-center" style="margin-top:15px">
                    <a rel="home" href="<?php echo site_url() ?>">Back to home</a>
                    
                    <div class="inlogin-forgot-links" style="margin-top:10px">
                        <a href="<?php echo site_url() ?>forgot-password">Forgot password?</a>
                    </div>
                </div>
			</div>
		</form>
	</div>
<?php
} else {
    header('location: ' . site_url());
}
?>