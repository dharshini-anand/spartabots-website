<?php
date_default_timezone_set('America/Los_Angeles');

// Explicitly include dispatch framework and functions.php
require_once 'system/includes/dispatch.php';
require_once 'system/includes/functions.php';
require_once 'system/admin/admin.php';
require_once 'system/includes/session.php';
include_once 'system/includes/opml.php';
include_once 'system/includes/file_type.php';

// Load configuration
config('source', 'config/config.ini');

// The front page of the website.
// This will match the root url
get('/index', function () {
	$posts = get_posts(null, 1, 1); // null, page #, posts per page

	$total = '';

	$tl = blog_tagline();

	if($tl){ $tagline = ' | ' . $tl;} else {$tagline = '';}

    render('index',array(
		'head_contents' => head_contents(blog_title() . $tagline, blog_description(), site_url()),
    	'page' => 1,
		'posts' => $posts,
		'bodyclass' => 'infront siteindex',
		'breadcrumb' => '',
		'pagination' => has_pagination($total, $perpage, $page)
	));
});

// The blog
get('/blog', function () {

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;
	$perpage = pref('posts.perpage');

	$posts = get_posts(null, $page, $perpage);

	$total = '';

	$tl = blog_tagline();

	if($tl){ $tagline = ' | ' . $tl;} else {$tagline = '';}

	if(empty($posts) || $page < 1){

		// a non-existing page
		render('no-posts',array(
			'head_contents' => head_contents('Blog | ' . blog_title(), blog_description(), site_url()),
			'bodyclass' => 'noposts',
		));

		die;
	}

    render('blog',array(
		'head_contents' => head_contents('Blog | ' . blog_title(), blog_description(), site_url()),
    	'page' => $page,
		'posts' => $posts,
		'bodyclass' => 'blog-infront',
		'breadcrumb' => '',
		'pagination' => has_pagination($total, $perpage, $page)
	));
});

// stuff that gets changed a lot done here, mostly redirects
// ---------------------------------------------------------
get('/survey', function(){
	header('Location: https://docs.google.com/forms/d/e/1FAIpQLScnevlqvayzlkv4MjzSmlvsZ5KMcnfUl7dv0sdkeYssBPIEDw/viewform?usp=sf_link', true, 303);
	die();
});
get('/remind', function(){
	header('Location: https://www.remind.com/join/team2976', true, 303);
	die();
});
get('/discord', function(){
	header('Location: https://discord.gg/SUufW2B', true, 303);
	die();
});
get('/sign-in', function() {
    //header('Location: https://forms.gle/kTvJWZyZUfMxyWPs7', true, 303);
	header('Location: https://docs.google.com/forms/d/e/1FAIpQLSe7_g9T2RUZmuIYLD06JavzH-K-EqxRtmSnh5FN6kJcYyzR3w/viewform?usp=sf_link', true, 303);
    die();
});
get('/training', function() {
    header('Location: https://docs.google.com/document/d/e/2PACX-1vRkpfTR2kTZoGh0R31sASS6wkG0dJ0AIYYgnAzhi3tpP8kNppOHzHsmAB5jjF2sg5RffJUYpg3_27MP/pub', true, 303);
    die();
});
get('/online-sign-up', function() {
    header('Location: https://forms.gle/uBfVaPWk8UPcGqSu6', true, 303);
    die();
});

post('/new-mailgun-email', function() {
	$webhook_recipient = $_POST['recipient'];
	$webhook_url = 'https://discordapp.com/api/webhooks/579841274786611201/I-XUf7kg_PxQ9O_mATmD_ZGNKaGJlWA_0xdFueOoaUCLj0C3L2ZiQNlBGgwFddKDQFMC';
	// if (stripos($webhook_recipient, 'sunnyhills') !== false) {
	// 	$webhook_url = 'https://discordapp.com/api/webhooks/490773574550945792/OKcmWKOLnybZLPveisOuAiYiQ-NI_62HEHIJEzznQJzhhtiVwAWMyRcTOAgRvw5P6gyf';
	// }
	send_discord_email_webhook($_POST['from'], $webhook_recipient, $_POST['subject'], $_POST['stripped-text'], $webhook_url);
});
// end stuff that gets changed a lot

// The profile page
get('/member/:user', function($user){
	if (!user_exists($user)) {
		render('profile',array(
			'head_contents' => head_contents('User not found | ' . blog_title(), 'User not found.', site_url() . 'member/' . $user),
            'bio' => 'No bio available.',
            'name' => 'User not found',
            'realname' => '',
            'role' => 'n/a',
			'bodyclass' => 'inprofile',
			'mode'	=> 'overview'
		));
		die();
	}

	$bio = get_bio($user);
	if (isset($bio[0])) $bio = $bio[0];
	else $bio = default_profile($user);

    render('profile',array(
		'head_contents' => head_contents($bio->title .' | ' . blog_title(), 'Profile page and all posts by ' . $bio->title . ' on ' . blog_title() . '.', site_url() . 'member/' . $user),
		'bio' => $bio->body,
		'name' => $bio->title,
		'realname' => user('realname', $user),
		'role' => user('role', $user),
		'bodyclass' => 'inprofile',
		'mode'	=> 'overview'
	));
});


get('/member/:user/:mode', function($user, $mode){
	if (!user_exists($user)) {
		render('profile',array(
			'head_contents' => head_contents('User not found | ' . blog_title(), 'User not found.', site_url() . 'member/' . $user),
            'bio' => 'No bio available.',
            'name' => 'User not found',
            'realname' => '',
            'role' => 'n/a',
			'bodyclass' => 'inprofile',
			'mode'	=> 'overview'
		));
		die();
	}

	$page = $_REQUEST['page'] ? (int) $_REQUEST['page'] : 1;

	$bio = get_bio($user);
	if (isset($bio[0])) $bio = $bio[0];
	else $bio = default_profile($user);

    render('profile',array(
		'head_contents' => head_contents('Viewing profile:  '. $bio->title .' | ' . blog_title(), 'Profile page and all posts by ' . $bio->title . ' on ' . blog_title() . '.', site_url() . 'member/' . $user),
   		'bio' => $bio->body,
		'name' => $bio->title,
		'realname' => user('realname', $user),
		'role' => user('role', $user),
		'bodyclass' => 'inprofile',
		'mode' => $mode,

		'page' => $page,
		'posts' => get_profile($user, $page, pref('profile.perpage')),
		'pagination' => has_pagination(get_count($user, 'dirname'), pref('profile.perpage'), $page)
	));
});

// Download page
function checkFile($file) {
    if (!isset($file)) {
        echo '<div>File not set</div>';
        return false;
    }
    if (!startsWith($file, '/home/public/')) {
        echo '<div>Bad file path</div>';
        return false;
    }
    if (!(startsWith($file, '/home/public/content/') || startsWith($file, '/home/public/documents/') || startsWith($file, '/home/public/images/') || startsWith($file, '/home/public/uploads/'))) {
        echo '<div>Cannot download files from that location.<//div>';
        return false;
    }
    if (!file_exists($file)) {
        echo '<div>File does not exist.</div>';
        return false;
    }
    return true;
}

get('/download', function () {
    $files = $_REQUEST['files'];
    $dl_file;
    $mime_type = 'application/octet-stream';

    if (count($files) == 0) {
        echo '<div>No files recieved.</div>';
        die;
    } else if (count($files) == 1) {
        if (!checkFile($files[0])) {
            die();
        } else {
            $dl_file = $files[0];
            $mime_type = getFileType($dl_file);
        }
    } else {
        $dl_file = 'dltemp/'. time() . '.zip';
        $zip = new ZipArchive;

        if (!$zip->open($dl_file, ZipArchive::CREATE)) {
            echo '<div>Could not open ZIP file.</div>';
            die();
        }

        foreach ($files as $file) {
            if (!checkFile($file)) {
                $zip->close();
                die();
            }

            if (is_dir($file)) {
                $rfiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file), RecursiveIteratorIterator::SELF_FIRST);

                foreach ($rfiles as $rfile) {
                    $rfile = str_replace('\\', '/', $rfile);
                    if( in_array(substr($rfile, strrpos($rfile, '/')+1), array('.', '..')) )
                        continue;
                    if (is_dir($rfile) === true) {
                        $zip->addEmptyDir(str_replace($file . '/', '', $rfile . '/'));
                    }
                    else if (is_file($rfile) === true) {
                        $zip->addFromString(str_replace($file . '/', '', $rfile), file_get_contents($rfile));
                    }
                }
            } else if (is_file($file)) {
                $zip->addFromString(basename($file), file_get_contents($file));
            }
        }
        $zip->close();
        $mime_type = 'application/zip';
    }
    $file_name = basename($dl_file);
    ob_clean();
    header("Content-Type: $mime_type");
    header("Content-disposition: attachment; filename=$file_name");
    header('Content-Length: ' . filesize($dl_file));
    readfile("$dl_file");
    ignore_user_abort(true);
    if (connection_aborted()) {
        unlink($dl_file);
    }
    die();
});

// Get Involved page
get('/get-involved', function () {
    $redirect = site_url() . 'get-involved/students';
    header("location: $redirect");
});

