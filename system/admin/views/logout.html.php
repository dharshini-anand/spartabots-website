<?php 
if ($_REQUEST['continue']) {
    user_logout(true, $_REQUEST['continue']);
} else {
    user_logout(true);
}
?>