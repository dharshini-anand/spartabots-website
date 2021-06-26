<?php
// GIT DEPLOYMENT SCRIPT v0.1
// https://gist.github.com/Matthew0x40/dcf2570ff32ea259d27d
$allowed_branches   = ['master'];
$access_token       = trim(file_get_contents('./DEPLOY_SECRET'));
// Directories that contain post deploy hooks to run after deployment
// Deploy hooks can either be .php files or .sh files
$deploy_hook_dirs   = [];
main();
function main() {
    check_access_token();
    branch_check();
    pwd();
    whoami();
    if (is_up_to_date()) {
        shell_exec_output('git status');
    } else {
        shell_exec_output('git pull origin '.BRANCH.' 2>&1');
        shell_exec_output('git status');
        exec_deploy_hooks();
    }
}
function branch_check() {
    $branch = shell_exec('git branch | grep \*');
    foreach ($GLOBALS['allowed_branches'] as $allowed_branch) {
        if (strpos($branch, $allowed_branch) !== false) {
            define('BRANCH', $allowed_branch);
            return;
        }
    }
    die('Unable to determine branch');
}
function is_up_to_date() {
    $remote_commit = trim(str_replace("\n", " ", shell_exec('git ls-remote origin -h refs/heads/'.BRANCH)));
    list($remote_commit) = explode(' ', $remote_commit, 2);
    $local_commit = trim(str_replace("\n", " ", shell_exec('git rev-parse HEAD')));
    $uptodate = strpos($remote_commit, $local_commit) !== false;
    manual_output('Checking if up-to-date');
    manual_output(null, $uptodate ? 'Already up-to-date, pull is not required' : 'Not up-to-date, pull required');
    manual_output(null, 'Remote commit: ' . $remote_commit);
    manual_output(null, 'Local commit:  ' . $local_commit);
    return $uptodate;
}
function exec_deploy_hooks() {
    if (empty($GLOBALS['deploy_hook_dirs'])) {
        return;
    }
    foreach ($GLOBALS['deploy_hook_dirs'] as $dir) {
        if (file_exists($dir)) {
            foreach (glob("{$dir}/*.php") as $filename) {
                include $filename;
            }
            if (is_win()) {
                foreach (glob("{$dir}/*.bat") as $filename) {
                    system("cmd /c {$filename}");
                }
            } else {
                foreach (glob("{$dir}/*.sh") as $filename) {
                    shell_exec("sh {$filename}");
                }
            }
        }
    }
}
function check_access_token() {
    if (!isset($_REQUEST['token']) || trim($_REQUEST['token']) !== $GLOBALS['access_token']) {
        die('Access denied');
    }
}
function whoami() {
    if (is_win()) {
        shell_exec_output('echo %USERNAME%');
    } else {
        shell_exec_output('whoami');
    }
}
function pwd() {
    if (is_win()) {
        shell_exec_output('echo %cd%');
    } else {
        shell_exec_output('echo $PWD');
    }
}
function shell_exec_output($command = null) {
    $out = shell_exec($command);
    manual_output($command, $out);
}
function manual_output($command = null, $out = null) {
    static $_output = '';
    if ($command === null && $out === null) {
        return $_output;
    }
    if (isset($command)) {
        $_output .= '<div><span style="color:#6BE234;">$</span>&nbsp;<span style="color: #729FCF;">'.xssafe($command).'</span></div>';
    }
    if (isset($out)) {
        $_output .= '<div>'.xssafe(trim($out)).'</div>';
    }
}
function xssafe($data, $encoding='UTF-8') {
   return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
}
function is_win() {
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>GIT DEPLOYMENT SCRIPT</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    background: #000;
    color: #fff;
    padding: 20px 30px;
    font-weight: bold;
    font-size: 14px;
    line-height: 26px;
    font-family: Consolas,Menlo,Monaco,Lucida Console,Liberation Mono,DejaVu Sans Mono,Bitstream Vera Sans Mono,Courier New,monospace,sans-serif;
}
</style>
</head>
<body>
    <div style="margin-bottom:20px">GIT DEPLOYMENT SCRIPT</div>
    <?php echo manual_output(); ?>
</body>
</html>
