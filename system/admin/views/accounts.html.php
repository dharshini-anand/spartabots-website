<?php
if (!empty($error)) {
    echo('<div class="error-message">'.$error.'</div>');
}

if (!empty($info)) {
    echo('<div class="success-message">'.$info.'</div>');
}
?>
<script>
function submitCreateAccountForm() {
    $.post( "/admin/accounts/create-account", {
        username: $('[name=username]').val(),
        password1: $('[name=password1]').val(),
        password2: $('[name=password2]').val(),
        account_type: $('[name=account_type]').val(),
        account_email: $('[name=account_email]').val(),
        account_realname: $('[name=account_realname]').val(),
        account_verify: $('[name=account_verify]').is(':checked') ? 1 : 0
    }, function(data) {
        $('#form-status').html(data);
        document.getElementById("create-account-form").reset();
    });
}
function showLoginHistory(name) {
    $.post( "/admin/accounts/get-login-history", {
        username: name
    }, function(data) {
        $('#login-history-popup').html(data);
        $('#login-history-popup').fadeIn();
    });
}
function deleteLoginHistory(name) {
    $.post( "/admin/accounts/delete-login-history", {
        username: name
    }, function(data) {
        $('.login-history-content').html(data);
    });
}
</script>
<section>
    <div id="form-status"></div>
    <div id="login-history-popup"></div>
    
    <div class="tabs">
        <div class="tab-header">
            <span id="account-list-tab" class="tab active" onclick="$('#account-list-tab').addClass('active');$('#create-admin-account-tab').removeClass('active');$('#account-list-content').show();$('#create-admin-account-content').hide()">Account List</span>
            <span id="create-admin-account-tab" class="tab" onclick="$('#account-list-tab').removeClass('active');$('#create-admin-account-tab').addClass('active');$('#account-list-content').hide();$('#create-admin-account-content').show()">Create Account</span>
        </div>
        <div id="account-list-content">
            <table id="accounts-list">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Real name</th>
                        <th>Email</th>
                        <th>User Role</th>
                        <th>Operations</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $files = user_data_file_glob();
                $files_count = count($files);
                
                for ($i = 0; $i < $files_count; $i++):
                    $user_data = parse_ini_file($files[$i]);
                    $name = explode('/', preg_replace('/\.[^.]*$/', '', remove_first($files[$i], 'config/users/')))[0];
                    $user_id = user_id($name);
                    $role_color = '';
                    if ($user_data['role'] == 'admin') {
                        $role_color = 'color:#bf0000;font-weight:600';
                    }
                    if ($user_data['role'] == 'editor') {
                        /*$role_color = 'color:#435dc9;';*/
                        $role_color = 'color:#19925d;font-weight:600';
                    }
                ?>
                    <tr>
                        <td style="position:relative;">
                            <span style="display: block;position: absolute;top: 0;right: 0;height: 65px;left: 0;">
                                <input type="checkbox" id="uselect-<?php echo $user_id; ?>" />
                                <label for="uselect-<?php echo $user_id; ?>" title="User #<?php echo $user_id; ?>"
                                    style="position: absolute;top: 0;right: 0;bottom: 0;left: 0;margin: auto 0;height: 22px;"><?php echo $name; ?></label>
                            </span>
                        </td>
                        <td><?php echo (empty($user_data['realname']) ? 'Not set' : $user_data['realname']) ?></td>
                        <td><?php echo $user_data['email']; ?></td>
                        <td><span style="<?php echo $role_color; ?>"><?php echo $user_data['role']; ?></span></td>
                        <td><a href="#" onclick="showLoginHistory('<?php echo $name ?>');return false">Login History</a></td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>
        <div id="create-admin-account-content" style="display:none">
            <form id="create-account-form" method="POST" action="/admin/accounts/create-account" onsubmit="submitCreateAccountForm();return false">
                <input type="text" spellcheck="false" autocomplete="off" name="username" placeholder="Username" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <input type="password" autocomplete="off" name="password1" placeholder="Password" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <input type="password" autocomplete="off" name="password2" placeholder="Confirm Password" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <input type="email" autocomplete="off" name="account_email" placeholder="Email" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <input type="text" autocomplete="off" name="account_realname" placeholder="Real name" style="display:block;margin-bottom:5px;max-width:300px;width:100%;" />
                <select name="account_type">
                    <option value="default">Normal Member</option>
                    <option value="editor">Editor</option>
                    <option value="admin">Admin</option>
                </select>
                <div style="margin-top:10px">
                    <input type="checkbox" id="account-verify" name="account_verify" value="1" /><label style="margin-left:5px" for="account-verify">Send verification email</label>
                </div>
                <div style="height:20px"></div>
                <input type="submit" style="display:block" value="Create Account" />
            </form>
        </div>
    </div>
</section>