get('/get-involved/:category', function ($category) {
    if (!isset($category) || empty($category)) {
        $category = 'students';
    }
    if ($category != 'students' && $category != 'mentors' && $category != 'sponsors' && $category != 'donate') {
        $category = 'students';
    }

    render('get-involved',array(
		'head_contents' => head_contents('Get Involved | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'involvedpage',
        'category'      => $category,
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Get Involved'
	));
});

get('/students', function () {
    $redirect = site_url() . 'get-involved/students';
    header("location: $redirect");
});
get('/mentors', function () {
    $redirect = site_url() . 'get-involved/mentors';
    header("location: $redirect");
});
get('/sponsors', function () {
    $redirect = site_url() . 'get-involved/sponsors';
    header("location: $redirect");
});
get('/donate', function () {
    $redirect = site_url() . 'get-involved/donate';
    header("location: $redirect");
});

// Stuff
get('/sudharsan', function () {
    render('sudharsan',array(
		'head_contents' => head_contents('Sudharsan is Fabulous | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'omgsudharsanpage'
	));
});

get('/steven', function () {
    render('steven',null,false);
});
get('/totes-ma-goats', function () {
    render('totes-ma-goats',null,false);
});
get('/kwwxis', function () {
    render('kwwxis',null,false);
});
get('/god', function () {
	render('godstafa',null,false);
});
/*
get('/custom-email', function () {
    render('custom-email',null,false);
});

post('/spartabots-custom-email', function () {
    $senderName = $_POST["sender_name"];
    $sender = $_POST["sender"];
    $replyTo = $_POST["reply_to"];
    $recipient = $_POST["recipient"];
    $subject = $_POST["subject"];
    $content = $_POST["message"];
    $password = $_POST["password"];

    if(!isset($password) || $password !== "AJAY_PAI_IS_AMAZING" || !(isset($senderName) && isset($sender) && isset($replyTo) && isset($recipient) && isset($subject) && isset($content))) {
        echo "Invalid";
    } else {
        // the line break in double quotes is concatenated to the string in single quotes
        $headers  = 'From: ' . $senderName . ' <' . $sender . '@spartabots.org>' . "\r\n";
        $headers .= 'Reply-To: Spartabots <' . $replyTo . '>' . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";

        $message = $content;

        $message = chunk_split(base64_encode($message));

        mail($recipient, $subject, $message, $headers);
        echo "Headers:  $headers \nRecipient: $recipient \nSubject: $subject \nMessage: $message";
    }
});
*/

get('/signin', function () {
	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {
        if ($role === 'admin') {
            render('signin', null, false);
        } else if($role === 'editor') {
			$hourtime = (int) date('G');
			if($hourtime >= 12 && $hourtime <= 21) { // between 12:00 PM and 9:59 PM
				render('signin', null, false);
			} else {
				echo '<h1>Boi it\'s '.date('g:i a').'. You don\'t need the sign-in.</h1>';
			}
// 			echo '<h1>Boi it\'s '.date('g:i a').'. You don\'t need the sign-in.</h1>';
//			render('signin', null, false);
		} else {
            not_found();
        }
	} else {
		$login = site_url() . 'login?continue=https://www.spartabots.org/signin';
		header("location: $login");
	}
});

// About us page
get('/about-us', function () {
    render('about-us',array(
		'head_contents' => head_contents('About Us | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'about-us',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; About Us'
	));
});

get('/about', function () {
    $redirect = site_url() . 'about-us';
    header("location: $redirect");
});

// About us more pages
get('/about/:page', function ($page) {
    $redirect = site_url() . 'about-us/' . $page;
    header("location: $redirect");
});

get('/about-us/:page', function ($page) {
    $content;
    $page_name = 'Page not found';
    $page_content = 'No content.';

    if (file_exists('content/static/about-us-' . $page . '.md')) {
        $content = markdown_transform(file_get_contents('content/static/about-us-' . $page . '.md'));
    }

    if (isset($content)) {
        $arr = explode('t-->', $content);
        if(isset($arr[1])) {
            $title = str_replace('<!--t','',$arr[0]);
            $title = rtrim(ltrim($title, ' '), ' ');
            $page_name = remove_first(remove_first($title, 'About Us: '), 'about us: ');
            $page_content = $arr[1];
        }
        else {
            $page_name = 'Untitled page';
            $page_content = $arr[0];
        }
    }

    render('about-us-pages',array(
		'head_contents' => head_contents($page_name . ' | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'about-pages',
        'page_content'  => $page_content,
        'page_name'     => $page_name,
        'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                '<a href="'. site_url() . 'about-us">About Us</a>',
                                $page_name)
	));
});

// My account page
get('/my-account', function () {
    if(login()) {
        $user = $_SESSION['user'];
        $current_realname = user('realname', $user);
        if (empty($current_realname)) {
            $current_realname = 'Real name not set';
        }

        render('my-account',array(
            'head_contents' => head_contents('My Account | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'editaccount resourcepage',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; My Account',
            'edit_mode'     => 'account',
            'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                    'My Account'),
            'current_email' => user('email', $user),
            'current_realname' => $current_realname
        ));
    } else {
        render('denied',array(
            'head_contents' => head_contents('My Account | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'editaccount resourcepage',
            'message'       => 'You must be logged in to view this page.',
            'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                    'My Account')
        ));
    }
});

post('/my-account', function () {
    $user = $_SESSION['user'];
	$role = user('role', $user);

    if(login()) {
        $current_pass   = from($_REQUEST, 'current_password');
        $pass1          = from($_REQUEST, 'password1');
        $pass2          = from($_REQUEST, 'password2');
        $email          = from($_REQUEST, 'email');
        $realname       = from($_REQUEST, 'realname');
        if (empty($email)) {
            $email = user('email', $user);
        }
        if (empty($realname)) {
            $realname = user('realname', $user);
        }

        if(!empty($pass1) && !empty($pass2) && !empty($current_pass)) {
            $user_pass_hash = user('password_hash', $user);
            $user_salt = user('salt', $user);

            $input_pass_hash = pbkdf2('SHA256', $current_pass, $user_salt, 1024, 64 * 8);

            if($input_pass_hash === $user_pass_hash) {
                if ($pass1 === $pass2) {
                    create_account($user, $pass1, $role, $email, $realname);

                    echo '<div class="success-message">Settings changed (password included)<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                } else {
                    echo '<div class="error-message">Passwords do not match<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                }
            } else {
                echo '<div class="error-message">Current password field incorrect<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
            }
        } else {
            change_account_settings($user, $role, $email, $realname);
            echo '<div class="success-message">Settings changed (password not included)<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
        }
    } else {
        echo 'Access Denied: not logged in.';
    }
});

post('/my-account/delete', function () {
    $delete_confirm = isset($_POST['delete_confirm']) && $_POST['delete_confirm']  ? "1" : "0";
    if ($delete_confirm) {
        $user = $_SESSION['user']; // get username

        session_destroy(); // log out user

        rrmdir(user_data_path($user)); // delete user data file

        $redirect = site_url();
        header("location: $redirect");
    } else {
        echo '<html><body>Account deletion failed: Delete confirmation checkbox not checked.<br/><a href="'.site_url().'">Return to home</a></body></html>';
    }
});

// Preferences page
get('/my-preferences', function () {
    if (login()) {
        render('my-account',array(
            'head_contents' => head_contents('Preferences | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'editaccount resourcepage',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Preferences',
            'edit_mode'     => 'preferences',
            'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                    'My preferences')
        ));
    } else {
        render('denied',array(
            'head_contents' => head_contents('Preferences | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'editaccount resourcepage',
            'message'       => 'You must be logged in to view this page.',
            'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                    'My preferences')
        ));
    }
});

post('/my-preferences', function () {
    $author_info = isset($_POST['author_info']) && $_POST['author_info'] == 'true' ? "true" : "false";
    $img_thumbnail = isset($_POST['img_thumbnail']) && $_POST['img_thumbnail'] == 'true'  ? "true" : "false";
    $teaser_type = isset($_POST['teaser_type']) && $_POST['teaser_type'] == 'true'  ? "trimmed" : "full";

    $teaser_char = intval($_POST['teaser_char']);
    if ($teaser_char < 1) $teaser_char = 1;

    $related_count = intval($_POST['related_count']);
    if ($related_count < 1) $related_count = 1;
    if ($related_count > 10) $related_count = 10;

    $posts_perpage = intval($_POST['posts_perpage']);
    if ($posts_perpage < 1) $posts_perpage = 1;
    if ($posts_perpage > 50) $posts_perpage = 50;

    $archive_perpage = intval($_POST['archive_perpage']);
    if ($archive_perpage < 1) $archive_perpage = 1;
    if ($archive_perpage > 50) $archive_perpage = 50;

    $search_perpage = intval($_POST['search_perpage']);
    if ($search_perpage < 1) $search_perpage = 1;
    if ($search_perpage > 50) $search_perpage = 50;

    $profile_perpage = intval($_POST['profile_perpage']);
    if ($profile_perpage < 1) $profile_perpage = 1;
    if ($profile_perpage > 50) $profile_perpage = 50;

    $pref = "author.info = \"$author_info\"\n";
    $pref .= "img.thumbnail = \"$img_thumbnail\"\n";
    $pref .= "teaser.type = \"$teaser_type\"\n";
    $pref .= "teaser.char = \"$teaser_char\"\n";
    $pref .= "related.count = \"$related_count\"\n";
    $pref .= "posts.perpage = \"$posts_perpage\"\n";
    $pref .= "archive.perpage = \"$archive_perpage\"\n";
    $pref .= "search.perpage = \"$search_perpage\"\n";
    $pref .= "profile.perpage = \"$profile_perpage\"";

    $user = $_SESSION['user'];
    change_account_pref($user, $pref);

    echo '<div class="success-message">Account preferences changed.<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
});

if (startsWith($_SERVER['REQUEST_URI'], '/docs')) {
    $s = remove_first($_SERVER['REQUEST_URI'], '/docs');
    if (startsWith($s, '/')) {
        $file_path = remove_first($s, '/');
        if (!endsWith($file_path, '/')) {
            $file_path .= '/';
        }
        if (startsWith($file_path, '/')) {
            $file_path = remove_first($file_path, '/');
        }

        render('resources',array(
            'head_contents' => head_contents('Documents | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'resources resourcepage',
            'view_mode'     => 'docs',
            'file_path'     => $file_path,
            'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                    '<a href="' . site_url() . 'resources">Resources</a>',
                                    'Documents')
        ));
        die();
    } else {
        $file_mgr_breadcrumb = 'Documents';
        render('resources',array(
            'head_contents' => head_contents('Documents | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'resources resourcepage',
            'view_mode'     => 'docs',
            'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                    '<a href="' . site_url() . 'resources">Resources</a>',
                                    'Documents')
        ));
        die();
    }
}

get('/docs/get_docs', function () {
    $file_path = $_REQUEST['file_path'];
    if (!isset($file_path)) {
        $file_path = '';
    }

    $search_filter = $_REQUEST['search_filter'];
    if (!isset($search_filter)) {
        $search_filter = '';
    }

    get_docs($file_path, $search_filter);
});

get('/docs/get_docs_props', function () {
    $file = $_REQUEST['file'];
    if (!isset($file)) {
        $file = '';
    }

    echo get_docs_props($file);
});

get('/docs/search-docs', function () {
    render('resources',array(
        'head_contents' => head_contents('Resources | ' . blog_title(), blog_description(), site_url()),
        'bodyclass'     => 'resources resourcepage',
        'view_mode'     => 'search-docs-get',
        'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                '<a href="' . site_url() . 'resources">Resources</a>',
                                'Search Documents')
    ));
    die();
});

post('/docs/search-docs', function () {
    render('resources',array(
        'head_contents' => head_contents('Resources | ' . blog_title(), blog_description(), site_url()),
        'bodyclass'     => 'resources resourcepage',
        'view_mode'     => 'search-docs-post',
        'file_path'     => urldecode($_POST['file_path']),
        'search_text'   => $_POST['search'],
        'dir_url'       => $_POST['resources_dir_url'],
        'dir_name'      => $_POST['dir_name'],
        'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                '<a href="' . site_url() . 'resources">Resources</a>',
                                'Search Documents')
    ));
    die();
});

get('/docs/:mode', function ($mode) {
    render('resources',array(
        'head_contents' => head_contents('Resources | ' . blog_title(), blog_description(), site_url()),
        'bodyclass'     => 'resources resourcepage',
        'view_mode'     => $mode,
        'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                '<a href="' . site_url() . 'resources">Resources</a>',
                                $mode)
    ));
});

// Contact us page
get('/contact-us', function () {
    render('contact-us',array(
		'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'contact-us',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us'
	));
});

post('/contact-us', function () {
	$email = from($_REQUEST, 'email');
	$subject = from($_REQUEST, 'subject');
	$msg = from($_REQUEST, 'message');
	$fake = from($_REQUEST, 'url');

    if(!empty($fake)) {
        render('contact-us',array(
            'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'contact-us',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us',
            'info'          => '<ul><li><b>Info</b> Your message has been sent.</li></ul>'
        ));
        return;
    }

    if(!empty($email)) {
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            render('contact-us',array(
                'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
                'bodyclass'     => 'contact-us',
                'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us',
                'error'         => '<ul><li><b>Error</b> Invalid email address.</li></ul>'
            ));
        }
    }

    if (empty($msg)) {
        render('contact-us',array(
            'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'contact-us',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us',
            'error'         => '<ul><li><b>Error</b> The message field must be filled in.</li></ul>'
        ));
    }

    if (empty($subject)) {
        render('contact-us',array(
            'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'contact-us',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us',
            'error'         => '<ul><li><b>Error</b> The subject field must be filled in.</li></ul>'
        ));
    }
	
	// added the stripos thing to filter out some annoying spam - Vishal
	if(!empty($email) && !empty($msg) && !empty($subject) && stripos($msg,'sexy') === FALSE) {
        $recipient  = "skyline.spartabots@gmail.com";
        $date = date('m/d/Y g:s a T');

        $message = 'Hello!<br/></br><p>Your contact form has been submitted by '. htmlspecialchars($email) .' from http://www.spartabots.org/contact-us:</p></br>';
        $message .= '<table rules="all" style="border-color:#ccc; width:80%" cellpadding="10">';
        $message .= "<tr><td style='background:#eee;' colspan='2'><strong>Info:</strong></td></tr>";
        $message .= "<tr><td><strong>Sender Email:</strong> </td><td>" . htmlspecialchars($email) . "</td></tr>";
        $message .= "<tr><td><strong>Subject:</strong> </td><td>" . htmlspecialchars($subject) . "</td></tr>";
        $message .= "<tr><td><strong>Date Sent:</strong> </td><td>" . $date . "</td></tr>";
        $message .= "<tr><td style='background:#eee;' colspan='2'><strong>Message:</strong></td></tr>";
        $message .= "<tr><td colspan='2'>" . markdown_transform(htmlspecialchars($msg)) . "</td></tr>";
        $message .= "</table>";

        send_no_reply_email("skyline.spartabots@gmail.com", 'Contact Form: ' . $subject . '   ' . $date, $message);

        render('contact-us',array(
            'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'contact-us',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us',
            'info'          => '<ul><li><b>Info</b> Your message has been sent.</li></ul>'
        ));
    } else {
        render('contact-us',array(
            'head_contents' => head_contents('Contact Us | ' . blog_title(), blog_description(), site_url()),
            'bodyclass'     => 'contact-us',
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Contact Us',
            'error'         => '<ul><li><b>Error</b> One or more fields must be filled in.</li></ul>'
        ));
    }
});

// Purchase request page
get('/purchase-request', function () {
    render('purchase-request',array(
		'head_contents' => head_contents('Purchase Request | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'purchasereq',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; <a href="' . site_url() . 'team-resources"></a>Purchase Request'
	));
});

post('/purchase-request', function () {
    // Format data
    $data = $_POST['data'];
    $data1 = explode(';',rtrim($data, ';'));
    $items = array();
    foreach ($data1 as $data2) {
        array_push($items, explode(',', $data2));
    }

    $owner = $_POST['owner'];
    $owner = $_POST['owner'];
    $At = $_POST['At'];
    if (empty($owner)) {
        echo 'The "Requester name" field must be filled out.';
        return;
    }

    // Actual stuff
    $date = date('m/d/Y');
    $dateTime = date('m/d/Y g:s a T');
    $item_count = 0;
    $missing_data = false;
    $bad_data = false;
    $table_construct = <<<EOF
    <table rules="rows" style="border-color:#ccc; width:100%" cellpadding="10">
        <thead>
            <tr style="text-align:left">
                <th>Vendor</th>
                <th style="width:50px;">Qty</th>
                <th>Item</th>
                <th>Part # / ASN</th>
                <th style="width:50px;">Price</th>
                <th style="width:60px;">Total</th>
            </tr>
        </thead>
    <tbody>
EOF;
    $subTotal = 0;
    foreach ($items as $item) {
        $item_str = implode(',', $item); // Only consists of commas if empty
        if (str_replace(',', '', $item_str) == '') { // Hack-ish way to determine if item row is completely empty
            continue;
        }

        $table_construct .= '<tr>';
        foreach ($item as $item_field) {
            if (empty($item_field)) {
                $missing_data = true;
                continue 2;
            }
        }

        if (!is_numeric(urldecode($item[1])) || !is_numeric(urldecode($item[4]))) {
            $bad_data = true;
            continue;
        }

        $item_total = (float) $item[1] * (float) $item[4];
        $subTotal += $item_total;


        $table_construct .= '<td>'.htmlspecialchars(urldecode($item[0])).'</td><td>'.htmlspecialchars(urldecode($item[1])).'</td><td>'.htmlspecialchars(urldecode($item[2])).'</td><td>'.htmlspecialchars(urldecode($item[3])).'</td><td>'.htmlspecialchars(urldecode($item[4])).'</td><td>$'.$item_total.'</td></tr>';

        $item_count++;
    }
    $table_construct .= '<td colspan="4"></td><td style="text-align:right;padding-right:4px">Subtotal:</td><td>$'.$subTotal.'</td></table></tbody>';

    if ($item_count == 0) {
        echo 'Please submit at least one item.';
        return;
    }
    if ($missing_data) {
        echo 'One or more items have missing data.';
        echo '<ul><li>Leave an item row completely empty to have it ignored</li><li>Partially filled out item rows are not accepted.</li></ul>';
        return;
    }
    if ($bad_data) {
        echo 'One or more items have invalid "Qty" or "Unit Price" data (data is not a number).';
        return;
    }

    $message = '<div style=\"font-size:17px;line-height:22px;font-family:arial,sans-serif;\">';
    $message .= '<table rules="all" style="border-color:#ccc; width:80%" cellpadding="10">';
    $message .= "<tr><td style='background:#eee;' colspan='2'><strong>Info:</strong></td></tr>";
    $message .= "<tr><td><strong>Requester:</strong> </td><td>" . htmlspecialchars($owner) . "</td></tr>";
    $message .= "<tr><td><strong>Date Sent:</strong> </td><td>" . $dateTime . "</td></tr>";
    $message .= "<tr><td><strong>Date Required:</strong> </td><td>" . htmlspecialchars($dtReq) . "</td></tr>";
    $message .= "<tr><td style='background:#eee;' colspan='2'><strong>Items Requested:</strong></td></tr>";
    $message .= "<tr><td colspan='2'>" . $table_construct . "</td></tr>";
    $message .= "<tr><td style='background:#eee;' colspan='2'><strong>Additonal Instructions:</strong></td></tr>";
    $message .= "<tr><td colspan='2'>" . markdown_transform(htmlspecialchars($At)) . "</td></tr>";
    $message .= "</table>";
    $message .= "</div>";

    send_no_reply_email("skyline.spartabots@gmail.com", 'Purchase Request from ' . $owner . ' on ' . $date, $message);
    echo 'Succesfully submitted purchase request.<br/><br/>';
    echo $table_construct;
});

// FIRST robotics page
get('/first', function () {
    render('first',array(
		'head_contents' => head_contents('FIRST | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'frcpage resourcepage',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; FIRST',
        'year'          => 0,
        'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                'FIRST')
	));
});

get('/first/:year', function($year){
    render('first', array(
		'head_contents' => head_contents('FIRST | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'frcpage resourcepage',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; FIRST',
        'year'          => (int) $year,
        'bar_crumb'     => array('<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a>',
                                '<a href="'. site_url() . 'first">FIRST</a>',
                                $year . ' FRC game')
	));
});

// The search page
get('/search', function(){
    $redirect = site_url() . 'advanced-search';
    header("location: $redirect");
});

// The advanced search page
get('/advanced-search', function () {
    render('adv-search',array(
		'head_contents' => head_contents('Advanced search | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'advsearch',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Advanced search'
	));
});

post('/advanced-search', function () {
    $keywords_all           = $_POST['search-keywords-all'];
    $required_phrase        = $_POST['search-required-phrase'];
    $keywords_any           = $_POST['search-keywords-any'];
    $keywords_none          = $_POST['search-keywords-none'];
    $required_author        = $_POST['search-required-author'];
    $required_tag           = $_POST['search-required-tag'];

    $title_keywords_all     = $_POST['search-keywords-all-title'];
    $title_required_phrase  = $_POST['search-required-phrase-title'];
    $title_keywords_any     = $_POST['search-keywords-any-title'];
    $title_keywords_none    = $_POST['search-keywords-none-title'];

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;
	$perpage = pref('search.perpage');

    $posts = adv_search($keywords_all, $required_phrase, $keywords_any, $keywords_none, $required_author, $required_tag,
                $title_keywords_all, $title_required_phrase, $title_keywords_any, $title_keywords_none, $page, $perpage);

    if(empty($posts) || $page < 1){
		render('404-search', array(
            'head_contents' => head_contents('Advanced search results | ' . blog_title(), 'Advanced search results on '. blog_title() . '.', site_url() . 'advanced-search/'),
            'bodyclass' => 'insearch',
            'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Advanced search results'
        ));
	} else {
        render('main',array(
            'head_contents' => head_contents('Advanced search results | ' . blog_title(), 'Advanced search results on '. blog_title() . '.', site_url() . 'advanced-search/'),
            'page' => $page,
            'posts' => $posts,
            'bodyclass' => 'insearch',
            'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Advanced search results',
            'pagination' => has_pagination(count($posts), $perpage, $page)
        ));
    }
});

// Meetings signup redirect
get('/meeting-signups', function () {
    $redirect = 'https://docs.google.com/spreadsheets/d/1nU3fzw1y-h5yjOm92yqQCC2lNx5Bs4p5m7L7agDCPHE/edit#gid=0';
    header("location: $redirect");
});

// Events/Meetings page
get('/meetings', function () {
    render('events',array(
		'head_contents' => head_contents('Events & Meetings | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'eventsview',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Events/Meetings',
        'view_mode'     => 'meetings'
	));
});
get('/events', function () {
    render('events',array(
		'head_contents' => head_contents('Events & Meetings | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'eventsview',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Events/Meetings',
        'view_mode'     => 'events'
	));
});
get('/calendar', function () {
    render('events',array(
		'head_contents' => head_contents('Calendar | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'eventsview',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Calendar',
        'view_mode'     => 'calendar'
	));
});

// Old events/meetings link, redirects to /meetings
get('/events-meetings', function () {
    $redirect = site_url() . 'meetings';
    header("location: $redirect");
});

get('/s', function () {
	$realID = from($_REQUEST, 'realID');

    if (isset($realID)) {
        $file_name = "config/signups/$realID.dat";

        $content = '';
        if (file_exists($file_name)) {
            $handle = fopen($file_name, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $line_data = explode(';', trim($line));
                    $user = $line_data[0];

                    if (!user_exists($user)) continue;

                    $user_realname = user('realname', $user);
                    $status = '';

                    if (intval($line_data[1]) == 0) {
                        $status = '<i style="margin-right:5px" class="fa fa-check"></i>Will attend';
                    } else if (intval($line_data[1]) == 1) {
                        $status = '<i style="margin-right:5px" class="fa fa-question"></i>Might attend';
                    } else if (intval($line_data[1]) == 2) {
                        $status = '<i style="margin-right:5px" class="fa fa-times"></i>Cannot attend';
                    }

                    $content .= <<<EOF
                    <div class="signup-attendent">
                        <span class="signup-attendent-name">$user_realname</span>
                        <span class="signup-attendent-status">$status</span>
                    </div>
EOF;
                }
            } else {
            }
            fclose($handle);
        } else {
            $content = 'No attendents.';
        }

        echo $content;
    } else {
        $redirect = site_url() . 'meetings';
        header("location: $redirect");
    }
});


get('/sfood', function () {
    $file_name = "config/signups/0food.dat";

    $content = '';
    if (file_exists($file_name)) {
        $handle = fopen($file_name, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line_data = explode(';', trim($line));
                $when = $line_data[0];
                $what = $line_data[1];

                $content .= <<<EOF
                <div class="signup-attendent">
                    <span class="signup-attendent-name">$when</span>
                    <span class="signup-attendent-status">$what</span>
                </div>
EOF;
            }
        } else {
        }
        fclose($handle);
    } else {
        $content = 'No data.';
    }

    echo $content;
});

post('/sfood', function () {
	$when = from($_REQUEST, 'when');
	$what = from($_REQUEST, 'what');

    $file_name = "config/signups/0food.dat";

    if (file_exists($file_name)) {
        $new_content = '';
        $found_self = false;

        $handle = fopen($file_name, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line_data = explode(';', trim($line));
                if (trim($line_data[0]) === $when) {
                    $found_self = true;
                    $new_content .= "$when;$what\n";
                } else {
                    $new_content .= $line;
                }
            }
        } else {
        }
        fclose($handle);

        if (!(strpos($new_content, $when) !== false)) {
            $handle = fopen($file_name, "a");
            fwrite($handle, "$when;$what\n");
            fclose($handle);
        } else {
            file_put_contents($file_name, $new_content);
        }
    }
});

post('/s', function () {
	$realID = from($_REQUEST, 'realID');
	$attend_status = intval(from($_REQUEST, 'attend_status'));

    if (login()) {
        $user = $_SESSION['user'];

        $file_name = "config/signups/$realID.dat";

        if (file_exists($file_name)) {
            $new_content = '';
            $found_self = false;

            $handle = fopen($file_name, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $line_data = explode(';', trim($line));
                    if (trim($line_data[0]) === $user) {
                        $found_self = true;
                        if ($attend_status != 3) {
                            $new_content .= "$user;$attend_status\n";
                        }
                    } else {
                        $new_content .= $line;
                    }
                }
            } else {
            }
            fclose($handle);

            if (!(strpos($new_content, $user) !== false) && $attend_status != 3) {
                $handle = fopen($file_name, "a");
                fwrite($handle, "$user;$attend_status\n");
                fclose($handle);
            } else {
                file_put_contents($file_name, $new_content);
            }
        } else {
            $handle = fopen($file_name, "w");
            fwrite($handle, "$user;$attend_status\n");
            fclose($handle);
            chmod($file_name, 0777);
        }
    }
});

// Meeting signups (s = signups)
get('/s/:alphaID', function ($alphaID) {
    if (strtolower($alphaID) === 'food') {
        render('signups-food',array(
            'head_contents' => head_contents('Food Signups | ' . blog_title(), blog_description(), site_url()),
            'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; <a href="'.site_url().'events-meetings">Events/Meetings</a> &#187; Food Signups',
            'bodyclass'     => 'eventsview signupspage foodsignupspage'
        ));
        return;
    }

    $realID = alphaID($alphaID, true);
    $type = 'meetings';
    $meeting_data = null;
    $event_item_content = null;

    // Get basic data
    $file_name = array_filter(glob("{config/events/$realID.ini,config/meetings/$realID.ini}", GLOB_BRACE), 'is_file');
    if (count($file_name) == 1) {
        $file_name = $file_name[0];
        $type = basename(dirname($file_name));
        $meeting_data = parse_ini_file($file_name);
    } else {
        $realID = null;
        $file_name = null;
    }

    if (!empty($meeting_data)) {
        $date = $meeting_data['date'];
        $start_time = $meeting_data['time.start'];
        $end_time = $meeting_data['time.end'];
        $desc = urldecode($meeting_data['name']);
        $details = urldecode($meeting_data['details']);
        if (empty($details)) { $details = '<div class="event-item-details" style="color:#898989l;margin-top:0">No details available</div>'; } else { $details = '<div class="event-item-details" style="margin-top:0"><b>Details:</b><br/>' . $details . '</div>'; };
        $has_passed = false;
        $datetime;

        // Get DateTime and check if passed
        try {
            if ($start_time == 'Not available') {
                if ($dt_now > $datetime = new DateTime($date)) {
                    $has_passed = true;
                }
            } else {
                if ($end_time == 'Not available') {
                    if ($dt_now > $datetime = new DateTime("$date $start_time")) {
                        $has_passed = true;
                    }
                } else {
                    if ($dt_now > $datetime = new DateTime("$date $end_time")) {
                        $has_passed = true;
                    }
                }
            }
        } catch (Exception $date_ex) {
            $has_passed = false;
        }
        //if (!isset($datetime)) continue;

        // Abbreviate 'Not available' to n/a
        $start_time_2 = $start_time;
        $end_time_2 = $end_time;

        if ($start_time_2 == 'Not available')
            $start_time_2 = 'n/a';
        if ($end_time_2 == 'Not available')
            $end_time_2 = 'n/a';

        // Time string 2 & 3
        $time_str_2 = $start_time_2;
        $time_str_3 = $start_time_2;
        if ($start_time_2 == 'n/a' && $end_time_2 == 'n/a') {
            $time_str_2 = 'n/a';
            $time_str_3 = 'n/a';
        } else if ($start_time_2 != 'n/a' && $end_time_2 == 'n/a') {
            // Do nothing
        } else {
            $time_str_2 .= '<br/>to ' . $end_time_2;
            $time_str_3 .= ' - ' . $end_time_2;
        }

        $datetime_month_short_name = null;
        $datetime_day_num = null;
        $datetime_day_short_name = null;
        if (isset($datetime)) {
            $datetime_month_short_name = $datetime->format('M');
            $datetime_day_num = $datetime->format('j');
            $datetime_day_short_name = $datetime->format('D');
        } else {
            $datetime_month_short_name = '&nbsp;';
            $datetime_day_num = $date;
            $datetime_day_short_name = '&nbsp;';
        }

        $event_item_content = <<<EOF
        <section class="event-item card-shadow" data-id="$alphaID">
            <div class="event-item-datetime">
                <div class="event-item-date">
                    <div class="event-item-date-month">$datetime_month_short_name</div>
                    <div class="event-item-date-day-num">$datetime_day_num</div>
                    <div class="event-item-date-day">$datetime_day_short_name</div>
                </div>
                <div class="event-item-time"><i class="fa fa-clock-o" style="margin-right: 5px;"></i>$time_str_2</div>
            </div>
            <div class="event-item-desc">
                <div class="event-item-desc-datetime"><span style="margin-right:10px">$date</span><i class="fa fa-clock-o" style="margin-right: 5px;"></i>$time_str_3</div>
                $details
            </div>
            <div class="clearfix"></div>
        </section>
EOF;
    }

    render('signups',array(
		'head_contents' => head_contents('Signups | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'eventsview signupspage',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; <a href="'.site_url().$type.'">'.ucfirst($type).'</a> &#187; Signups',
        'alphaID'       => $alphaID,
        'realID'        => $realID,
        'file_name'     => $file_name,
        'type'          => $type,
        'meeting_data'  => $meeting_data,
        'event_item_content' => $event_item_content,
        'has_passed'    => $has_passed
	));
});

// Get events data (deprecated, no need to use anymore)
get('/events-meetings/get', function () {
    $type = $_GET['type'];
    get_events($type);
});

// Get submitted login data
post('/login', function() {

	$user = from($_REQUEST, 'user');
	$pass = from($_REQUEST, 'password');
	$redirect = from($_REQUEST, 'continue');
    $remember_me = isset($_POST['remember-me']) && $_POST['remember-me']  ? "1" : "0";

    if (empty($redirect)) $redirect = site_url();

	if(!empty($user) && !empty($pass)) {
        if (strpos($user, '@') !== false) {
            $user = get_username_by_email($user); // email: find username
        }

        if (empty($user)) {
            pref('views.root', 'system/admin/views');

            render('login',array(
                'head_contents' => head_contents('Login | ' . blog_title(), 'Login page on ' .blog_title(), site_url()),
                'error' => 'The username/email or password is incorrect.',
                'username' => $user,
                'password' => $pass,
                'bodyclass' => 'inlogin'
            ));
        } else {
            //session($user, $pass, null);
            $log = session($user, $pass, $redirect, null, $remember_me);

            if(!empty($log)) {
                pref('views.root', 'system/admin/views');

                render('login',array(
                    'head_contents' => head_contents('Login | ' . blog_title(), 'Login page on ' .blog_title(), site_url()),
                    'error' => $log,
                    'bodyclass' => 'inlogin',
                    'continue' => $redirect,
                    'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Login'
                ));
            }
        }
	}
	else {
		pref('views.root', 'system/admin/views');
		render('login',array(
			'head_contents' => head_contents('Login | ' . blog_title(), 'Login page on ' .blog_title(), site_url()),
			'error' => 'One or more fields are not filled in.',
			'username' => $user,
			'password' => $pass,
			'bodyclass' => 'inlogin'
		));
	}

});

// Get submitted account registration data
post('/register-account', function() {
	$username = from($_REQUEST, 'user');
	$pass1 = from($_REQUEST, 'password1');
	$pass2 = from($_REQUEST, 'password2');
	$account_email = from($_REQUEST, 'email');
	$account_realname = from($_REQUEST, 'realname');
    $account_role = 'default';

    if(!empty($username) && !empty($pass1)) {
        if (preg_match('/[^0-9a-z\. _\-]/i', $username)) {
            echo '<div class="error-message">Invalid username. Usernames can only contain numbers, letters, underscores, periods, spaces and hypens.<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
        } else {
            if (!file_exists(user_data_file($username))) {
                if (!(strpos($account_email, '@') !== false)) {
                    echo '<div class="error-message">Invalid email address<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                } else {
                    $email_hash_files = user_data_file_glob('email-' . md5($account_email) . '.dat');
                    if (!empty($email_hash_files[0])) {
                        echo '<div class="error-message">There already is an account registered with this email.<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                    } else {
                        if ($pass1 === $pass2) {
                            create_account($username, $pass1, $account_role, $account_email, $account_realname);

                            echo '<div class="success-message">Succesfully created account "'.$username.'". Please check your email for an account verification email<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                        } else {
                            echo '<div class="error-message">Passwords do not match<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                        }
                    }
                }
            } else {
                echo '<div class="error-message">The account "'.$username.'" already exists<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
            }
        }
    } else {
        echo '<div class="error-message">One or more fields were not filled in<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
    }
});

// Verify registration
get('/verify-registration', function() {
	$user = from($_REQUEST, 'username');
	$key = from($_REQUEST, 'confirm');

	pref('views.root', 'system/admin/views');
    if (!file_exists(user_data_file($user))) {
        render('verify-registration',array(
			'head_contents' => head_contents('Account Verification | ' . blog_title(), blog_description(), site_url()),
			'bodyclass' => 'inlogin verifypage',
            'verify_message' => '<div class="notify-message">Account \''.$user.'\' does not exist</div>',
            'verify_user'   => $user
		));
        die();
    }

    $verify_thanks = '<div style="margin-top:15px;font-size: 17px;line-height: 22px;border-top: 1px solid #e3e3e3;padding-top: 20px;">Thank you for registering,</div><div style="margin-top:6px;font-size:17px;line-height:22px;">please login <a href="http://www.spartabots.org/login">here</a></div>';
    if(!empty($user) && !empty($key))  {
        $verify_data = parse_ini_file(user_data_file($user, 'verification'));
        if (!($verify_data['verified'] === 'true')) {
            if ($verify_data['key'] === $key) {
                $verify_file = fopen(user_data_file($user, 'verification'), "w");
                fwrite($verify_file, "verified = \"true\"\n");
                fclose($verify_file);

                render('verify-registration',array(
                    'head_contents' => head_contents('Account Verification | ' . blog_title(), blog_description(), site_url()),
                    'bodyclass' => 'inlogin verifypage',
                    'verify_message' => '<div class="success-message" style="box-shadow:none">Your account has been verified</div>' . $verify_thanks,
                    'verify_user'   => $user
                ));
            } else {
                render('verify-registration',array(
                    'head_contents' => head_contents('Account Verification | ' . blog_title(), blog_description(), site_url()),
                    'bodyclass' => 'inlogin verifypage',
                    'verify_message' => '<div class="error-message" style="box-shadow:none">Account verification failed: incorrect key.</div>',
                    'verify_user'   => $user
                ));
            }
        } else {
            render('verify-registration',array(
                'head_contents' => head_contents('Account Verification | ' . blog_title(), blog_description(), site_url()),
                'bodyclass' => 'inlogin verifypage',
                'verify_message' => '<div class="error-message" style="box-shadow:none">Account already verified</div>' . $verify_thanks,
                'verify_user'   => $user
            ));
        }
    } else if (!empty($user)) {
        $verify_data = parse_ini_file(user_data_file($user, 'verification'));
        $account_verified = ($verify_data['verified'] === 'true');

		render('verify-registration',array(
			'head_contents' => head_contents('Account Verification | ' . blog_title(), blog_description(), site_url()),
			'bodyclass' => 'inlogin verifypage',
            'verify_message' => '<div class="notify-message">Account \''.$user.'\' is '. ($account_verified ? '' : 'not ') .'verified</div>',
            'verify_user'   => $user
		));
    } else {
        render('verify-registration',array(
			'head_contents' => head_contents('Account Verification | ' . blog_title(), blog_description(), site_url()),
			'bodyclass' => 'inlogin verifypage',
            'verify_message' => '<div class="error-message" style="box-shadow:none">Account verification failed: Username and confirm key not recieved</div>',
            'verify_user'   => $user
		));
    }
});

// Username forgotten
get('/forgot-username', function() {
	pref('views.root', 'system/admin/views');
    render('forgot-username',array(
        'head_contents' => head_contents('Forgot username | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage'
    ));
});

post('/forgot-username', function() {
	$email = from($_REQUEST, 'email');
    $username = get_username_by_email($email);

    if (!empty($username)) {
        $message  = '<div style="font-size:17px;line-height:22px">';
        $message .= 'Hello!<br/></br><p>You have requested your username information from '.site_url().'forgot-username<br/>';
        $message .= 'If you did not request this, you can safely ignore this email.</p>';

        $message .= '<table rules="all" style="border-color:#ccc; width:80%" cellpadding="10">';
        $message .= "<tr><td style='background:#eee;' colspan='2'><strong>Your username:</strong></td></tr>";
        $message .= "<tr><td colspan='2'>".$username."</td></tr>";
        $message .= "</table>";
        $message .= "</div>";

        send_no_reply_email($email, 'Username information for Spartabots.org', $message);
    }

	pref('views.root', 'system/admin/views');
    render('forgot-username',array(
        'head_contents' => head_contents('Forgot username | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage',
        'result_message' => '<div class="notify-message">If there is a Spartabots.org account registered to "'.$email.'" we have sent your username information. Thanks!</div>'
    ));
});

// Password forgotten
get('/forgot-password', function() {
	pref('views.root', 'system/admin/views');
    render('forgot-password',array(
        'head_contents' => head_contents('Forgot password | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage'
    ));
});

post('/forgot-password', function() {
	$email = from($_REQUEST, 'email');

    if (!empty($email) && strpos($email, '@') !== false) { // Simple email validation
        $user = get_username_by_email($email);
        if (!empty($user)) { // Check if username associated with email
            $user_file = user_data_file($user);
            if (file_exists($user_file)) { // check if user data file actually exists
                $salt = user('salt', $user);
                $token = pbkdf2('SHA256', time(), $salt, 1024, 128);
                $expire_time = strtotime('+72 hours');

                if (file_exists(user_data_file($user, 'pass-reset-token'))) {
                    unlink(user_data_file($user, 'pass-reset-token'));
                }

                $token_file = fopen(user_data_file($user, 'pass-reset-token'), "w");

                fwrite($token_file, "token = \"$token\"\n");
                fwrite($token_file, "expire_time = \"$expire_time\"\n");
                fclose($token_file);

                chmod(user_data_file($user, 'pass-reset-token'), 0777);

                $link = site_url() . 'password-reset?email=' . urlencode($email) . '&confirm=' . $token;

                $message  = '<div style="font-size:17px;line-height:22px">';
                $message .= 'Hello!<br/></br><p>You have requested information on how to reset your password from '.site_url().'forgot-password<br/>';
                $message .= 'If you did not request this, you can safely ignore this email.</p>';

                $message .= "<hr style=\"border:0;border-top:1px solid #e3e3e3;height:0px;margin-top:20px;margin-bottom:20px;\" />";
                $message .= '<a href="'.$link.'"  target="_blank" style="display:block;text-align:center"><div style="display:inline-block;background-color:#2BAD4A;color:#fff;text-decoration:none;font-weight:700;font-size:18px;padding-top:8px;padding-left:16px;padding-bottom:8px;padding-right:16px;border-radius:3px">Reset password</div></a>';

                $message .= "<hr style=\"border:0;border-top:1px solid #e3e3e3;height:0px;margin-top:20px;margin-bottom:20px;\" />";

                $message .= "<p>";
                $message .= "Or, paste this link into your browser:<br/>";
                $message .= "<a href=\"$link\" style=\"width:620px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis\">$link</a>";
                $message .= "</p>";

                $message .= "<p>The password reset link will expire in 72 hours.</p>";

                $message .= "</div>";

                send_no_reply_email($email, 'Password reset information for Spartabots.org', $message);
            }
        }
    }

	pref('views.root', 'system/admin/views');
    render('forgot-password',array(
        'head_contents' => head_contents('Forgot password | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage',
        'result_message' => '<div class="notify-message">If there is a Spartabots.org account registered to "'.$email.'" we have sent instructions on how to reset your password. Thanks!</div>'
    ));
});

// Password reset
get('/password-reset', function() {
	pref('views.root', 'system/admin/views');

	$email = urldecode(from($_REQUEST, 'email'));
	$token = from($_REQUEST, 'confirm');
    $user = get_username_by_email($email);

    $response = '';
    $activate = false;

    if(!empty($user) && !empty($token) && file_exists(user_data_file($user, 'pass-reset-token')))  {
        $verify_data = parse_ini_file(user_data_file($user, 'pass-reset-token'));
        if (time() < intval($verify_data['expire_time'])) {
            if ($verify_data['token'] === $token) {
                $response = null;
                $activate = true;
            } else {
                $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Access Denied. The form has been locked.</div>';
            }
        } else {
            $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Expired link. The form has been locked.</div>';
            unlink(user_data_file($user, 'pass-reset-token'));
        }
    } else {
        $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Access Denied. The form has been locked.</div>';
    }

    render('password-reset',array(
        'head_contents' => head_contents('Password reset | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage',
        'response' => $response,
        'email' => ($activate ? urlencode($email) : ''),
        'token' => ($activate ? $token : ''),
        'activate' => $activate
    ));
});

post('/password-reset', function() {
	pref('views.root', 'system/admin/views');

	$email = urldecode(from($_REQUEST, 'email'));
	$token = from($_REQUEST, 'token');
	$pass1 = from($_REQUEST, 'password1');
	$pass2 = from($_REQUEST, 'password2');
    $user = get_username_by_email($email);

    $response = '';
    $activate = false;

    if(!empty($user) && !empty($token) && file_exists(user_data_file($user, 'pass-reset-token')) && file_exists(user_data_file($user)))  {
        $verify_data = parse_ini_file(user_data_file($user, 'pass-reset-token'));
        if (time() < intval($verify_data['expire_time'])) {
            if ($verify_data['token'] === $token) {
                if ($pass1 === $pass2) {
                    $response = '<div class="success-message" style="margin-bottom:0;box-shadow:none">The password for \''.$user.'\' has been changed. The form has been locked.</div>';
                    $activate = false;
                    unlink(user_data_file($user, 'pass-reset-token'));

                    create_account($user, $pass1, user('role', $user), user('email', $user), user('realname', $user), false);
                } else {
                    $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Passwords do not match.</div>';
                    $activate = true;
                }
            } else {
                $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Access Denied. The form has been locked.</div>';
            }
        } else {
            $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Expired link. The form has been locked.</div>';
            unlink(user_data_file($user, 'pass-reset-token'));
        }
    } else {
        $response = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Access Denied. The form has been locked.</div>';
    }

    render('password-reset',array(
        'head_contents' => head_contents('Password reset | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage',
        'response' => $response,
        'email' => ($activate ? urlencode($email) : ''),
        'token' => ($activate ? $token : ''),
        'activate' => $activate
    ));
});

// Resend verification
get('/resend-verification', function() {
	pref('views.root', 'system/admin/views');
    render('resend-verification',array(
        'head_contents' => head_contents('Resend Verification | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage'
    ));
});

post('/resend-verification', function() {
	$user = get_username_by_email(from($_REQUEST, 'email'));
    $user_file = user_data_file($user);
    $message = '';

    if (!empty($user) && file_exists($user_file)) {
        if (file_exists(user_data_file($user, 'verification'))) {
            $verify_data = parse_ini_file(user_data_file($user, 'verification'));
            if (!($verify_data['verified'] === 'true')) {
                send_verification($user, $verify_data['key'], true);
                $message = '<div class="success-message" style="margin-bottom:0;box-shadow:none">Verification resent</div>';
            } else {
                $message = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Account already verified</div>';
            }
        } else {
            $message = '<div class="error-message" style="margin-bottom:0;box-shadow:none">Verification data does not exist</div>';
        }
    } else {
        $message = '<div class="error-message" style="margin-bottom:0;box-shadow:none">No account associated with that email.</div>';
    }

	pref('views.root', 'system/admin/views');
    render('resend-verification',array(
        'head_contents' => head_contents('Resend Verification | ' . blog_title(), blog_description(), site_url()),
        'bodyclass' => 'inlogin verifypage',
        'verify_message'       => $message
    ));
});


// The blog post page
get('/:year/:month/:name', function($year, $month, $name){

	$post = find_post($year, $month, $name);

	$current = $post['current'];

	if(!$current){
		not_found();
	}

	$bio = get_bio($current->author);

	if(isset($bio[0])) {
		$bio = $bio[0];
	}
	else {
		$bio = default_profile($current->author);
	}

	if (array_key_exists('prev', $post)) {
		$prev = $post['prev'];
	}
	else {
		$prev = array();
	}

	if (array_key_exists('next', $post)) {
		$next= $post['next'];
	}
	else {
		$next = array();
	}

	render('post',array(
		'head_contents' => head_contents($current->title .' | ' . blog_title(), $description = get_description($current->body), $current->url),
		'p' => $current,
		'authorinfo' => authorinfo($bio->title, $bio->body),
		'bodyclass' => 'inpost',
		'breadcrumb' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="' . site_url() .  '">' .config('breadcrumb.home'). '</a></span> &#187; '. $current->tagb . ' &#187; ' . $current->title,
		'prev' => has_prev($prev),
		'next' => has_next($next),
		'type' => 'blogpost',
	));
});

// Edit blog post
get('/:year/:month/:name/edit', function($year, $month, $name){

	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {

		pref('views.root', 'system/admin/views');
		$post = find_post($year, $month, $name);

		if(!$post){
			not_found();
		}

		$current = $post['current'];

		if($user === $current->author || $role === 'admin') {
			render('edit-post',array(
				'head_contents' => head_contents('Edit post | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'Edit Post',
				'p' => $current,
				'bodyclass' => 'editpost',
				'breadcrumb' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="' . site_url() .  '">' .config('breadcrumb.home'). '</a></span> &#187; '. $current->tagb . ' &#187; ' . $current->title
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Edit post | ' . blog_title(), blog_description(), site_url()),
				'p' => $current,
				'bodyclass' => 'denied',
				'breadcrumb' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="' . site_url() .  '">' .config('breadcrumb.home'). '</a></span> &#187; '. $current->tagb . ' &#187; ' . $current->title
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Get edited data for blog post
post('/:year/:month/:name/edit', function() {

	$title = from($_REQUEST, 'title');
	$tag = from($_REQUEST, 'tag');
	$url = from($_REQUEST, 'url');
	$content = from($_REQUEST, 'content');
	$oldfile = from($_REQUEST, 'oldfile');
	$destination = from($_GET, 'destination');
	if(!empty($title) && !empty($tag) && !empty($content)) {
		if(!empty($url)) {
			edit_post($title, $tag, $url, $content, $oldfile, $destination);
		}
		else {
			$url = $title;
			edit_post($title, $tag, $url, $content, $oldfile, $destination);
		}
	}
	else {
		$message['error'] = '';
		if(empty($title)) {
			$message['error'] .= '<li>Title field is required.</li>';
		}
		if (empty($tag)) {
			$message['error'] .= '<li>Tag field is required.</li>';
		}
		if (empty($content)) {
			$message['error'] .= '<li>Content field is required.</li>';
		}
		pref('views.root', 'system/admin/views');

		render('edit-post',array(
			'head_contents' => head_contents('Edit post | ' . blog_title(), blog_description(), site_url()),
            'heading' => 'Edit Post',
			'error' => '<ul>' . $message['error'] . '</ul>',
			'oldfile' => $oldfile,
			'postTitle' => $title,
			'postTag' => $tag,
			'postUrl' => $url,
			'postContent' => $content,
			'bodyclass' => 'editpost',
			'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Edit post'
		));
	}

});

// Delete blog post
get('/:year/:month/:name/delete', function($year, $month, $name){

	$user = $_SESSION['user'];

	$role = user('role', $user);

	if(login()) {

		pref('views.root', 'system/admin/views');
		$post = find_post($year, $month, $name);

		if(!$post){
			not_found();
		}

		$current = $post['current'];

		if($user === $current->author || $role === 'admin') {
			render('delete-post',array(
				'head_contents' => head_contents('Delete post | ' . blog_title(), blog_description(), site_url()),
				'p' => $current,
				'bodyclass' => 'deletepost',
				'breadcrumb' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="' . site_url() .  '">' .config('breadcrumb.home'). '</a></span> &#187; '. $current->tagb . ' &#187; ' . $current->title
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Delete post | ' . blog_title(), blog_description(), site_url()),
				'p' => $current,
				'bodyclass' => 'deletepost',
				'breadcrumb' => '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="' . site_url() .  '">' .config('breadcrumb.home'). '</a></span> &#187; '. $current->tagb . ' &#187; ' . $current->title
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Gallery Upload
get('/media/upload', function(){
	render('media-gallery-upload',array(
		'head_contents' => head_contents('Upload to Gallery | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'ingallery',
		'breadcrumb'    => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; <a href="' . site_url() .  'media">Media</a> &#187; <a href="' . site_url() .  'media/gallery">Gallery</a> &#187; Upload'
	));
});

// Media
get('/media', function () {
    $redirect = site_url() . 'media/gallery';
    header("location: $redirect");
});

// Gallery
get('/media/gallery', function(){
	render('media-gallery',array(
		'head_contents' => head_contents('Gallery | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'ingallery',
		'breadcrumb'    => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; <a href="' . site_url() .  'media">Media</a> &#187; Gallery'
	));
});

// Videos
get('/media/videos', function () {
    render('media-videos',array(
		'head_contents' => head_contents('Videos | ' . blog_title(), blog_description(), site_url()),
		'bodyclass'     => 'videospage',
		'breadcrumb'    => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; <a href="' . site_url() .  'media">Media</a> &#187; Videos'
	));
});

// Get deleted data for blog post
post('/:year/:month/:name/delete', function() {

	$file = from($_REQUEST, 'file');
	$destination = from($_GET, 'destination');
	delete_post($file, $destination);

});

// Edit the profile
get('/edit/profile', function(){

	if(login()) {

		pref('views.root', 'system/admin/views');
		render('edit-profile',array(
			'head_contents' => head_contents('Edit profile | ' . blog_title(), blog_description(), site_url()),
            'heading' => 'Edit Profile',
			'bodyclass' => 'editprofile',
			'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Edit profile',
		));
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Get edited data for static page
post('/edit/profile', function() {

	$user = $_SESSION['user'];
	$title = from($_REQUEST, 'title');
	$content = from($_REQUEST, 'content');
	if(!empty($title) && !empty($content)) {
		edit_profile($title, $content, $user);

		$redirect = site_url() . 'admin/my-account';
		header("location: $redirect");
	}
	else {
		$message['error'] = '';
		if(empty($title)) {
			$message['error'] .= '<li>Title field is required.</li>';
		}
		if (empty($content)) {
			$message['error'] .= '<li>Content field is required.</li>';
		}
		pref('views.root', 'system/admin/views');

		render('edit-profile',array(
			'head_contents' => head_contents('Edit profile | ' . blog_title(), blog_description(), site_url()),
            'heading' => 'Edit Profile',
			'error' => '<ul>' . $message['error'] . '</ul>',
			'postTitle' => $title,
			'postContent' => $content,
			'bodyclass' => 'editprofile',
			'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Edit profile'
		));
	}

});

get('/admin/posts', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {

		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
			$page = from($_GET, 'page');
			$page = $page ? (int)$page : 1;
			$perpage = 20;

			$posts = get_posts(null, $page, $perpage);

			$total = '';

			if(empty($posts) || $page < 1){

				// a non-existing page
				render('no-posts',array(
					'head_contents' => head_contents('All blog posts | ' . blog_title(), blog_description(), site_url()),
					'bodyclass' => 'noposts',
				));

				die;
			}

			$tl = blog_tagline();

			if($tl){ $tagline = ' | ' . $tl;} else {$tagline = '';}

			render('posts-list',array(
				'head_contents' => head_contents('All blog posts | ' . blog_title(), blog_description(), site_url()),
				'heading' => 'All blog posts',
				'page' => $page,
				'posts' => $posts,
				'bodyclass' => 'all-posts',
				'breadcrumb' => '',
				'pagination' => has_pagination($total, $perpage, $page)
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('All blog posts | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// The author page
get('/admin/mine', function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
        if ($role === 'admin' || $role === 'editor') {
            pref('views.root', 'system/admin/views');
            $profile = $_SESSION['user'];

            $page = from($_GET, 'page');
            $page = $page ? (int)$page : 1;
            $perpage = pref('profile.perpage');

            $posts = get_profile($profile, $page, $perpage);

            $total = get_count($profile, 'dirname');

            $bio = get_bio($profile);

            if(isset($bio[0])) {
                $bio = $bio[0];
            }
            else {
                $bio = default_profile($profile);
            }

            if(empty($posts) || $page < 1){
                render('user-posts',array(
                    'head_contents' => head_contents('My blog posts | ' . blog_title(), blog_description(), site_url()),
                    'page' => $page,
                    'heading' => 'My Posts',
                    'posts' => null,
                    'bio' => $bio->body,
                    'name' => $bio->title,
                    'bodyclass' => 'userposts',
                    'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Viewing profile: ' . $bio->title,
                    'pagination' => has_pagination($total, $perpage, $page)
                ));
                die;
            }

            render('user-posts',array(
                'head_contents' => head_contents('My blog posts | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'My Posts',
                'page' => $page,
                'posts' => $posts,
                'bio' => $bio->body,
                'name' => $bio->title,
                'bodyclass' => 'userposts',
                'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Viewing profile: ' . $bio->title,
                'pagination' => has_pagination($total, $perpage, $page)
            ));
        } else {
			not_found();
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// The navigation edit page
get('/admin/edit-nav', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {

		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
			render('edit-nav',array(
				'head_contents' => head_contents('Edit Navigation | ' . blog_title(), blog_description(), site_url()),
				'heading' => 'Edit navigation',
				'bodyclass' => 'editnav',
				'breadcrumb' => ''
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Edit Navigation | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => ''
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// The settings page

get('/admin/settings', function () {
	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {

		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
			render('edit-settings',array(
				'head_contents' => head_contents('Settings | ' . blog_title(), blog_description(), site_url()),
				'heading' => 'Website settings',
				'bodyclass' => 'sitesettings',
				'breadcrumb' => ''
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Settings | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

post('/admin/settings', function() {

	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {

        $content = from($_REQUEST, 'content');
        if(!empty($content)) {
            change_config($content);
            echo '<div class="success-message">Settings changed<i class="fa fa-times" onclick="$(\'#settings-form-status\').html(\'\')"></i></div>';
        } else {
            echo '<div class="error-message">Content required<i class="fa fa-times" onclick="$(\'#settings-form-status\').html(\'\')"></i></div>';
        }
    } else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Settings | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});


// The config edit page
get('/admin/edit-config', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {

		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
			render('edit-config',array(
				'head_contents' => head_contents('Edit Config | ' . blog_title(), blog_description(), site_url()),
				'heading' => 'Edit config',
				'bodyclass' => 'editconfig',
				'breadcrumb' => ''
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Edit Config | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

post('/admin/edit-config', function() {

	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {

        $content = from($_REQUEST, 'content');
        if(!empty($content)) {
            edit_config($content);
        }
        else {
            $message['error'] = '';
            if (empty($content)) {
                $message['error'] .= '<li>Content field is required.</li>';
            }
            pref('views.root', 'system/admin/views');

            render('edit-config',array(
                'head_contents' => head_contents('Edit Config | ' . blog_title(), blog_description(), site_url()),
                'error' => '<ul>' . $message['error'] . '</ul>',
                'bodyclass' => 'editconfig',
                'breadcrumb' => ''
            ));
        }
    }
    else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Edit Config | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

// The acounts page
get('/admin/accounts', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {
		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
			render('accounts',array(
				'head_contents' => head_contents('Accounts | ' . blog_title(), blog_description(), site_url()),
				'heading' => 'Accounts',
				'bodyclass' => 'accounts',
				'breadcrumb' => ''
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Accounts | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => ''
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// The acounts page
get('/admin/my-account', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
        if ($role === 'admin' || $role === 'editor') {
            pref('views.root', 'system/admin/views');

            render('my-account',array(
                'head_contents' => head_contents('My Account | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'My account',
                'bodyclass' => 'myaccount',
                'breadcrumb' => ''
            ));
        } else {
            not_found();
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});


post('/admin/accounts/get-login-history', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {
        $username = from($_REQUEST, 'username');

        $data_file = user_data_dat_file($username, 'login_history');
        $login_history = <<<EOF
                <div class="login-history">
                    <div class="login-history-title">
                        <span>Login History for $username</span>
                        <i class="fa fa-times" style="margin:5px;float:right;cursor:pointer;" onclick="$('#login-history-popup').fadeOut();return false"></i>
                    </div>
                    <div class="login-history-content">
EOF;

        if (file_exists($data_file)) {
            $handle = fopen($data_file, "r");
            if ($handle) {
                $history_array = array();
                $line_num = 0;
                while (($line = fgets($handle)) !== false) {
                    $line_num++;
                    $line_data = explode('@', $line);
                    $history_action = $line_data[0];
                    $history_time = $line_data[1];
                    $history_time_data = explode(' ', $history_time);
                    $history_time_date = $history_time_data[0] . ' ' . $history_time_data[1] . ' ' . $history_time_data[2];
                    $history_time_time = $history_time_data[3] . ' ' . $history_time_data[4];


                    $login_history_item = <<<EOF
                    <div class="login-history-item" data-line-number="$line_num">
                        <div class="login-history-action" data-history-action="$history_action">$history_action</div>
                        <div class="login-history-date"><span>$history_time_date</span><span>$history_time_time</span></div>
                        <div class="clearfix"></div>
                    </div>
EOF;
                    array_unshift($history_array, $login_history_item);
                }
                foreach($history_array as $history_item) {
                    $login_history .= $history_item;
                }
            } else {
                $login_history .= '<b>Could not obtain login history:</b><br/>An error while reading this user\'s login history data file.';
            }
            fclose($handle);

        } else {
            $login_history .= '<b>Login history not available:</b><br/>Possible reasons for this include:<ul><li>this user has never logged in before</li><li>this user does not exist</li><li>an admistrator has deleted the user\'s login history data file</li></ul>';
        }

        $login_history .= '</div>';
        if (file_exists($data_file)) {
            $login_history .= '<div style="padding:20px 10px;border-top:1px solid #F2F2F2;"><a onclick="if (confirm(\'Are you sure you want to delete '.$user.'\\\'s login history?\')) deleteLoginHistory(\''.$user.'\'); return false" href="#" style="">Delete login history</a></div>';
        } else {
            $login_history .= '<div style="padding:20px 10px;border-top:1px solid #F2F2F2;"><a onclick="return false" href="#" title="No login history to delete." style="color: rgb(180,180,180);cursor:not-allowed">Delete login history</a></div>';
        }
        $login_history .= '</div>';


        echo $login_history;
    } else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Accounts | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});


post('/admin/accounts/delete-login-history', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {
        $username = from($_REQUEST, 'username');

        $data_file = user_data_dat_file($username, 'login_history');

        if (file_exists($data_file)) {
            unlink($data_file);
            echo '<div style="padding:10px;">Login history deleted.</div>';
        } else {
            echo '<div style="padding:10px;">Login history could not be deleted: this user has no available login history.</div>';
        }
    } else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Accounts | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

get('/admin/accounts/create-account', function() {
	header("location: " . site_url() . "admin/accounts");
});
post('/admin/accounts/create-account', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {

        $username = from($_REQUEST, 'username');
        $pass1 = from($_REQUEST, 'password1');
        $pass2 = from($_REQUEST, 'password2');
        $account_role = from($_REQUEST, 'account_type');
        $account_email = from($_REQUEST, 'account_email');
        $account_realname = from($_REQUEST, 'account_realname');
        $account_verify = isset($_POST['account_verify']) && $_POST['account_verify']  ? "1" : "0";

        if (empty($account_realname)) {
            $account_realname = 'Not named';
        }

        if(!empty($username) && !empty($pass1) && !empty($pass2) && !empty($account_role)) {
            if (!file_exists(user_data_file($username))) {
                if ($pass1 === $pass2) {
                    create_account($username, $pass1, $account_role, $account_email, $account_realname, $account_verify);

                    echo '<div class="success-message">Succesfully created account "'.$username.'" of type '.$account_role.'<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                } else {
                    echo '<div class="error-message">Passwords do not match<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
                }
            } else {
                echo '<div class="error-message">The account "'.$username.'" already exists<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
            }
        } else {
            echo '<div class="error-message">One or more fields were not filled in<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
        }
    } else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Accounts | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

get('/admin/accounts/change-password', function() {
	header("location: " . site_url() . "admin/accounts");
});
post('/admin/accounts/change-password', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {
        $pass1 = from($_REQUEST, 'password1');
        $pass2 = from($_REQUEST, 'password2');

        if(!empty($pass1) && !empty($pass2)) {
            if ($pass1 === $pass2) {
                create_account($user, $pass1, $role, user('email', $user), user('realname', $user));

                echo '<div class="success-message">Succesfully changed password<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
            } else {
                echo '<div class="error-message">Passwords do not match<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
            }
        } else {
            echo '<div class="error-message">Password field(s) not filled in<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
        }
    } else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Accounts | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

post('/admin/edit-nav', function() {

	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {

        $content = from($_REQUEST, 'content');
        if(!empty($content)) {
            edit_nav($content);
            echo '<div class="success-message">Menu changed<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
        }
        else {
            echo '<div class="error-message">Content required<i class="fa fa-times" onclick="$(\'#form-status\').html(\'\')"></i></div>';
        }
    }
    else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Edit Navigation | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});


// The events/meetings page
get('/admin/events', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {

		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
			render('events',array(
				'head_contents' => head_contents('Edit Events | ' . blog_title(), blog_description(), site_url()),
				'heading' => 'Edit events & meetings',
				'bodyclass' => 'editevents',
				'breadcrumb' => ''
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('Edit Events | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => ''
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});
get('/events/delete', function () {
    echo 'Access denied';
});
post('/events/delete', function () {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {
        if (isset($_POST['type'])) {
            $files = glob('config/'. $_POST['type'] .'/*.ini');
            foreach($files as $file){
                if(is_file($file))
                    unlink($file);
            }
            echo 'All ' . $_POST['type'] . ' have been deleted.';
        } else {
            if (file_exists($_POST['file_name'])) {
                unlink($_POST['file_name']);
                echo 'The event or meeting, ' . $_POST['file_name'] . ', has been deleted.';
            } else {
                echo 'The event or meeting, ' . $_POST['file_name'] . ', was not found and therefore was not deleted.';
            }
        }
    }
});
get('/events/get_admin', function () {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    if(login() && $role === 'admin') {
        $type = $_GET['type'];
        if (isset($type)) {
            $files = array_filter(glob('config/'. $type .'/*.ini'), 'is_file');
            $files_count = count($files);

            $future_arr = array();
            $past_arr = array();

            $dt_now = new DateTime();
            for ($i = 0; $i < $files_count; $i++) {
                $meeting_data = parse_ini_file($files[$i]);
                $file_name = $files[$i];
                $date = $meeting_data['date'];
                $start_time = $meeting_data['time.start'];
                $end_time = $meeting_data['time.end'];
                $desc = urldecode($meeting_data['name']);
                $details = urldecode($meeting_data['details']);
                if (empty($details)) $details = '';
                $has_passed = false;
                $datetime = null;

                // Get DateTime and check if passed
                try {
                    if ($start_time == 'Not available') {
                        if ($dt_now > $datetime = new DateTime($date)) {
                            $has_passed = true;
                        }
                    } else {
                        if ($end_time == 'Not available') {
                            if ($dt_now > $datetime = new DateTime("$date $start_time")) {
                                $has_passed = true;
                            }
                        } else {
                            if ($dt_now > $datetime = new DateTime("$date $end_time")) {
                                $has_passed = true;
                            }
                        }
                    }
                } catch (Exception $date_ex) {
                    $has_passed = false;
                }

                //if (!isset($datetime)) continue;

                if ($has_passed) {
                    array_push($past_arr, array(
                        'datetime' => $datetime,
                        'file_name' => $file_name,
                        'type' => $type,
                        'date' => $date,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'desc' => $desc,
                        'details' => $details
                    ));
                } else {
                    array_push($future_arr, array(
                        'datetime' => $datetime,
                        'file_name' => $file_name,
                        'type' => $type,
                        'date' => $date,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'desc' => $desc,
                        'details' => $details
                    ));
                }
            }

            //uasort($past_arr,function($a,$b){return $a['datetime']<$b['datetime']?-1:1;});
            //uasort($future_arr,function($a,$b){return $a['datetime']<$b['datetime']?-1:1;});

            uasort($past_arr,function($a,$b){
                if (!isset($a['datetime']))
                    return -1;
                if (!isset($b['datetime']))
                    return 1;
                return $a['datetime']<$b['datetime']?1:-1;
            });
            uasort($future_arr,function($a,$b){
                if (!isset($a['datetime']))
                    return -1;
                if (!isset($b['datetime']))
                    return 1;
                return $a['datetime']<$b['datetime']?1:-1;
            });

            foreach ($future_arr as $meeting_data) {
                $file_name = $meeting_data['file_name'];
                $date = $meeting_data['date'];
                $start_time = $meeting_data['start_time'];
                $end_time = $meeting_data['end_time'];
                $desc = $meeting_data['desc'];
                $details = $meeting_data['details'];

                echo <<<EOF
    <form action="POST" data-file-name="$file_name" onsubmit="edit_action(this);return false" class="events-view-mode events-row">
        <input type="hidden" name="type" value="$type" />
        <section class="events-date-col">
            <input class="event-edit-input" type="text" value="$date" name="date" disabled>
        </section>
        <section class="events-time-col">
            <input class="event-edit-input" type="text" value="$start_time" name="time_start" disabled>
        </section>
        <section class="events-time-col">
            <input class="event-edit-input" type="text" value="$end_time" name="time_end" disabled>
        </section>
        <section class="events-desc-col">
            <input class="event-edit-input" type="text" value="$desc" name="description" disabled>
        </section>
        <section class="events-tools-col">
            <a onclick="edit_enter($(this).closest('form'))" class="events-edit-btn events-view-mode">Edit</a>
            <span class="events-view-mode">&nbsp;|&nbsp;</span>
            <a onclick="edit_delete($(this).closest('form'))" class="events-delete-btn events-view-mode">Delete</a>

            <a onclick="edit_cancel($(this).closest('form'))" class="events-cancel-edit-btn events-edit-mode">Cancel</a>
            <input type="submit" class="events-save-btn events-edit-mode" value="Save" />
        </section>
        <div class="clearfix"></div>
        <div class="events-details-col events-edit-mode">
            <textarea class="event-edit-input" placeholder="Details (ex. links to meeting notes). You may use HTML." name="details" disabled>$details</textarea>
        </div>
    </form>
EOF;
            }

            echo '<div style="padding:6px 10px;border-bottom:1px solid #E3E3E4;background:#fcfcfc"><span style="min-width:120px;display:inline-block;">Past '.$type.'</span><a style="cursor:pointer;background:#f4f4f4;padding:6px 8px;color:rgb(51,51,51);width:54px;display:inline-block;box-sizing:border-box;-moz-box-sizing:border-box;"onclick="if ($(\'#events-area-past-'.$type.'\').is(\':visible\')){$(\'#events-area-past-'.$type.'\').hide();$(this).html(\'Show\')}else{$(\'#events-area-past-'.$type.'\').show();$(this).html(\'Hide\')}">Show</a></div>';
            echo '<div id="events-area-past-'.$type.'" style="display:none">';
            foreach ($past_arr as $meeting_data) {
                $file_name = $meeting_data['file_name'];
                $date = $meeting_data['date'];
                $start_time = $meeting_data['start_time'];
                $end_time = $meeting_data['end_time'];
                $desc = $meeting_data['desc'];
                $details = $meeting_data['details'];

                echo <<<EOF
    <form action="POST" data-file-name="$file_name" onsubmit="edit_action(this);return false" class="events-view-mode events-row">
        <input type="hidden" name="type" value="$type" />
        <section class="events-date-col">
            <input class="event-edit-input" type="text" value="$date" name="date" disabled>
        </section>
        <section class="events-time-col">
            <input class="event-edit-input" type="text" value="$start_time" name="time_start" disabled>
        </section>
        <section class="events-time-col">
            <input class="event-edit-input" type="text" value="$end_time" name="time_end" disabled>
        </section>
        <section class="events-desc-col">
            <input class="event-edit-input" type="text" value="$desc" name="description" disabled>
        </section>
        <section class="events-tools-col">
            <a onclick="edit_enter($(this).closest('form'))" class="events-edit-btn events-view-mode">Edit</a>
            <span class="events-view-mode">&nbsp;|&nbsp;</span>
            <a onclick="edit_delete($(this).closest('form'))" class="events-delete-btn events-view-mode">Delete</a>

            <a onclick="edit_cancel($(this).closest('form'))" class="events-cancel-edit-btn events-edit-mode">Cancel</a>
            <input type="submit" class="events-save-btn events-edit-mode" value="Save" />
        </section>
        <div class="clearfix"></div>
        <div class="events-details-col events-edit-mode">
            <textarea class="event-edit-input" placeholder="Details (ex. links to meeting notes). You may use HTML." name="details" disabled>$details</textarea>
        </div>
    </form>
EOF;
            }
            echo '</div>';

            if ($files_count == 0) {
                echo '<div class="event-status-row">No '. $type .' found</div>';
            }
        } else {
            echo '<div class="event-status-row">Did not receive type</div>';
        }
    } else {
        echo 'Access denied';
    }
});
get('/events/change', function () {
    echo 'Access denied';
});
post('/events/change', function () {
	$user = $_SESSION['user'];
	$role = user('role', $user);

    if(login() && $role === 'admin') {
        $file_name = $_POST['file_name'];
        $date = $_POST['date'];
        $time_start = $_POST['time_start'];
        $time_end = $_POST['time_end'];
        $desc = urlencode($_POST['description']);
        $type = $_POST['type'];
        $details = urlencode($_POST['details']);

        $next_id = intval(file_get_contents('config/next-meeting-id.dat'));
        file_put_contents('config/next-meeting-id.dat', $next_id + 1);

        if (empty($time_start)) {
            $time_start = 'Not available';
        }
        if (empty($time_end)) {
            $time_end = 'Not available';
        }

        if (isset($file_name) && file_exists($file_name)) {
            unlink($file_name);
        }

        $new_file_name = 'config/'.$type.'/'. $next_id .'.ini';

        $file = fopen($new_file_name, "w");

        fwrite($file, "date = $date\n");
        fwrite($file, "\n");
        fwrite($file, "time.start = $time_start\n");
        fwrite($file, "\n");
        fwrite($file, "time.end = $time_end\n");
        fwrite($file, "\n");
        fwrite($file, "name = \"$desc\"\n");
        fwrite($file, "\n");
        fwrite($file, "details = \"$details\"\n");

        echo 'The event or meeting has been edited/created.';
    }
});


// The file upload page
get('/admin/file-upload', function () {

	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(login()) {

		pref('views.root', 'system/admin/views');
		if($role === 'admin') {
            if(!file_exists('uploads/' . date('Y') . '/' . date('m') . '/')) {
                $mask=umask(0);
                mkdir('uploads/' . date('Y') . '/' . date('m') . '/', 0777, true);
                umask($mask);
            }

            $year_dirs = array_filter(glob('uploads/*'), 'is_dir');
            for ($i = 0, $size = count($year_dirs); $i < $size; $i++) {
                $year_dirs[$i] = remove_first($year_dirs[$i], 'uploads/');
            }

			render('file-upload',array(
				'head_contents' => head_contents('File Upload | ' . blog_title(), blog_description(), site_url()),
				'heading'       => 'File upload',
				'bodyclass'     => 'file-upload',
				'breadcrumb'    => '',
                'year_dirs'     => $year_dirs
			));
		}
		else {
			render('denied',array(
				'head_contents' => head_contents('File Upload | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => ''
			));
		}
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

get('/uploads/upload', function() {
    echo 'Access denied';
});

post('/uploads/upload', function() {

	$user = $_SESSION['user'];
	$role = user('role', $user);
    if (login() && $role === 'admin') {
        $success = true;

        $count = count($_FILES['upload']['name']);
        for ($i = 0; $i < $count; $i++) {
            // Get the temp file path
            $tmpFilePath = $_FILES['upload']['tmp_name'][$i];

            // Make sure we have a filepath
            if ($tmpFilePath != ""){
                // Setup our new file path
                $newFilePath = "uploads/" . date('Y') . '/' . date('m') . '/' . $_FILES['upload']['name'][$i];
                if(!file_exists("uploads/" . date('Y') . '/' . date('m') . '/')) {
                    $mask=umask(0);
                    mkdir($path, 0777);
                    umask($mask);
                }

                // Upload the file into the temp dir
                if(move_uploaded_file($tmpFilePath, $newFilePath)) {

                } else {
                    $success = false;
                }
            }
        }
    }
    else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('File Upload | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

get('/uploads/get', function() {
    $year = $_GET['year'];
    $month = $_GET['month'];

    if (!isset($year)) {
        echo 'year must be given';
    }

    $month_dirs = array_filter(glob('uploads/' . $year . '/*'), 'is_dir');
    echo '<div class="file-list-tabs" id="month-tabs">';
    for ($i = 0, $size = count($month_dirs); $i < $size; $i++) {
        $month_dirs[$i] = remove_first($month_dirs[$i], 'uploads/' . $year . '/');

        $class = 'file-list-tab month-tab';
        if (isset($month) && $month == $month_dirs[$i]) {
            $class .= ' active';
        }

        $month_short_name = '???';
        switch($month_dirs[$i]) {
        case 1:  $month_short_name = 'Jan'; break;
        case 2:  $month_short_name = 'Feb'; break;
        case 3:  $month_short_name = 'Mar'; break;
        case 4:  $month_short_name = 'Apr'; break;
        case 5:  $month_short_name = 'May'; break;
        case 6:  $month_short_name = 'Jun'; break;
        case 7:  $month_short_name = 'Jul'; break;
        case 8:  $month_short_name = 'Aug'; break;
        case 9:  $month_short_name = 'Sep'; break;
        case 10: $month_short_name = 'Oct'; break;
        case 11: $month_short_name = 'Nov'; break;
        case 12: $month_short_name = 'Dec'; break;
        }

        echo '<div class="'.$class.'" onclick="switch_month('.$year.','.$month_dirs[$i].')">' . $month_short_name . '</div>';
    }
    echo '<div class="clearfix"></div></div>';
    echo '<div id="file-list-content"></div>';

    if (isset($month)) {
        $month = sprintf("%02s", $month);

        $files = array_filter(glob('uploads/' . $year . '/' . $month . '/*'), 'is_file');
        $files_count = count($files);
        if ($files_count == 0) {
            echo '<div>No files here</div>';
        } else {
            echo '<ul class="file-list" data-count="'.$files_count.'">';
            for ($i = 0; $i < $files_count; $i++) {
                $files[$i] = remove_first($files[$i], 'uploads/' . $year . '/' . $month . '/');
                $file_path = site_url() . 'uploads/' . $year . '/' . $month . '/' . htmlspecialchars($files[$i]);

                echo <<<EOF
    <li>
        <div class="file-list-name"><a target="_blank" href="$file_path">$files[$i]</a></div>
        <div class="file-list-path"><input type="text" value="$file_path" readonly="readonly" /></div>
        <div class="file-list-opts">
            <a class="file-list-select-path" href="#" onclick="$(this).closest('li').find('.file-list-path input').select();return false">select path</a>
            <span>&nbsp;|&nbsp;</span>
            <a class="file-list-delete" href="#" onclick="delete_file($year, $month, '$files[$i]');return false">delete</a>
            <input type="checkbox" onchange="checkMultiActionUpdate(this)" class="files-multi-action-checkbox" data-year="$year" data-month="$month" data-file="$files[$i]"/>
        </div>
        <div class="clearfix"></div>
    </li>
EOF;
            }
        }
        echo '</ul>';
    } else {
        echo '<div>Choose a month</div>';
    }

    echo '</div>';
});

get('/uploads/delete', function() {
    echo 'Access denied';
});

post('/uploads/delete', function() {
    $user = $_SESSION['user'];
	$role = user('role', $user);
    if (login() && $role === 'admin') {

        $year = $_POST['year'];
        $month = $_POST['month'];
        $file = $_POST['file_name'];

        if (isset($year) && isset($month) && isset($file)) {
            $month = sprintf("%02s", $month);
            $path = $_SERVER['DOCUMENT_ROOT'] . 'uploads/' . $year . '/' . $month . '/' . $file;
            $http_path = site_url() . 'uploads/' . $year . '/' . $month . '/' . $file;

            echo from($_REQUEST, 'file');
            if (delete_upload($path)) {
                echo 'Successfully deleted ' . $http_path;
            } else {
                echo 'Failed to delete ' . $http_path;
            }
        } else {
            echo 'year, month and file_name must be given';
        }
    } else {
        echo 'Access denied';
    }
});

// The static page
get('/:static', function($static){

	if($static === 'sitemap.xml' || $static === 'sitemap.base.xml' || $static === 'sitemap.post.xml' || $static === 'sitemap.static.xml' || $static === 'sitemap.tag.xml' || $static === 'sitemap.archive.xml' || $static === 'sitemap.author.xml') {

		header('Content-Type: text/xml');

		if ($static === 'sitemap.xml') {
			generate_sitemap('index');
		}
		else if ($static === 'sitemap.base.xml') {
			generate_sitemap('base');
		}
		else if ($static === 'sitemap.post.xml') {
			generate_sitemap('post');
		}
		else if ($static === 'sitemap.static.xml') {
			generate_sitemap('static');
		}
		else if ($static === 'sitemap.tag.xml') {
			generate_sitemap('tag');
		}
		else if ($static === 'sitemap.archive.xml') {
			generate_sitemap('archive');
		}
		else if ($static === 'sitemap.author.xml') {
			generate_sitemap('author');
		}

		die;

	}
	elseif($static === 'admin') {
		if(login()) {
            if (get_user_role() === 'admin' || get_user_role() === 'editor') {
                pref('views.root', 'system/admin/views');
                render('main', array(
                    'head_contents' => head_contents('Admin | ' . blog_title(), blog_description(), site_url()),
                    'bodyclass' => 'adminfront',
                    'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Admin'
                ));
            } else {
                not_found();
            }
		}
		else {
			$login = site_url() . 'login';
			header("location: $login");
		}
		die;
	}
	elseif($static === 'login') {
		pref('views.root', 'system/admin/views');
		render('login', array(
			'head_contents' => head_contents('Login | ' . blog_title(), 'Login page from ' . blog_title() . '.', site_url() . '/login'),
			'bodyclass' => 'inlogin',
			'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Login'
		));
		die;
	}
    elseif($static === 'register-account') {
		pref('views.root', 'system/admin/views');
		render('register', array(
			'head_contents' => head_contents('Register account | ' . blog_title(), 'Account registration page from ' . blog_title() . '.', site_url() . '/register-account'),
			'bodyclass' => 'inlogin signuppage',
			'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Register account'
		));
		die;
	}
	elseif($static === 'logout') {
		if(login()) {
			pref('views.root', 'system/admin/views');
			render('logout', array(
				'head_contents' => head_contents('Logout | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'inlogout',
				'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Logout'
			));
		}
		else {
			$login = site_url() . 'login';
			header("location: $login");
		}
		die;
	}
	else {
		$post = get_static_post($static);

		if(!$post){
			not_found();
		}

		$post = $post[0];

		render('static',array(
			'head_contents' => head_contents($post->title .' | ' . blog_title(), $description = get_description($post->body), $post->url),
			'bodyclass' => 'inpage',
			'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; ' . $post->title,
			'p' => $post,
			'type' => 'staticpage',
		));
	}

});

// Edit the static page
get('/:static/edit', function($static){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
        pref('views.root', 'system/admin/views');

        if ($role === 'admin' || $role === 'editor') {
            $post = get_static_post($static);

            if(!$post){
                not_found();
            }

            $post = $post[0];

            render('edit-page',array(
                'head_contents' => head_contents('Edit page | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'Edit Page',
                'bodyclass' => 'editpage',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; ' . $post->title,
                'p' => $post,
                'type' => 'staticpage',
            ));
        } else {
			render('denied',array(
				'head_contents' => head_contents('Edit page | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Get edited data for static page
post('/:static/edit', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);

    if (login() && $role === 'admin' || $role === 'editor') {
        $title = from($_REQUEST, 'title');
        $url = from($_REQUEST, 'url');
        $content = from($_REQUEST, 'content');
        $oldfile = from($_REQUEST, 'oldfile');
        $destination = from($_GET, 'destination');
        if(!empty($title) && !empty($content)) {
            if(!empty($url)) {
                edit_page($title, $url, $content, $oldfile, $destination);
            }
            else {
                $url = $title;
                edit_page($title, $url, $content, $oldfile, $destination);
            }
        }
        else {
            $message['error'] = '';
            if(empty($title)) {
                $message['error'] .= '<li>Title field is required.</li>';
            }
            if (empty($content)) {
                $message['error'] .= '<li>Content field is required.</li>';
            }
            pref('views.root', 'system/admin/views');

            render('edit-page',array(
                'head_contents' => head_contents('Edit page | ' . blog_title(), blog_description(), site_url()),
                'error' => '<ul>' . $message['error'] . '</ul>',
                'oldfile' => $oldfile,
                'postTitle' => $title,
                'postUrl' => $url,
                'postContent' => $content,
                'bodyclass' => 'editpage',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Edit page'
            ));
        }
	} else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Edit page | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

// Deleted the static page
get('/:static/delete', function($static){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
        pref('views.root', 'system/admin/views');

        if ($role === 'admin' || $role === 'editor') {
            $post = get_static_post($static);

            if(!$post){
                not_found();
            }

            $post = $post[0];

            render('delete-page',array(
                'head_contents' => head_contents('Delete page | ' . blog_title(), blog_description(), site_url()),
                'bodyclass' => 'deletepage',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; ' . $post->title,
                'p' => $post,
                'type' => 'staticpage',
            ));
        } else {
			render('denied',array(
				'head_contents' => head_contents('Delete page | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Get deleted data for static page
post('/:static/delete', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);

    if (login() && $role === 'admin' || $role === 'editor') {
        $file = from($_REQUEST, 'file');
        $destination = from($_GET, 'destination');
        delete_page($file, $destination);
	} else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Delete page | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

// Add blog post
get('/add/post', function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
        if ($role === 'admin' || $role === 'editor') {
            pref('views.root', 'system/admin/views');
            render('add-post',array(
                'head_contents' => head_contents('Add post | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'Add Post',
                'bodyclass' => 'addpost',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Add post'
            ));
        } else {
			not_found();
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Get submitted blog post data
post('/add/post', function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

    if (login() && $role === 'admin' || $role === 'editor') {
        $title = from($_REQUEST, 'title');
        $tag = from($_REQUEST, 'tag');
        $url = from($_REQUEST, 'url');
        $content = from($_REQUEST, 'content');
        $user = $_SESSION['user'];
        if(!empty($title) && !empty($tag) && !empty($content)) {
            if(!empty($url)) {
                add_post($title, $tag, $url, $content, $user);
            }
            else {
                $url = $title;
                add_post($title, $tag, $url, $content, $user);
            }
        }
        else {
            $message['error'] = '';
            if(empty($title)) {
                $message['error'] .= '<li>Title field is required.</li>';
            }
            if (empty($tag)) {
                $message['error'] .= '<li>Tag field is required.</li>';
            }
            if (empty($content)) {
                $message['error'] .= '<li>Content field is required.</li>';
            }
            pref('views.root', 'system/admin/views');
            render('add-post',array(
                'head_contents' => head_contents('Add post | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'Add Post',
                'error' => '<ul>' . $message['error'] . '</ul>',
                'postTitle' => $title,
                'postTag' => $tag,
                'postUrl' => $url,
                'postContent' => $content,
                'bodyclass' => 'addpost',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Add post'
            ));
        }
	} else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Add post | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

// Add the static page
get('/add/page', function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
        if ($role === 'admin' || $role === 'editor') {
            pref('views.root', 'system/admin/views');
            render('add-page',array(
                'head_contents' => head_contents('Add page | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'Add Page',
                'bodyclass' => 'addpage',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Add page'
            ));
        } else {
			not_found();
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
});

// Get submitted static page data
post('/add/page', function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

    if (login() && $role === 'admin' || $role === 'editor') {
        $title = from($_REQUEST, 'title');
        $url = from($_REQUEST, 'url');
        $content = from($_REQUEST, 'content');
        if(!empty($title) && !empty($content)) {
            if(!empty($url)) {
                add_page($title, $url, $content);
            }
            else {
                $url = $title;
                add_page($title, $url, $content);
            }
        }
        else {
            $message['error'] = '';
            if(empty($title)) {
                $message['error'] .= '<li>Title field is required.</li>';
            }
            if (empty($content)) {
                $message['error'] .= '<li>Content field is required.</li>';
            }
            pref('views.root', 'system/admin/views');
            render('add-page',array(
                'head_contents' => head_contents('Add page | ' . blog_title(), blog_description(), site_url()),
                'heading' => 'Add Page',
                'error' => '<ul>' . $message['error'] . '</ul>',
                'postTitle' => $title,
                'postUrl' => $url,
                'postContent' => $content,
                'bodyclass' => 'addpage',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Add page'
            ));
        }
	} else {
        pref('views.root', 'system/admin/views');
        render('denied',array(
            'head_contents' => head_contents('Add page | ' . blog_title(), blog_description(), site_url()),
            'bodyclass' => 'denied',
            'breadcrumb' => '',
        ));
    }
});

// Import page
get('/admin/import',function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
		pref('views.root', 'system/admin/views');
        if($role === 'admin') {
            render('import', array(
                'head_contents' => head_contents('Import feed | ' . blog_title(), blog_description(), site_url()),
                'bodyclass' => 'importfeed',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Import feed'
            ));
        } else {
            render('denied',array(
				'head_contents' => head_contents('All blog posts | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
        }
	} else {
		$login = site_url() . 'login';
		header("location: $login");
	}
	die;
});

// Get import post
post('/admin/import', function() {
	$user = $_SESSION['user'];
	$role = user('role', $user);

    if(login() && $role === 'admin') {
        $url = from($_REQUEST, 'url');
        $credit = from($_REQUEST, 'credit');
        if(!empty($url)) {

            get_feed($url, $credit, null);
            $log = get_feed($url, $credit, null);

            if(!empty($log)) {

                pref('views.root', 'system/admin/views');

                render('import',array(
                    'head_contents' => head_contents('Import feed | ' . blog_title(), blog_description(), site_url()),
                    'error' => '<ul>' . $log . '</ul>',
                    'bodyclass' => 'editprofile',
                    'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Import feed'
                ));
            }
        }
        else {
            $message['error'] = '';
            if(empty($url)) {
                $message['error'] .= '<li>You need to specify the feed url.</li>';
            }

            pref('views.root', 'system/admin/views');

            render('import',array(
                'head_contents' => head_contents('Import feed | ' . blog_title(), blog_description(), site_url()),
                'error' => '<ul>' . $message['error'] . '</ul>',
                'url' => $url,
                'bodyclass' => 'editprofile',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Login'
            ));
        }
	}
});

// Backup page
get('/admin/backup',function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
		pref('views.root', 'system/admin/views');
        if ($role === 'admin') {
            render('backup', array(
                'head_contents' => head_contents('Backup content | ' . blog_title(), blog_description(), site_url()),
                'bodyclass' => 'backup',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Backup'
            ));
        } else {
            render('denied',array(
				'head_contents' => head_contents('All blog posts | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
	die;
});

// Create Zip file
get('/admin/backup-start',function(){
	$user = $_SESSION['user'];
	$role = user('role', $user);

	if(login()) {
		pref('views.root', 'system/admin/views');
        if ($role === 'admin') {
            render('backup-start', array(
                'head_contents' => head_contents('Backup content started | ' . blog_title(), blog_description(), site_url()),
                'bodyclass' => 'startbackup',
                'breadcrumb' => '<a href="' . site_url() . '">' .config('breadcrumb.home'). '</a> &#187; Backup started'
            ));
        } else {
            render('denied',array(
				'head_contents' => head_contents('All blog posts | ' . blog_title(), blog_description(), site_url()),
				'bodyclass' => 'denied',
				'breadcrumb' => '',
			));
        }
	}
	else {
		$login = site_url() . 'login';
		header("location: $login");
	}
	die;
});


// The tag page
get('/tag/:tag',function($tag){

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;
	$perpage = config('tag.perpage');

	$posts = get_tag($tag, $page, $perpage, false);

	$total = get_count($tag, 'filename');

	if(empty($posts) || $page < 1){
		// a non-existing page
		not_found();
	}

    render('main',array(
		'head_contents' => head_contents('Posts tagged: ' . $tag .' | ' . blog_title(), 'All posts tagged: ' . $tag . ' on '. blog_title() . '.', site_url() . 'tag/' . $tag),
    	'page' => $page,
		'posts' => $posts,
		'bodyclass' => 'intag',
		'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Posts tagged: ' . $tag,
		'pagination' => has_pagination($total, $perpage, $page)
	));
});

// The archive page
get('/archive/:req',function($req){

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;
	$perpage = pref('archive.perpage');

	$posts = get_archive($req, $page, $perpage);

	$total = get_count($req, 'filename');

	if(empty($posts) || $page < 1){
		// a non-existing page
		not_found();
	}

	$time = explode('-', $req);
	$date = strtotime($req);

	if (isset($time[0]) && isset($time[1]) && isset($time[2])) {
		$timestamp = date('d F Y', $date);
	}
	else if (isset($time[0]) && isset($time[1])) {
		$timestamp = date('F Y', $date);
	}
	else {
		$timestamp = $req;
	}

	if(!$date){
		// a non-existing page
		not_found();
	}

    render('main',array(
		'head_contents' => head_contents('Archive for: ' . $timestamp .' | ' . blog_title(), 'Archive page for: ' . $timestamp . ' on ' . blog_title() . '.', site_url() . 'archive/' . $req),
    	'page' => $page,
		'posts' => $posts,
		'bodyclass' => 'inarchive',
		'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Archive for: ' . $timestamp,
		'pagination' => has_pagination($total, $perpage, $page)
	));
});


// The search page
get('/search/:keyword', function($keyword){

	$page = from($_GET, 'page');
	$page = $page ? (int)$page : 1;
	$perpage = pref('search.perpage');

	$posts = normal_search($keyword, $page, $perpage);

	$total = keyword_count($keyword);

	if(empty($posts) || $page < 1){
		render('404-search', array(
            'head_contents' => head_contents('Search results for: ' . $keyword . ' | ' . blog_title(), 'Search results for: ' . $keyword . ' on '. blog_title() . '.', site_url() . 'search/' . $keyword),
            'bodyclass' => 'insearch',
            'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Search results for: ' . $keyword
        ));
	} else {
        render('main',array(
            'head_contents' => head_contents('Search results for: ' . $keyword . ' | ' . blog_title(), 'Search results for: ' . $keyword . ' on '. blog_title() . '.', site_url() . 'search/' . $keyword),
            'page' => $page,
            'posts' => $posts,
            'bodyclass' => 'insearch',
            'breadcrumb' => '<a href="' . site_url() .  '">' .config('breadcrumb.home'). '</a> &#187; Search results for: ' . $keyword,
            'pagination' => has_pagination($total, $perpage, $page)
        ));
    }
});

// API Stuff
// --------------------------------------------------------------------------------

// The JSON API
get('/api/json',function(){

	header('Content-type: application/json');

	// Print the 10 latest posts as JSON
	echo generate_json(get_posts(null, 1, config('json.count')));
});

get('/api/is-logged-in',function(){
    if (login())
        echo 'true';
    else
        echo 'false';
});

// Show the RSS feed
get('/feed/rss',function(){

	header('Content-Type: application/rss+xml');

	// Show an RSS feed with the 30 latest posts
	echo generate_rss(get_posts(null, 1, config('rss.count')));
});

// Generate OPML file
get('/feed/opml',function(){

	header('Content-Type: text/xml');

	// Generate OPML file for the RSS
	echo generate_opml();

});

// If we get here, it means that
// nothing has been matched above

get('.*',function(){
	not_found();
});

// Serve the blog
dispatch();
