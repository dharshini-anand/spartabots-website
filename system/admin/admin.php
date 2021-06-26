<?php
// Return username.ini value
function user($key, $user=null) {
	$value = user_data_file($user);
	static $_config = array();
	if (file_exists($value)) {
		$_config = parse_ini_file($value, true);
		if(!empty($_config[$key])) {
			return $_config[$key];
		}
	}
}

function user_id($user) {
    foreach (glob(user_data_path($user) . "id-*") as $filename) {
        return substr(remove_first(basename($filename), 'id-'), 0, -4);
    }
}

function user_exists($user) {
    return file_exists(user_data_path($user));
}

function user_from_id($id) {
    foreach (glob("config/users/*/id-$id.dat") as $filename) {
        return basename(dirname($filename));
    }
}

// Returns the main user data file containing the user's hashed password, email and such
function user_data_file($user, $filename = null) {
    if (isset($filename)) {
        return 'config/users/' . $user  . '/' . $filename . '.ini';
    } else {
        return 'config/users/' . $user  . '/user_data.ini';
    }
}
// Returns the main user data file containing the user's hashed password, email and such
function user_data_dat_file($user, $filename) {
    return 'config/users/' . $user  . '/' . $filename . '.dat';
}

function user_data_file_glob($filename = 'user_data.ini') {
    return array_filter(glob('config/users/*/' . $filename), 'is_file');
}

function user_data_path($user) {
    return 'config/users/' . $user  . '/';
}

function user_login_history($user, $login) {
    $date_str = date('F d, Y h:i:s a');
    $action_str = ($login) ? 'Login' : 'Logout';
    
    if (file_exists(user_data_dat_file($user, 'login_history'))) {
        $login_history_file = fopen(user_data_dat_file($user, 'login_history'), "a");
        fwrite($login_history_file, "$action_str@$date_str\n");
        fclose($login_history_file);
    } else {
        $login_history_file = fopen(user_data_dat_file($user, 'login_history'), "w");
        fwrite($login_history_file, "$action_str@$date_str\n");
        fclose($login_history_file);
        
        chmod(user_data_dat_file($user, 'login_history'), 0777);
    }
}

// ClouldFlare API Stuff
function cf_stats($interval) {
    $ch = curl_init("https://www.cloudflare.com/api_json.html");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

    // handling of -d curl parameter is here.
    $param = array(
        'a' => 'stats',
        'tkn' => '77b1c49a1ceae08a87f6ed10094fe1ac3b012',
        'email' => 'skyline.spartabots@gmail.com',
        'z' => 'spartabots.org',
        'interval' => $interval
    );
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

    $result = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($result, true)['response']['result']['objs'][0];

    $trafficPageViews = $data['trafficBreakdown']['pageviews'];
    $trafficPageViewsReg = $trafficPageViews['regular'];
    $trafficPageViewsThreat = $trafficPageViews['threat'];
    $trafficPageViewsCrawler = $trafficPageViews['crawler'];

    $trafficUnique = $data['trafficBreakdown']['uniques'];
    $trafficUniqueReg = $trafficUnique['regular'];
    $trafficUniqueThreat = $trafficUnique['threat'];
    $trafficUniqueCrawler = $trafficUnique['crawler'];

    $bwServedCF = $data['bandwidthServed']['cloudflare'];
    $bwServedUser = $data['bandwidthServed']['user'];

    $reqServedCF = $data['requestsServed']['cloudflare'];
    $reqServedUser = $data['requestsServed']['user'];
    
    return array(
        'trafficPageViewsReg' => $trafficPageViewsReg,
        'trafficPageViewsThreat' => $trafficPageViewsThreat,
        'trafficPageViewsCrawler' => $trafficPageViewsCrawler,
        'trafficUniqueReg' => $trafficUniqueReg,
        'trafficUniqueThreat' => $trafficUniqueThreat,
        'trafficUniqueCrawler' => $trafficUniqueCrawler,
        'bwServedCF' => $bwServedCF,
        'bwServedUser' => $bwServedUser,
        'reqServedCF' => $reqServedCF,
        'reqServedUser' => $reqServedUser
    );
}

