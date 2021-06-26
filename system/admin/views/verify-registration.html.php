<?php if (isset($error)) { ?>
	<center class="inlogin-form-center">
        <div class="error-message" style="margin-bottom:0;margin-top:25px;"><?php echo $error?></div>
    </center>
<?php } ?>
<div id="form-status"></div>
<h2 id="inlogin-header">Verification for <?php echo $verify_user; ?></h2>
<small style="text-align:center;display:block;"><?php echo blog_title() ?></small>
<div id="inlogin-area">
    <div class="inlogin-form-center" style="margin-top:15px">
        <div style="margin-top:20px">
        <?php echo $verify_message; ?>
        </div>
        
        <a style="display:block;margin-top:15px" rel="home" href="<?php echo site_url() ?>">Back to home</a>
    </div>
</div>