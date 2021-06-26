<?php
if (!empty($error)) {
    echo('<div class="error-message">'.$error.'</div>');
}

if (!empty($info)) {
    echo('<div class="success-message">'.$info.'</div>');
}

if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

$profile_filename = 'content/' . $user . '/author.md';

if(file_exists($profile_filename)) {
    $content = file_get_contents($profile_filename);
    $arr = explode('t-->', $content);
    if(isset($arr[1])) {
        $old_profile_title = ltrim(rtrim(str_replace('<!--t','',$arr[0]), ' '));
        $old_profile_content = ltrim($arr[1]);
    }
    else {
        $old_profile_title = $user;
        $old_profile_content = ltrim($arr[0]);
    }
}
else {
        $old_profile_title = $user;
        $old_profile_content = 'Just another HTMLy user.';
}
?>
<script>
function submitChangePasswordForm() {
    $.post( "/admin/accounts/change-password", {
        password1: $('[name=password1]').val(),
        password2: $('[name=password2]').val()
    }, function(data) {
        $('#form-status').html(data);
        document.getElementById("account-change-password-form").reset();
    });
}
</script>
<section>
    <div id="form-status"></div>
    <div class="tabs">
        <div class="tab-header">
            <span class="tab" style="cursor:default;"><?php echo $user ?></span>
            <span id="profile-edit-tab" class="tab active" onclick="$('#profile-edit-tab').addClass('active');$('#account-settings-tab').removeClass('active');$('#profile-edit-content').show();$('#account-settings-content').hide()">Profile</span>
            <span id="account-settings-tab" class="tab" onclick="$('#profile-edit-tab').removeClass('active');$('#account-settings-tab').addClass('active');$('#profile-edit-content').hide();$('#account-settings-content').show()">Change password</span>
        </div>
        <div id="profile-edit-content">
            <link rel="stylesheet" type="text/css" href="<?php echo site_url() ?>system/admin/editor/css/editor.css" />
            <script type="text/javascript" src="<?php echo site_url() ?>system/admin/editor/js/Markdown.Converter.js"></script>
            <script type="text/javascript" src="<?php echo site_url() ?>system/admin/editor/js/Markdown.Sanitizer.js"></script>
            <script type="text/javascript" src="<?php echo site_url() ?>system/admin/editor/js/Markdown.Editor.js"></script>
            <?php if (isset($error)) { ?>
                <div class="error-message"><?php echo $error ?></div>
             <?php } ?>
            <div class="wmd-panel" style="margin-top: 20px;">
                <form method="POST" action="/edit/profile">
                    <div style="display:none">
                        Title <span class="required">*</span><br>
                        <input type="hidden" name="title" class="text <?php if (isset($postTitle)) { if (empty($postTitle)) { echo 'error';}} ?>" value="<?php echo $old_profile_title?>"/>
                        <br><br>
                    </div>
                    <div id="wmd-button-bar" class="wmd-button-bar"></div>
                    <textarea id="wmd-input" class="wmd-input <?php if (isset($postContent)) { if (empty($postContent)) { echo 'error';}} ?>" name="content" cols="20" rows="10"><?php echo $old_profile_content ?></textarea><br>
                    <input type="submit" name="submit" class="submit" value="Save"/>
                </form>
            </div>
            <div id="wmd-preview-wrapper">
                <h3>Preview</h3>
                <div id="wmd-preview" class="wmd-panel wmd-preview"></div>
            </div>
            <script type="text/javascript">
            (function () {
                var converter = new Markdown.Converter();

                var editor = new Markdown.Editor(converter);
                
                editor.run();
            })();
            </script>
            <div class="clearfix"></div>
        </div>
        <div id="account-settings-content" style="display:none">
            <form id="account-change-password-form" method="POST" action="/admin/accounts/change-password" onsubmit="submitChangePasswordForm();return false">
                <input type="password" name="password1" placeholder="Password" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <input type="password" name="password2" placeholder="Confirm Password" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <input type="submit" style="display:block" value="Change Password" />
            </form>
        </div>
    </div>
</section>