// Edit blog posts
function edit_post($title, $tag, $url, $content, $oldfile, $destination = null) {

	$oldurl = explode('_', $oldfile);

	$post_title = $title;
	$post_tag = preg_replace('/[^A-Za-z0-9,.-]/u', '', ucwords($tag));
	$post_tag = str_replace(' ', '-',$post_tag);
	$post_tag = rtrim(ltrim($post_tag, ',\.\-'), ',\.\-');
	$post_url = preg_replace('/[^A-Za-z0-9 ,.-]/u', '', strtolower($url));
	$post_url = str_replace(' ', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = rtrim(ltrim($post_url, ',\.\-'), ',\.\-');
	$post_content = '<!--t ' . $post_title . ' t-->' . "\n\n" . $content;
		
	if(!empty($post_title) && !empty($post_tag) && !empty($post_url) && !empty($post_content)) {
		if(get_magic_quotes_gpc()) {
			$post_content = stripslashes($post_content);
		}
		$newfile = $oldurl[0] . '_' . $post_tag . '_' . $post_url . '.md';
		if($oldfile === $newfile) {
			file_put_contents($oldfile, print_r($post_content, true));
		}
		else {
			rename($oldfile, $newfile);
			file_put_contents($newfile, print_r($post_content, true));
		}
		
		$replaced = substr($oldurl[0], 0,strrpos($oldurl[0], '/')) . '/';
		$dt = str_replace($replaced,'',$oldurl[0]);
		$t = str_replace('-','',$dt);
		$time = new DateTime($t);
		$timestamp= $time->format("Y-m-d");
		
		// The post date
		$postdate = strtotime($timestamp);
		
		// The post URL
		$posturl = site_url().date('Y/m', $postdate).'/'.$post_url;
		
		if ($destination == 'post') {
			header("Location: $posturl");
		}
		else {
			$redirect = site_url() . $destination;
			header("Location: $redirect");
		}
		
	}
		
}

// Edit static page
function edit_page($title, $url, $content, $oldfile, $destination = null) {

	$dir = substr($oldfile, 0, strrpos($oldfile, '/'));

	$post_title = $title;
	$post_url = preg_replace('/[^A-Za-z0-9 ,.-]/u', '', strtolower($url));
	$post_url = str_replace(' ', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = rtrim(ltrim($post_url, ',\.\-'), ',\.\-');
	$post_content = '<!--t ' . $post_title . ' t-->' . "\n\n" . $content;
		
	if(!empty($post_title) && !empty($post_url) && !empty($post_content)) {
		if(get_magic_quotes_gpc()) {
			$post_content = stripslashes($post_content);
		}
		$newfile = $dir . '/' . $post_url . '.md';
		if($oldfile === $newfile) {
			file_put_contents($oldfile, print_r($post_content, true));
		}
		else {
			rename($oldfile, $newfile);
			file_put_contents($newfile, print_r($post_content, true));
		}
		
		$posturl = site_url() . $post_url;
		
		if ($destination == 'post') {
			header("Location: $posturl");
		}
		else {
			$redirect = site_url() . $destination;
			header("Location: $redirect");
		}
		
	}
		
}

// Add blog post
function add_post($title, $tag, $url, $content, $user) {

	$post_date = date('Y-m-d-H-i-s');
	$post_title = $title;
	$post_tag = preg_replace('/[^A-Za-z0-9,.-]/u', '', ucwords($tag));
	$post_tag = rtrim(ltrim($post_tag, ',\.\-'), ',\.\-');
	$post_url = preg_replace('/[^A-Za-z0-9 ,.-]/u', '', strtolower($url));
	$post_url = str_replace(' ', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = rtrim(ltrim($post_url, ' \,\.\-'), ' \,\.\-');
	$post_content = '<!--t ' . $post_title . ' t-->' . "\n\n" . $content;
	
	if(!empty($post_title) && !empty($post_tag) && !empty($post_url) && !empty($post_content)) {
		if(get_magic_quotes_gpc()) {
			$post_content = stripslashes($post_content);
		}
		$filename = $post_date . '_' . $post_tag . '_' . $post_url . '.md';
		$dir = 'content/' . $user. '/blog/';
		if(is_dir($dir)) {
			file_put_contents($dir . $filename, print_r($post_content, true));
		}
		else {
			mkdir($dir, 0777, true);
			file_put_contents($dir . $filename, print_r($post_content, true));
		}
		$redirect = site_url() . 'admin/mine';
		header("Location: $redirect");	
	}
	
}

// Add static page
function add_page($title, $url, $content) {

	$post_title = $title;
	$post_url = preg_replace('/[^A-Za-z0-9 ,.-]/u', '', strtolower($url));
	$post_url = str_replace(' ', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = rtrim(ltrim($post_url, ',\.\-'), ',\.\-');
	$post_content = '<!--t ' . $post_title . ' t-->' . "\n\n" . $content;
	
	if(!empty($post_title) && !empty($post_url) && !empty($post_content)) {
		if(get_magic_quotes_gpc()) {
			$post_content = stripslashes($post_content);
		}
		$filename = $post_url . '.md';
		$dir = 'content/static/';
		if(is_dir($dir)) {
			file_put_contents($dir . $filename, print_r($post_content, true));
		}
		else {
			mkdir($dir, 0777, true);
			file_put_contents($dir . $filename, print_r($post_content, true));
		}
		$redirect = site_url() . 'admin';
		header("Location: $redirect");
	}
	
}

// Delete blog post
function delete_post($file, $destination) {
	$deleted_content = $file;
	if(!empty($deleted_content)) {
		unlink($deleted_content);
		if($destination == 'post') {
			$redirect = site_url();
			header("Location: $redirect");
		}
		else {
			$redirect = site_url() . $destination;
			header("Location: $redirect");
		}	
	}
}

// Delete static page
function delete_page($file, $destination) {
	$deleted_content = $file;
	if(!empty($deleted_content)) {
		unlink($deleted_content);
		if($destination == 'post') {
			$redirect = site_url();
			header("Location: $redirect");
		}
		else {
			$redirect = site_url() . $destination;
			header("Location: $redirect");
		}			
	}
}

// Delete upload
function delete_upload($file) {
	$deleted_content = $file;
	if(!empty($deleted_content)) {
		return unlink($deleted_content);
	}
    return false;
}

// Edit user profile
function edit_profile($title, $content, $user) {

	$user_title = $title;
	$user_content = '<!--t ' . $user_title . ' t-->' . "\n\n" . $content;
	
	if(!empty($user_title) && !empty($user_content)) {
		if(get_magic_quotes_gpc()) {
			$user_content = stripslashes($user_content);
		}
		$dir = 'content/' . $user. '/';
		$filename = 'content/' . $user . '/author.md';
		if(is_dir($dir)) {
			file_put_contents($filename, print_r($user_content, true));
		}
		else {
			mkdir($dir, 0777, true);
			file_put_contents($filename, print_r($user_content, true));
		}	
	}
	
}

// Edit site config
function edit_config($content) {
	if(!empty($content)) {
		$dir = 'content/' . $user. '/';
		$filename = 'config/config.ini';
        file_put_contents($filename, print_r($content, true));
		$redirect = site_url() . 'admin/edit-config';
		header("Location: $redirect");
	}
}

function change_config($content) {
	if(!empty($content)) {
		$dir = 'content/' . $user. '/';
		$filename = 'config/config.ini';
        file_put_contents($filename, print_r($content, true));
	}
}

// Import RSS feed
function migrate($title, $time, $tags, $content, $url, $user, $source) {

	$post_date = date('Y-m-d-H-i-s', $time);
	$post_title = $title;
	$post_tag = preg_replace('/[^A-Za-z0-9,.-]/u', '', $tags);
	$post_tag = rtrim(ltrim($post_tag, ',\.\-'), ',\.\-');
	$post_url = preg_replace('/[^A-Za-z0-9 ,.-]/u', '', strtolower($url));
	$post_url = str_replace(' ', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = str_replace('--', '-',$post_url);
	$post_url = rtrim(ltrim($post_url, ',\.\-'), ',\.\-');
	if(!empty($source)) {
		$post_content = '<!--t ' . $post_title . ' t-->' . "\n\n" . $content . "\n\n" . 'Source: <a target="_blank" href="' . $source . '">' . $title . '</a>';
	}
	else {
		$post_content = '<!--t ' . $post_title . ' t-->' . "\n\n" . $content;
	}
	if(!empty($post_title) && !empty($post_tag) && !empty($post_url) && !empty($post_content)) {
		if(get_magic_quotes_gpc()) {
			$post_content = stripslashes($post_content);
		}
		$filename = $post_date . '_' . $post_tag . '_' . $post_url . '.md';
		$dir = 'content/' . $user. '/blog/';
		if(is_dir($dir)) {
			file_put_contents($dir . $filename, print_r($post_content, true));
		}
		else {
			mkdir($dir, 0777, true);
			file_put_contents($dir . $filename, print_r($post_content, true));
		}
		
		$redirect = site_url() . 'admin/mine';
		header("Location: $redirect");	
	}
	
}

// Fetch RSS feed
function get_feed($feed_url, $credit, $message=null) {  
    $source = file_get_contents($feed_url);
    $feed = new SimpleXmlElement($source);
	if(!empty($feed->channel->item)) {
		foreach($feed->channel->item as $entry) {
			$descriptionA = $entry->children('content', true);
			$descriptionB = $entry->description;
			if(!empty($descriptionA)) {
				$content = $descriptionA;
			}
			else if (!empty($descriptionB)) {
				$content = preg_replace('#<br\s*/?>#i', "\n", $descriptionB);
			}
			else {
				return $str = '<li>Can not read the feed content.</li>';
			}
			$time = new DateTime($entry->pubDate);
			$timestamp= $time->format("Y-m-d H:i:s");
			$time = strtotime($timestamp);
			$tags = strip_tags(preg_replace('/[^A-Za-z0-9,.-]/u', '', $entry->category));
			$title = rtrim($entry->title, ' \,\.\-');
			$title = ltrim($title, ' \,\.\-');
			$user = $_SESSION['user'];
			$url = preg_replace('/[^A-Za-z0-9 .-]/u', '', strtolower($title));
			$url = str_replace(' ', '-',$url);
			$url = str_replace('--', '-',$url);
			$url = str_replace('--', '-',$url);
			$url = rtrim($url, ',\.\-');
			$url = ltrim($url, ',\.\-');
			if ($credit == 'yes') {
				$source = $entry->link;
			}
			else {
				$source= null;
			}
			migrate($title, $time, $tags, $content, $url, $user, $source);
		}
	}
	else {
		return $str= '<li>Unsupported feed.</li>';
	}
	
}  

// Get recent posts by user
function get_recent_posts() {
	if (isset($_SESSION['user'])) {
		$posts = get_profile($_SESSION['user'], 1, 5);
		if(!empty($posts)) {
			echo '<table class="post-list">';
			echo '<tr class="head"><th>Title</th><th>Published</th><th>Tag</th><th>Operations</th></tr>';
			$i = 0; $len = count($posts);
			foreach($posts as $p) {
				if ($i == 0) {
					$class = 'item first';
				} 
				elseif ($i == $len - 1) {
					$class = 'item last';
				}
				else {
					$class = 'item';
				}
				$i++;
				echo '<tr class="' . $class . '">';
				echo '<td><a target="_blank" href="' . $p->url . '">' . $p->title . '</a></td>';
				echo '<td>' . date('d F Y', $p->date) . '</td>';
				echo '<td>' . $p->tag . '</td>';
				echo '<td><a href="' . $p->url . '/edit?destination=admin">Edit</a> <a href="' . $p->url . '/delete?destination=admin">Delete</a></td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
            echo '<div><span>No posts found.</span></div>';
        }
	}
}

// Get all static pages
function get_recent_pages() {
	if (isset($_SESSION['user'])) {
		$posts = get_static_post(null);
		if(!empty($posts)) {
			krsort($posts);
			echo '<table class="post-list">';
			echo '<tr class="head"><th>Title</th><th>Operations</th></tr>';
			$i = 0; $len = count($posts);
			foreach($posts as $p) {
				if ($i == 0) {
					$class = 'item first';
				} 
				elseif ($i == $len - 1) {
					$class = 'item last';
				}
				else {
					$class = 'item';
				}
				$i++;
				echo '<tr class="' . $class . '">';
				echo '<td><a target="_blank" href="' . $p->url . '">' . $p->title . '</a></td>';
				echo '<td><a href="' . $p->url . '/edit?destination=admin">Edit</a> <a href="' . $p->url . '/delete?destination=admin">Delete</a></td>';
				echo '</tr>';
			}
			echo '</table>';
		} else {
            echo '<div><span>No posts found.</span></div>';
        }
	}
}

// Get all available zip files
function get_backup_files () {
	if (isset($_SESSION['user'])) {
		$files = get_zip_files();
		if(!empty($files)) {
			krsort($files);
			echo '<table class="backup-list">';
			echo '<tr class="head"><th>Filename</th><th>Date</th><th>Operations</th></tr>';
			$i = 0; $len = count($files);
			foreach($files as $file) {
			
				if ($i == 0) {
					$class = 'item first';
				} 
				elseif ($i == $len - 1) {
					$class = 'item last';
				}
				else {
					$class = 'item';
				}
				$i++;
				
				// Extract the date
				$arr = explode('_', $file);
				
				// Replaced string
				$replaced = substr($arr[0], 0,strrpos($arr[0], '/')) . '/';
				
				$name = str_replace($replaced,'',$file);
				
				$date = str_replace('.zip','',$arr[1]);
				$t = str_replace('-', '', $date);
				$time = new DateTime($t);
				$timestamp= $time->format("D, d F Y, H:i:s");
				
				$url = site_url() . $file;
				echo '<tr class="' . $class . '">';
				echo '<td>' . $name . '</td>';
				echo '<td>' . $timestamp . '</td>';
				echo '<td><a target="_blank" href="' . $url . '">Download</a> <form method="GET"><input type="hidden" name="file" value="' . $file . '"/><input type="submit" name="submit" value="Delete"/></form></td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		else {
			echo 'No available backup!';
		}
	}
}

// Navigation stuff

function admin_nav_menu() {
    $menu = config('blog.menu');
    
    if (!empty($menu)) {
    
        $links = preg_split('/\|(?![^{]*\})/', $menu);
		echo '<ul id="nav-list-main" class="nav-list">';
        
        foreach($links as $link) {
            $sub = explode('{', $link);
            
            $sub_menu = '';
            if (isset($sub[1])) {
                $sub_links = explode('|', rtrim($sub[1], '}'));
                $sub_menu .= '<ul class="nav-sub-list">';
                foreach($sub_links as $sub_link) {
                    $sub_anc = explode('->', $sub_link);
                    if(isset($sub_anc[0]) && isset($sub_anc[1])) {
                        $sub_menu .= <<<EOF
    <li class="sub-item">
        <div class="nav-list-text">
            <input type="text" class="sub-item-text" value="$sub_anc[0]"/>
        </div>
        <div class="nav-list-anchor">
            <input type="text" class="sub-item-anchor" value="$sub_anc[1]" />
        </div>
        <div class="nav-list-opts">
            <a class="item-move-down" onclick="move_item($(this), true);return false" href="#">down</a>
            <span>&nbsp;|&nbsp;</span>
            <a class="item-move-up" onclick="move_item($(this), false);return false" href="#">up</a>
            <span>&nbsp;|&nbsp;</span>
            <a class="item-delete" onclick="if (confirm(\'Delete item?\')) { $(this).closest(\'li\').remove()}return false" href="#">delete</a>
        </div>
        <div class="clearfix"></div>
    </li>
EOF;
                    }
                }
                $sub_menu .= '</ul>';
            } else {
                $sub_menu .= '<ul class="nav-sub-list"></ul>';
            }
            $sub_menu .= <<<EOF
    <div class="sub-nav-list-add-item" class="nav-list-row">
        <div class="nav-list-text">
            <input class="sub-nav-list-add-text" type="text" placeholder="Item text" />
        </div>
        <div class="nav-list-anchor">
            <input class="sub-nav-list-add-anchor" type="text" placeholder="Item anchor" />
        </div>
        <div class="nav-list-opts">
            <input class="sub-nav-list-add-submit" type="button" value="Add Sub-Item" onclick="subAddAction(this);$(this).closest('.sub-nav-list-add-item').hide();return false" />
            <a class="sub-nav-list-add-cancel" onclick="$(this).closest('.sub-nav-list-add-item').hide();return false">Cancel</a>
        </div>
        <div class="clearfix"></div>
    </div>
EOF;
            
            if (isset($sub[0])) {
                $anc = explode('->', $sub[0]);
                if(isset($anc[0]) && isset($anc[1])) {
                    echo <<<EOF
    <li class="item">
        <div class="item-main">
            <div class="nav-list-text">
                <input type="text" class="item-text" value="$anc[0]"/>
            </div>
            <div class="nav-list-anchor">
                <input type="text" class="item-anchor" value="$anc[1]" />
            </div>
            <div class="nav-list-opts">
                <a class="item-move-down" onclick="move_item($(this), true);return false" href="#">down</a>
                <span>&nbsp;|&nbsp;</span>
                <a class="item-move-up" onclick="move_item($(this), false);return false" href="#">up</a>
                <span>&nbsp;|&nbsp;</span>
                <a class="item-delete" onclick="if (confirm('Delete item?')) { $(this).closest('li').remove()}return false" href="#">delete</a>
                <span>&nbsp;|&nbsp;</span>
                <a class="item-add-sub" onclick="$(this).closest('li').find('.sub-nav-list-add-item').show();return false" href="#">Add child</a>
            </div>
            <div class="clearfix"></div>
        </div>
        $sub_menu
    </li>
EOF;
                }
            }
        }
		echo '</ul>';
    } else {
        echo '<div>Not applicable for static pages</div>';
    }
}

function edit_nav($content) {
	if(!empty($content)) {
        config('blog.menu', $content);
        config('source-write', 'config/config.ini');
	}
}


// ACCOUNT VALIDATION/REGISTRATION/ETC.
// ------------------------------------------------------------------------------------------------

function send_verification($user, $key, $is_resend, $email = null) {
    if (empty($email)) {
        $email = user('email', $user);
    }
    
    $user = htmlspecialchars($user);
    $key = htmlspecialchars($key);
    $email = htmlspecialchars($email);
    $link = site_url() . "verify-registration?username=$user&confirm=$key";
    
    $message = "<div style=\"font-size:17px;line-height:22px\">";
    $message .= "<p style=\"margin-bottom:10px\">Hi <b>$user</b>,</p>";
    $message .= "<p>Welcome to spartabots.org! Please verify your email address below.</p>";
    $message .= "</div>";
    
    if ($is_resend) {
        $content = "<p style=\"font-size:17px;line-height:22px\">";
        $message .= "This account verification was resent from http://www.spartabots.org/resend-verification<br/>";
        $message .= "If you did not resend this verification, please ignore this email.";
        $message .= "</p>";
    }
    
    $message .= "<hr style=\"border:0;border-top:1px solid #e3e3e3;height:0px;margin-top:20px;margin-bottom:20px;\" />";
    $message .= '<a href="'.$link.'"  target="_blank" style="display:block;text-align:center"><div style="display:inline-block;background-color:#2BAD4A;color:#fff;text-decoration:none;font-weight:700;font-size:18px;padding-top:8px;padding-left:16px;padding-bottom:8px;padding-right:16px;border-radius:3px">Verify \''.$email.'\'</div></a>';
    $message .= "<hr style=\"border:0;border-top:1px solid #e3e3e3;height:0px;margin-top:20px;margin-bottom:20px\" />";
    
    $message .= "<p style=\"font-size:17px;line-height:22px\">";
    $message .= "Or, paste this link into your browser:<br/>";
    $message .= "$link";
    $message .= "</p>";
    
    $message .= "<p style=\"font-size:17px;line-height:22px;margin-top:20px\">";
    $message .= "Thanks,<br/>";
    $message .= "The Spartabots team";
    $message .= "</p>";
    
    $message .= "<p style=\"font-size:14px;line-height:20px;margin-top:25px\">";
    $message .= "If you did not register for this website, please ignore this email.";
    $message .= "</p>";
    
    send_no_reply_email($email, 'Action required to activate membership for Spartabots.org', $message);
}

function create_account($user, $password, $role, $email, $realname, $verify = true) {
    $length = 16;
    //$salt = base64_encode(mcrypt_create_iv(ceil(0.75*$length), MCRYPT_DEV_URANDOM));
    $salt = base64_encode(random_bytes(12));
    
    $hash_password = pbkdf2('SHA256', $password, $salt, 1024, 64 * 8);
    
    if (!file_exists(user_data_path($user))) {
        mkdir(user_data_path($user), 0777, true);
        chmod(user_data_path($user), 0777);
        change_account_pref($user, '');
        
        $login_history_file = fopen(user_data_dat_file($user, 'login_history'), "w");
        fwrite($login_history_file, '');
        fclose($login_history_file);
        chmod(user_data_dat_file($user, 'login_history'), 0777);
		
		$user_id = next_user_id();
		if ($user_id == 13 || $user_id == 69 || $user_id == 666) {
			$user_id = next_user_id();
		}
		
        $user_id_file = fopen(user_data_dat_file($user, 'id-' . $user_id), "w");
        fwrite($user_id_file, $user_id);
        fclose($user_id_file);
        chmod(user_data_dat_file($user, 'id-' . $user_id), 0777);
    }
    
    foreach (glob(user_data_path($user) . "email-*") as $filename) {
        unlink($filename);
    }
    $email_hash = md5($email);
    $email_hash_file = fopen(user_data_dat_file($user, 'email-' . $email_hash), "w");
    fwrite($email_hash_file, '');
    fclose($email_hash_file);
    chmod(user_data_dat_file($user, 'email-' . $email_hash), 0777);
    
    if ($verify) {
        if (!file_exists(user_data_file($user, 'verification'))) {
            $verify_file = fopen(user_data_file($user, 'verification'), "w");
            $verify_key = md5(rand(0, 1000));
            
            fwrite($verify_file, "verified = \"false\"\n");
            fwrite($verify_file, "key = \"$verify_key\"\n");
            fclose($verify_file);
            
            chmod(user_data_file($user, 'verification'), 0777);
            
            send_verification($user, $verify_key, false, $email);
        } else {
            $verify_data = parse_ini_file(user_data_file($user, 'verification'));
            if (!($verify_data['verified'] === 'true')) {
                $verify_file = fopen(user_data_file($user, 'verification'), "w");
                $verify_key = md5(rand(0, 1000));
                
                fwrite($verify_file, "verified = \"false\"\n");
                fwrite($verify_file, "key = \"$verify_key\"\n");
                fclose($verify_file);
                
                send_verification($user, $verify_key, false, $email);
            }
        }
    } else {
        if (!file_exists(user_data_file($user, 'verification'))) {
            $verify_file = fopen(user_data_file($user, 'verification'), "w");
            fwrite($verify_file, "verified = \"true\"\n");
            fclose($verify_file);
            chmod(user_data_file($user, 'verification'), 0777);
        }
    }
    
    $account_file = fopen(user_data_file($user), "w");
    
    fwrite($account_file, ";Password\n");
    fwrite($account_file, "password_hash = $hash_password\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Salt\n");
    fwrite($account_file, "salt = $salt\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Role\n");
    fwrite($account_file, "role = $role\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Email\n");
    fwrite($account_file, "email = $email\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Real name\n");
    fwrite($account_file, "realname = \"$realname\"\n");
    
    fclose($account_file);
    
    chmod(user_data_file($user), 0777);
}

function change_account_pref($user, $pref) {
    if (!file_exists(user_data_path($user))) {
        return;
    }
    
    $file = user_data_file($user, 'preferences');
    
    $pref_file = fopen($file, "w");
    fwrite($pref_file, $pref);
    fclose($pref_file);
    
    chmod($file, 0777);
}

function change_account_settings($user, $role, $email, $realname) {
    $user_file = user_data_file($user);
    
    if(!file_exists($user_file)) {
        return;
    }
    
    foreach (glob(user_data_path($user) . "email-*") as $filename) {
        unlink ($filename);
    }
    $email_hash = md5($email);
    $email_hash_file = fopen(user_data_dat_file($user, 'email-' . $email_hash), "w");
    fwrite($email_hash_file, '');
    fclose($email_hash_file);
    chmod(user_data_dat_file($user, 'email-' . $email_hash), 0777);
    
    $hash_password = user('password_hash', $user);
    $user_salt = user('salt', $user);
    
    $account_file = fopen($user_file, "w");
    
    fwrite($account_file, ";Password\n");
    fwrite($account_file, "password_hash = $hash_password\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Salt\n");
    fwrite($account_file, "salt = $user_salt\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Role\n");
    fwrite($account_file, "role = $role\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Email\n");
    fwrite($account_file, "email = $email\n");
    
    fwrite($account_file, "\n");
    fwrite($account_file, ";Real name\n");
    fwrite($account_file, "realname = \"$realname\"\n");
    
    fclose($account_file);
    
    chmod($user_file, 0777);
}

/*
* PBKDF2 key derivation function as defined by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
* $algorithm - The hash algorithm to use. Recommended: SHA256
* $password - The password.
* $salt - A salt that is unique to the password.
* $count - Iteration count. Higher is better, but slower. Recommended: At least 1024.
* $key_length - The length of the derived key in bytes.
* $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
* Returns: A $key_length-byte key derived from the password and salt.
*
* Test vectors can be found here: https://www.ietf.org/rfc/rfc6070.txt
*
* This implementation of PBKDF2 was originally created by defuse.ca
* With improvements by variations-of-shadow.com
*/
function pbkdf2($algorithm, $password, $salt, $count, $key_length, $raw_output = false) {
    $algorithm = strtolower($algorithm);
    if(!in_array($algorithm, hash_algos(), true))
        die('PBKDF2 ERROR: Invalid hash algorithm.');
    if($count <= 0 || $key_length <= 0)
        die('PBKDF2 ERROR: Invalid parameters.');

    $hash_length = strlen(hash($algorithm, "", true));
    $block_count = ceil($key_length / $hash_length);

    $output = "";
    for($i = 1; $i <= $block_count; $i++) {
        // $i encoded as 4 bytes, big endian.
        $last = $salt . pack("N", $i);
        // first iteration
        $last = $xorsum = hash_hmac($algorithm, $last, $password, true);
        // perform the other $count - 1 iterations
        for ($j = 1; $j < $count; $j++) {
            $xorsum ^= ($last = hash_hmac($algorithm, $last, $password, true));
        }
        $output .= $xorsum;
    }

    if($raw_output)
        return substr($output, 0, $key_length);
    else
        return bin2hex(substr($output, 0, $key_length));
}

/*
* Create a session
* $user = Username of account attempting to log in to
* $pass = Password given by the user attempting to log into the account
*/
function session($user, $pass, $redirect, $str = null, $remember_me = false) {
    $user_file = user_data_file($user);
    
    $is_verified = true;
    if (file_exists(user_data_file($user, 'verification'))) {
        $verify_data = parse_ini_file(user_data_file($user, 'verification'));
        if (!($verify_data['verified'] === 'true')) {
            $is_verified = false;
            // No longer make it so verification is necessary to login
            // return $str = 'Account not verified.<br/>Never received a verification email? <a style="color:white;text-decoration:underline;" href="'.site_url().'resend-verification">Resend verification</a>';
        }
    } else {
        return $str = 'Verification data missing: please <a style="color:white;text-decoration:underline;" href="'.site_url().'contact-us">contact us</a> to have this problem fixed';
    }
    
    if(!file_exists($user_file)) {
        return $str = 'The username/email or password is incorrect.';
    }
    
    $user_pass_hash = user('password_hash', $user);
    $user_salt = user('salt', $user);
    
    $input_pass_hash = pbkdf2('SHA256', $pass, $user_salt, 1024, 64 * 8);
    
    if($input_pass_hash === $user_pass_hash) {
        $_SESSION['user'] = $user;
        $_SESSION['user_id'] = user_id($user);
        $_SESSION['user_verified'] = $is_verified;
        user_login_history($user, true);
		
		if ($remember_me) {
			$new_token = login_regenerate_token($user);
            $token_file = user_data_dat_file($user, 'email-' . md5(user('email', $user)));
			file_put_contents($token_file, $new_token);
			setcookie('login', $user . ';' . $new_token,time()+3600*24*30);
		}
		
        session_regenerate_id(true);
        header('location: ' . $redirect);
    }
    else {
        return $str = 'The username/email or password is incorrect.';
    }
}

function login_regenerate_token($user) {
	$user_salt = user('salt', $user);
    $token = pbkdf2('SHA256', time() + rand (1, 10000), $user_salt, 1024, 64 * 8);
	return $token;
}

function user_logout($log_logout, $continue = null) {
	if ($log_logout) {
		$user = $_SESSION['user'];
		user_login_history($user, false);
	}
    
    session_unset();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
	}
	if (isset($_COOKIE['login'])) {
		unset($_COOKIE['login']);
		setcookie('login', '', time()-3600,'/');
	}
	
    session_destroy();
    
    if (empty($continue)) {
        header('location: ' . site_url());
    } else {
        header('location: ' . $continue);
    }
}

function next_user_id() {
    $fp = fopen('config/next_user_id.dat', "r+");
    
    if (flock($fp, LOCK_EX)) {
        // Read current value of the counter and increment
        $cntr = fread($fp, 80);
        $cntr = intval($cntr);

        // Write new value to the file
        ftruncate($fp, 0);
        fseek($fp, 0, SEEK_SET);
        fwrite($fp, $cntr + 1);
        flock($fp, LOCK_UN);
        
        return $cntr;
    } else {
        throw new Exception('Could not lock.');
    }
    fclose($fp);
}

/**
 * Translates a number to a short alhanumeric version
 *
 * Translated any number up to 9007199254740992
 * to a shorter version in letters e.g.:
 * 9007199254740989 --> PpQXn7COf
 *
 * specifiying the second argument true, it will
 * translate back e.g.:
 * PpQXn7COf --> 9007199254740989
 *
 * this function is based on any2dec && dec2any by
 * fragmer[at]mail[dot]ru
 * see: http://nl3.php.net/manual/en/function.base-convert.php#52450
 *
 * If you want the alphaID to be at least 3 letter long, use the
 * $pad_up = 3 argument
 *
 * In most cases this is better than totally random ID generators
 * because this can easily avoid duplicate ID's.
 * For example if you correlate the alpha ID to an auto incrementing ID
 * in your database, you're done.
 *
 * The reverse is done because it makes it slightly more cryptic,
 * but it also makes it easier to spread lots of IDs in different
 * directories on your filesystem. Example:
 * $part1 = substr($alpha_id,0,1);
 * $part2 = substr($alpha_id,1,1);
 * $part3 = substr($alpha_id,2,strlen($alpha_id));
 * $destindir = "/".$part1."/".$part2."/".$part3;
 * // by reversing, directories are more evenly spread out. The
 * // first 26 directories already occupy 26 main levels
 *
 * more info on limitation:
 * - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
 *
 * if you really need this for bigger numbers you probably have to look
 * at things like: http://theserverpages.com/php/manual/en/ref.bc.php
 * or: http://theserverpages.com/php/manual/en/ref.gmp.php
 * but I haven't really dugg into this. If you have more info on those
 * matters feel free to leave a comment.
 *
 * The following code block can be utilized by PEAR's Testing_DocTest
 * <code>
 * // Input //
 * $number_in = 2188847690240;
 * $alpha_in  = "SpQXn7Cb";
 *
 * // Execute //
 * $alpha_out  = alphaID($number_in, false, 8);
 * $number_out = alphaID($alpha_in, true, 8);
 *
 * if ($number_in != $number_out) {
 *   echo "Conversion failure, ".$alpha_in." returns ".$number_out." instead of the ";
 *   echo "desired: ".$number_in."\n";
 * }
 * if ($alpha_in != $alpha_out) {
 *   echo "Conversion failure, ".$number_in." returns ".$alpha_out." instead of the ";
 *   echo "desired: ".$alpha_in."\n";
 * }
 *
 * // Show //
 * echo $number_out." => ".$alpha_out."\n";
 * echo $alpha_in." => ".$number_out."\n";
 * echo alphaID(238328, false)." => ".alphaID(alphaID(238328, false), true)."\n";
 *
 * // expects:
 * // 2188847690240 => SpQXn7Cb
 * // SpQXn7Cb => 2188847690240
 * // aaab => 238328
 *
 * </code>
 *
 * @author  Kevin van Zonneveld &lt;kevin@vanzonneveld.net>
 * @author  Simon Franz
 * @author  Deadfish
 * @author  SK83RJOSH
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link    http://kevin.vanzonneveld.net/
 *
 * @param mixed   $in   String or long input to translate
 * @param boolean $to_num  Reverses translation when true
 * @param mixed   $pad_up  Number or boolean padds the result up to a specified length
 * @param string  $pass_key Supplying a password makes it harder to calculate the original ID
 *
 * @return mixed string or long
 */
function alphaID($in, $to_num = false, $pad_up = false, $pass_key = null)
{
  $out   =   '';
  $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $base  = strlen($index);

  if ($pass_key !== null) {
    // Although this function's purpose is to just make the
    // ID short - and not so much secure,
    // with this patch by Simon Franz (http://blog.snaky.org/)
    // you can optionally supply a password to make it harder
    // to calculate the corresponding numeric ID

    for ($n = 0; $n < strlen($index); $n++) {
      $i[] = substr($index, $n, 1);
    }

    $pass_hash = hash('sha256',$pass_key);
    $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);

    for ($n = 0; $n < strlen($index); $n++) {
      $p[] =  substr($pass_hash, $n, 1);
    }

    array_multisort($p, SORT_DESC, $i);
    $index = implode($i);
  }

  if ($to_num) {
    // Digital number  <<--  alphabet letter code
    $len = strlen($in) - 1;

    for ($t = $len; $t >= 0; $t--) {
      $bcp = bcpow($base, $len - $t);
      $out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
    }

    if (is_numeric($pad_up)) {
      $pad_up--;

      if ($pad_up > 0) {
        $out -= pow($base, $pad_up);
      }
    }
  } else {
    // Digital number  -->>  alphabet letter code
    if (is_numeric($pad_up)) {
      $pad_up--;

      if ($pad_up > 0) {
        $in += pow($base, $pad_up);
      }
    }

    for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
      $bcp = bcpow($base, $t);
      $a   = floor($in / $bcp) % $base;
      $out = $out . substr($index, $a, 1);
      $in  = $in - ($a * $bcp);
    }
  }

  return $out;
}
?>