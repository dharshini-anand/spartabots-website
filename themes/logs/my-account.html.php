<style>
.resource-panel:not([data-mode=<?php echo $edit_mode ?>]) {
    display: none;
}
</style>

<script>
function myAccountSubmit() {
    $.post( '/my-account', {
        current_password: $('[name=current_password]').val(),
        password1: $('[name=password1]').val(),
        password2: $('[name=password2]').val(),
        email: $('[name=email]').val(),
        realname: $('[name=realname]').val()
    }, function(data) {
        $('#form-status').html(data);
        $('[name=current_password]').val('');
        $('[name=password1]').val('');
        $('[name=password2]').val('');
    });
}

function myPrefSubmit() {
    $.post( '/my-preferences', {
        author_info: $('[name=author_info]').is(':checked'),
        img_thumbnail: $('[name=img_thumbnail]').is(':checked'),
        teaser_type: $('[name=teaser_type]').is(':checked'),
        
        teaser_char: $('[name=teaser_char]').val(),
        related_count: $('[name=related_count]').val(),
        posts_perpage: $('[name=posts_perpage]').val(),
        archive_perpage: $('[name=archive_perpage]').val(),
        search_perpage: $('[name=search_perpage]').val(),
        profile_perpage: $('[name=profile_perpage]').val()
    }, function(data) {
        $('#form-status').html(data);
    });
}

</script>

<div id="form-status"></div>
<div class="wrapper responsive">
<div class="resource-pane-wrapper">
<div class="resource-pane">
    <div class="resource-sidebar">
        <ul>
            <li><h3 class="resource-sidebar-title">Navigation</h3></li>
            <li><a href="/my-account">My Account</a></li>
            <li><a href="/my-preferences">Preferences</a></li>
        </ul>
    </div>
    <div class="resource-panel" data-mode="account">
        <h2>My Account</h2>
        
        <form id="my-account-form" method="POST" onsubmit="myAccountSubmit();return false">
            <fieldset>
                <h3>Change Password</h3>
                
                <label><span class="field-name">Current Password:</span><input type="password" name="current_password" /></label>
                <label><span class="field-name">New Password:</span><input type="password" name="password1" /></label>
                <label><span class="field-name">Confirm Password:</span><input type="password" name="password2" /></label>
            </fieldset>
            <fieldset>
                <h3>Settings</h3>
                
                <label><span class="field-name">Email:</span><input type="email" name="email" value="<?php echo $current_email; ?>" /></label>
                <label><span class="field-name">Real name:</span><input type="text" name="realname" value="<?php echo $current_realname; ?>" /></label>
            </fieldset>
            
            <div class="special-btn">
                <input type="submit" value="Submit">
                <i class="special-btn-icon fa fa-send"></i>
            </div>
        </form>
        <hr style="margin:20px 0;" />
        <form id="delete-my-account-form" method="POST" action="/my-account/delete" onsubmit="if ($('#delete-confirm').is(':checked')){if (confirm('Are you sure you want to delete your account? This is the last confirmation.')) {return true;}else{return false;}}else{alert('Check the delete confirmation checkbox to delete your account.');return false}">
            <fieldset>
                <h3>Delete account</h3>
                <input type="checkbox" id="delete-confirm" name="delete_confirm" value="1" />
                <label style="display:block" for="delete-confirm">I really want to delete my account</label>
            </fieldset>
            <div class="special-btn">
                <input type="submit" value="Delete account">
                <i class="special-btn-icon fa fa-times-circle"></i>
            </div>
        </form>
    </div>
    <div class="resource-panel" data-mode="preferences">
        <h2>Preferences</h2>
        
        <form id="my-preferences-form" method="POST" onsubmit="myPrefSubmit();return false">
            <div style="margin-top:15px">
            <div class="pref-field">
                <input type="checkbox" id="author_info" name="author_info" value="1" <?php if (pref('author.info') == "true") { echo "checked"; } ?> />
                <label for="author_info">Display author info on posts</label>
            </div>
            <div class="pref-field">
                <input type="checkbox" id="img_thumbnail" name="img_thumbnail" value="1" <?php if (pref('img.thumbnail') == "true") { echo "checked"; } ?> />
                <label for="img_thumbnail">Show image thumbnail on teasers</label>
            </div>
            <div class="pref-field">
                <input type="checkbox" id="teaser_type" name="teaser_type" value="1" <?php if (pref('teaser.type') == "trimmed") { echo "checked"; } ?> />
                <label for="teaser_type">Trim post teaser</label>
            </div>
            </div>
            
            <div style="margin-top:20px;margin-bottom:25px">
            <div class="pref-field">
                <label class="field-name" for="teaser_char">Characters per trimmed teaser</label>
                <input required type="number" id="teaser_char" name="teaser_char" value="<?php echo pref('teaser.char') ?>" min="1" step="1" />
            </div>
            <div class="pref-field">
                <label class="field-name" for="related_count">Number of related posts</label>
                <input required type="number" id="related_count" name="related_count" value="<?php echo pref('related.count') ?>" min="1" max="10" step="1" />
            </div>
            <div class="pref-field">
                <label class="field-name" for="posts_perpage">Posts per page</label>
                <input required type="number" id="posts_perpage" name="posts_perpage" value="<?php echo pref('posts.perpage') ?>" min="1" max="50" step="1" />
            </div>
            <div class="pref-field">
                <label class="field-name" for="archive_perpage">Archive items per page</label>
                <input required type="number" id="archive_perpage" name="archive_perpage" value="<?php echo pref('archive.perpage') ?>" min="1" max="50" step="1" />
            </div>
            <div class="pref-field">
                <label class="field-name" for="search_perpage">Search results per page</label>
                <input required type="number" id="search_perpage" name="search_perpage" value="<?php echo pref('search.perpage') ?>" min="1" max="50" step="1" />
            </div>
            <div class="pref-field">
                <label class="field-name" for="profile_perpage">Posts per page in profile</label>
                <input required type="number" id="profile_perpage" name="profile_perpage" value="<?php echo pref('profile.perpage') ?>" min="1" max="50" step="1" />
            </div>
            </div>
            
            <div class="special-btn">
                <input type="submit" value="Submit">
                <i class="special-btn-icon fa fa-send"></i>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
</div>
</div>