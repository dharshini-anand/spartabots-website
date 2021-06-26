<?php

// Change this to your timezone
date_default_timezone_set('America/Los_Angeles');

use \Michelf\MarkdownExtra;
use \Suin\RSSWriter\Feed;
use \Suin\RSSWriter\Channel;
use \Suin\RSSWriter\Item;

include 'tweetphp/TweetPHP.php';

$TweetPHP = new TweetPHP(array(
    // The consumer_key, consumer_secret, access_token, access_token_secret values
    // below are super secret, don't let anyone you don't trust see them
    'consumer_key'              => 'twdA08di5GsvUreht3jaSw',
    'consumer_secret'           => 'vtAJpyZXnNnlfEB5rnUKAnP3nHEHe4knZ6aynjyU3QM',
    'access_token'              => '1458220729-GqZGkoaLxqWqwspDLy9hzB9zCppr7dL21BeJcjj',
    'access_token_secret'       => 'QvH7mbNCEWtQ1E45Mp7d3Vnb8wwRocPRMH78ln2GoA',
    'twitter_screen_name'       => 'SpartaBots',
    'tweets_to_display'         => 5,
    'ignore_replies'            => true,
    'ignore_retweets'           => true,
    'twitter_wrap_open'         => '<h3>Latest Tweets</h3><ul class="twitterlist">',
    'twitter_wrap_close'        => '</ul>',
    'tweet_wrap_open'           => '<li><span class="status">',
    'meta_wrap_open'            => '</span><span class="meta"><i class="fa fa-fw fa-twitter"></i> ',
    'meta_wrap_close'           => '</span>',
    'tweet_wrap_close'          => '</li>',
    'error_message'             => 'Oops, our Twitter feed is unavailable right now.',
    'error_link_text'           => 'Follow us on Twitter'
));

// Recursive delete dir and all contents
function rrmdir($dir) { 
  foreach(glob($dir . '/*') as $file) { 
    if(is_dir($file)) rrmdir($file); else unlink($file); 
  } rmdir($dir); 
}

function rglob($dir, $ext = null) {
    $result = array();
    if ($ext != null) {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $filename) {
            if (endsWith($filename, ".$ext"))
                array_push($result, $filename);
        }
    } else {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $filename) {
            array_push($result, $filename);
        }
    }
    return $result;
}

function urlencode2($path) {
    return implode('/', array_map('rawurlencode', explode('/', $path)));
}

// Return Latest Tweets
function latest_tweets() {
    global $TweetPHP;
    return $TweetPHP->get_tweet_list();
}

// Get blog post path. Unsorted. Mostly used on widget.
function get_post_unsorted(){

	static $_cache = array();

	if(empty($_cache)){

		// Get the names of all the posts

		$_cache = glob('content/*/blog/*.md', GLOB_NOSORT);
	}

	return $_cache;
}

// Get blog post with more info about the path. Sorted by filename.
function get_post_sorted(){
	static $tmp = array();
	static $_cache = array();

	if(empty($_cache)){

		// Get the names of all the posts

		$tmp = glob('content/*/blog/*.md', GLOB_NOSORT);
		
		if (is_array($tmp)) {
			foreach($tmp as $file) {
				$_cache[] = pathinfo($file);
			}
		}
		
	}
	
	usort($_cache, "sortfile");
	
	return $_cache;
}

// Get static page path. Unsorted. 
function get_static_pages(){

	static $_cache = array();

	if(empty($_cache)){

		// Get the names of all the
		// static page.

		$_cache = glob('content/static/*.md', GLOB_NOSORT);
	}

	return $_cache;
}

// Get author bio path. Unsorted. 
function get_author_names(){

	static $_cache = array();

	if(empty($_cache)){

		// Get the names of all the
		// author.

		$_cache = glob('content/*/author.md', GLOB_NOSORT);
	}

	return $_cache;
}

// Get backup file. 
function get_zip_files(){

	static $_cache = array();

	if(empty($_cache)){

		// Get the names of all the
		// zip files.

		$_cache = glob('backup/*.zip');
	}

	return $_cache;
}

// Check year for first page
function frc_check_year($year, $otherYear) {
    if ($year == $otherYear) {
        return '';
    } else {
        return 'hide';
    }
}

// build email message function
function send_no_reply_email($recipient, $subject, $content) {
    $headers  = 'From: Spartabots <spartabots-no-reply@spartabots.org>' . "\r\n";
    $headers .= 'Reply-To: Spartabots <spartabots-no-reply@spartabots.org>' . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n\r\n";
    
    $message  = '<html><body>';
    $message .= '<h1></h1>';
    
    $message .= '<div style="padding:10px; background:#fbfbfb">';
    $message .= '<table style="border-color:#fff; width:100%; border-bottom: 1px solid #ccc; margin-bottom:10px;" cellpadding="10">';
    $message .= '<tr><td><span style="font-size: 18px;font-weight: bold;">Message Notification</span></td>';
    $message .= '<td style="text-align: right;"><span style="color:#24913E;font-family: Georgia, serif;letter-spacing: .15em;font-size: 16px;">SPARTABOTS</span></td></tr>';
    $message .= '</table>';
    
    $message .= '<div style="padding:10px;border:1px solid #e3e3e3;margin-bottom:10px; background: #ffffff">';
    $message .= $content;
    $message .= '</div>';
    $message .= '<small>Message sent to '. htmlspecialchars($recipient) .' from spartabots.org<br/>Do not reply to this email.</small>';
    $message .= '</div>';
    $message .= '</body></html>';
    
    $message = chunk_split(base64_encode($message));
    mail($recipient, $subject, $message, $headers);
}

// Events Meetings Get
function get_events($type) {
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
            $alphaID = alphaID(basename($file_name, '.ini'));
            if (empty($details)) { $details = '<div class="event-item-details" style="color:#898989">No details available</div>'; } else { $details = '<div class="event-item-details"><b>Details:</b><br/>' . $details . '</div>'; };
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
            
            // Abbreviate 'Not available' to n/a
            $start_time_2 = $start_time;
            $end_time_2 = $end_time;
            
            if ($start_time_2 == 'Not available')
                $start_time_2 = 'n/a';
            if ($end_time_2 == 'Not available')
                $end_time_2 = 'n/a';
            
            // Time string 1
            $time_str = $start_time_2;
            if ($start_time_2 == 'n/a' && $end_time_2 == 'n/a') {
                $time_str = 'Not available';
            } else if ($start_time_2 != 'n/a' && $end_time_2 == 'n/a') {
                // Do nothing
            } else {
                $time_str .= ' - ' . $end_time_2;
            }
            
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
            
            if ($has_passed) {
                array_push($past_arr, array(
                    'datetime' => $datetime,
                    'date_str' => $date,
                    'desc' => $desc,
                    'start_time_str' => $start_time,
                    'start_time_str_2' => $start_time_2,
                    'end_time_str' => $end_time,
                    'end_time_str_2' => $end_time_2,
                    'time_str' => $time_str,
                    'time_str_2' => $time_str_2,
                    'time_str_3' => $time_str_3,
                    'details' => $details,
                    'alphaID' => $alphaID
                ));
            } else {
                array_push($future_arr, array(
                    'datetime' => $datetime,
                    'date_str' => $date,
                    'desc' => $desc,
                    'start_time_str' => $start_time,
                    'start_time_str_2' => $start_time_2,
                    'end_time_str' => $end_time,
                    'end_time_str_2' => $end_time_2,
                    'time_str' => $time_str,
                    'time_str_2' => $time_str_2,
                    'time_str_3' => $time_str_3,
                    'details' => $details,
                    'alphaID' => $alphaID
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
        
        echo '<div class="event-list">';
        if (empty($future_arr)) {
            echo "<section class=\"card-shadow\" style=\"padding:10px\">Currently no upcoming $type</section>";
        }
        
        $toggle_str = 'if($(this).closest(\'section\').hasClass(\'event-item-details-hide\')){$(this).html(\'Hide details\');$(this).closest(\'section\').removeClass(\'event-item-details-hide\')}else{$(this).html(\'Show details\');$(this).closest(\'section\').addClass(\'event-item-details-hide\')}';
        foreach ($future_arr as $meeting_data) {
            $datetime = $meeting_data['datetime'];
            $date_str = $meeting_data['date_str'];
            $time_str_3 = $meeting_data['time_str_3'];
            $time_str_2 = $meeting_data['time_str_2'];
            $time_str = $meeting_data['time_str'];
            $name = $meeting_data['desc'];
            if (empty($name)) {
                $name = 'Unamed ' . rtrim($type, 's');
            }
            $details_str = $meeting_data['details'];
            $alphaID = $meeting_data['alphaID'];
            
            $datetime_month_short_name = null;
            $datetime_day_num = null;
            $datetime_day_short_name = null;
            if (isset($datetime)) {
                $datetime_month_short_name = $datetime->format('M');
                $datetime_day_num = $datetime->format('j');
                $datetime_day_short_name = $datetime->format('D');
            } else {
                $datetime_month_short_name = '&nbsp;';
                $datetime_day_num = $date_str;
                $datetime_day_short_name = '&nbsp;';
            }
            
            $signups_link = site_url() . 's/' . $alphaID;
            
            echo <<<EOF
    <section class="event-item card-shadow" data-id="$alphaID">
        <div class="event-item-datetime">
            <div class="event-item-date">
                <div class="event-item-date-month">$datetime_month_short_name</div>
                <div class="event-item-date-day-num">$datetime_day_num</div>
                <div class="event-item-date-day">$datetime_day_short_name</div>
            </div>
            <div class="event-item-time"><i class="fa fa-clock-o" style="margin-right: 5px;"></i>$time_str_2</div>
            
            <a class="event-item-signups-link" href="$signups_link">View signups</a>
        </div>
        <div class="event-item-desc">
            <div class="event-item-desc-datetime"><span style="margin-right:10px">$date_str</span><i class="fa fa-clock-o" style="margin-right: 5px;"></i>$time_str_3</div>
            <h3 class="event-item-name">$name</h3>
            <div class="event-item-details-toggle"onclick="$toggle_str">Hide details</div>
            <div class="clearfix"></div>
            <hr style="margin-top:10px;border-color: #f1f1f1" />
            $details_str
            <a class="event-item-signups-link-2" href="$signups_link">View signups</a>
        </div>
        <div class="clearfix"></div>
    </section>
EOF;
        }
        
        $past_show_str = 'if ($(\'.event-list-past-'.$type.'\').is(\':visible\')){$(\'.event-list-past-'.$type.'\').hide();$(this).html(\'Show past '.$type.'\')}else{$(\'.event-list-past-'.$type.'\').show();$(this).html(\'Hide past '.$type.'\')}';
        $past_show_style = 'color: rgb(133,133,133) !important;background:#f6f6f6;padding:6px 10px;display:inline-block;width:100%;box-sizing:border-box;moz-box-sizing:border-box;';
        echo <<<EOF
        <h3 style="margin-bottom:10px;text-align:center;">
            <a style="$past_show_style" onclick="$past_show_str">Show past $type</a>
            <div class="clearfix"></div>
        </h3>
EOF;
        foreach ($past_arr as $meeting_data) {
            $datetime = $meeting_data['datetime'];
            $date_str = $meeting_data['date_str'];
            $time_str_3 = $meeting_data['time_str_3'];
            $time_str_2 = $meeting_data['time_str_2'];
            $time_str = $meeting_data['time_str'];
            $name = $meeting_data['desc'];
            if (empty($name)) {
                $name = 'Unamed ' . rtrim($type, 's');
            }
            $details_str = $meeting_data['details'];
            $alphaID = $meeting_data['alphaID'];
            
            $datetime_month_short_name = null;
            $datetime_day_num = null;
            $datetime_day_short_name = null;
            if (isset($datetime)) {
                $datetime_month_short_name = $datetime->format('M');
                $datetime_day_num = $datetime->format('j');
                $datetime_day_short_name = $datetime->format('D');
            } else {
                $datetime_month_short_name = '&nbsp;';
                $datetime_day_num = 'TBA';
                $datetime_day_short_name = '&nbsp;';
            }
            
            $signups_link = site_url() . 's/' . $alphaID;
            
            echo <<<EOF
    <section style="display:none" class="event-item card-shadow event-list-past-$type" data-id="$alphaID">
        <div class="event-item-datetime">
            <div class="event-item-date">
                <div class="event-item-date-month">$datetime_month_short_name</div>
                <div class="event-item-date-day-num">$datetime_day_num</div>
                <div class="event-item-date-day">$datetime_day_short_name</div>
            </div>
            <div class="event-item-time"><i class="fa fa-clock-o" style="margin-right: 5px;"></i>$time_str_2</div>
            
            <a class="event-item-signups-link" href="$signups_link">View signups</a>
        </div>
        <div class="event-item-desc">
            <div class="event-item-desc-datetime"><span style="margin-right:10px">$date_str</span><i class="fa fa-clock-o" style="margin-right: 5px;"></i>$time_str_3</div>
            <h3 class="event-item-name">$name</h3>
            <div class="event-item-details-toggle"onclick="$toggle_str">Hide details</div>
            <div class="clearfix"></div>
            <hr style="margin-top:10px;border-color: #f1f1f1" />
            $details_str
            <a class="event-item-signups-link-2" href="$signups_link">View signups</a>
        </div>
        <div class="clearfix"></div>
    </section>
EOF;
        }
        echo '</div>';
        
        if ($files_count == 0) {
            echo '<section class="card-shadow" style="padding:10px">No '. $type .' found</section>';
        }
    }
}

// Docs breadcrumb dropdown function
function get_files_list_breadcrumb_dropdown($file_path, $current_file_path) {
    $path_prefix = $_SERVER['DOCUMENT_ROOT']."documents/".urldecode($file_path);
    $dirs = array_filter(glob("$path_prefix*"), 'is_dir');
    $dirs_count = count($dirs);
    
    $dropdown_str = '<div class="files-list-breadcrumb-dropdown">';
    
    foreach ($dirs as $path) {
        $dir_name = 'null';
        $url = $_SERVER['DOCUMENT_ROOT'] . 'documents/';
        
        if (substr($path, 0, strlen($path_prefix)) == $path_prefix) {
            $dir_name = substr($path, strlen($path_prefix));
        } else { continue; }
        if (substr($path, 0, strlen($url)) == $url) {
            $url = site_url() . 'docs/' . substr($path, strlen($url));
        } else { continue; }
        
        if (startsWith(urldecode(trim($current_file_path, '/')), urldecode(trim($file_path . $dir_name, '/'))))
            $dropdown_str .= '<a href="'.$url.'"><b>'.$dir_name.'</b></a>';
        else
            $dropdown_str .= '<a href="'.$url.'">'.$dir_name.'</a>';
    }
    $dropdown_str .= '</div>';
    return $dropdown_str;
}

// Special "glob" functions
function glob_all_dirs($path) {
    $data = array();
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($files as $file) {
        if (is_dir($file) === true) {
            $data[] = strval($file);
        }
    }
    return $data;
}

function glob_all_files($path) {
    $data = array();
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($files as $file) {
        if (is_file($file) === true) {
            $data[] = strval($file);
        }
    }
    return $data;
}

function file_perms($file, $octal = false) {
    if(!file_exists($file)) return false;
    $perms = fileperms($file);
    $cut = $octal ? 2 : 3;
    return substr(decoct($perms), $cut);
}

function getFileType($file, $name = false) {
    if (function_exists("file_type")) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (!startsWith($ext, '.')) {
            $ext = '.' . $ext;
        }
        return file_type($ext, $name);
    } else if (function_exists("finfo_file")) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $mime = finfo_file($finfo, $file);
        finfo_close($finfo);
        return $mime;
    } else if (function_exists("mime_content_type")) {
        return mime_content_type($file);
    } else if (!stristr(ini_get("disable_functions"), "shell_exec")) {
        // http://stackoverflow.com/a/134930/1593459
        $file = escapeshellarg($file);
        $mime = shell_exec("file -bi " . $file);
        return $mime;
    } else {
        return false;
    }
}

// Get doc properties
function get_docs_props($file) {
    $icon_class = (filetype($file) == 'dir') ? 'fa-folder-o' : get_file_icon($file);
    $file_base_name = basename($file);
    $mime_type = getFileType($file);
    $type_name = (filetype($file) == 'dir') ? 'File Folder' : getFileType($file, true);
    $file_location = site_url().remove_first(realpath($file), '/home/public/');
    $access_time_formatted = date('F d, Y h:i:s a', fileatime($file));
    $mod_time_formatted = date('F d, Y h:i:s a', filemtime($file));
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $dir_contains_formatted = (filetype($file) == 'dir') ?  '<div class="prop-field"><div class="prop-field-label">Contains</div><div class="prop-field-data">' . count(glob_all_dirs($file)) . ' folders and ' . count(glob_all_files($file)) . ' files</div><div class="clearfix"></div></div>': '';
    $file_perms = file_perms($file, true);

    $file_size_b = filesize($file);
    $file_size_kb = (int) round($file_size_b / 1024);
    if ($file_size_kb == 0)
        $file_size_kb = 1;
    $file_size_kb_formatted = number_format($file_size_kb);
    $file_size_formatted .= $file_size_kb_formatted . ' KB ('.$file_size_b.' bytes)';
    
    $result = '<div class="file-props">';
    $result .= '<div class="file-props-tabs">';
    $result .= '<div class="file-props-tab active" onclick="$(\'.file-props-section\').hide();$(\'[data-props-section=general]\').show();$(\'.file-props-tab\').removeClass(\'active\');$(this).addClass(\'active\')">General</div>';
    if (filetype($file) != 'dir')
        $result .= '<div class="file-props-tab" onclick="$(\'.file-props-section\').hide();$(\'[data-props-section=details]\').show();$(\'.file-props-tab\').removeClass(\'active\');$(this).addClass(\'active\')">Details</div>';
    $result .= '<div class="clearfix"></div>';
    $result .= '</div>';
    $result .= <<<EOF
    <div class="file-props-section" data-props-section="general">
        <div class="prop-field">
            <div class="prop-field-label file-icon"><i class="fa $icon_class"></i></div>
            <div class="prop-field-data file-name"><input type="text" readonly value="$file_base_name" /></div>
            <div class="clearfix"></div>
        </div>
        <hr/>
        <div class="prop-field">
            <div class="prop-field-label">Type</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$type_name" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">Location</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$file_location" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">File Size</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$file_size_formatted" /></div>
            <div class="clearfix"></div>
        </div>
        $dir_contains_formatted
        <hr/>
        <div class="prop-field">
            <div class="prop-field-label">Accessed</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$access_time_formatted" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">Modified</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$mod_time_formatted" /></div>
            <div class="clearfix"></div>
        </div>
    </div>
EOF;
    if (filetype($file) != 'dir') {
    $result .= <<<EOF
    <div class="file-props-section" data-props-section="details" style="display:none">
        <div class="prop-field">
            <div class="prop-field-label">Name</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$file_base_name" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">Type</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$type_name" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">Mime Type</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$mime_type" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">Location</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$file_location" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">File Size</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$file_size_formatted" /></div>
            <div class="clearfix"></div>
        </div>
        <div class="prop-field">
            <div class="prop-field-label">File Perms</div>
            <div class="prop-field-data"><input class="field-data" type="text" readonly value="$file_perms" /></div>
            <div class="clearfix"></div>
        </div>
    </div>
EOF;
    }
    
    $result .= '</div>';
    return $result;
}

// Get docs function
function get_docs($file_path, $search_filter = '') {
    if (!isset($file_path)) {
        $file_path = '';
    }
    if (!isset($search_filter)) {
        $search_filter = '';
    }

    $path_prefix = $_SERVER['DOCUMENT_ROOT'] . "documents/".urldecode($file_path);
    $dirs = ($search_filter == '') ? array_filter(glob("$path_prefix*"), 'is_dir') : glob_all_dirs("$path_prefix");
    $dirs_count = count($dirs);
    $files = ($search_filter == '') ? array_filter(glob("$path_prefix*"), 'is_file') : glob_all_files("$path_prefix");
    $files_count = count($files);
    $results_num = 0;
    $this_dir_name = basename($path_prefix);
    if ($path_prefix == $_SERVER['DOCUMENT_ROOT'] . "documents/") {
        $this_dir_name = 'Documents';
    }
    
    if ($search_filter == '') {
        echo '<div class="files-list-topbar">';
        if (file_exists($path_prefix)) {
            if (!empty($file_path)) {
                $files_breadcrumb = '<div class="files-list-breadcrumb"><div id="breadcrumb-dir-1" class="breadcrumb-dir"><a href="'.site_url().'docs">Documents</a><span class="files-list-crumb-caret" onclick="var event = arguments[0] || window.event; event.stopPropagation();$(\'.breadcrumb-dir:not(#breadcrumb-dir-1)\').each(function(){$(this).removeClass(\'active\')});$(this).closest(\'.breadcrumb-dir\').toggleClass(\'active\');"><i class="fa fa-caret-right"></i><i class="fa fa-caret-down"></i></span>'.get_files_list_breadcrumb_dropdown('', $file_path).'<div class="clearfix"></div></div>';
                $file_path_pieces = explode('/', rtrim($file_path, "/"));
                $file_path_pieces_count = count($file_path_pieces);
                $file_path_breadcrumb_href = site_url() . 'docs/';
                $file_path_breadcrumb_dropdown_path = '';
                $breadcrumb_dir_id = 1;
                
                for ($i = 0; $i < $file_path_pieces_count; ++$i) {
                    $file_path_piece = $file_path_pieces[$i];
                    $file_path_breadcrumb_href .= $file_path_piece . '/';
                    $file_path_breadcrumb_dropdown_path .= $file_path_piece . '/';
                    $breadcrumb_dir_id++;
                    
                    if ($i != $file_path_pieces_count - 1) {
                        $files_breadcrumb .= '<div id="breadcrumb-dir-'.$breadcrumb_dir_id.'" class="breadcrumb-dir"><a href="'.rtrim($file_path_breadcrumb_href,'/').'">'. urldecode($file_path_piece) .'</a>';
                        $files_breadcrumb .= '<span class="files-list-crumb-caret" onclick="var event = arguments[0] || window.event; event.stopPropagation();$(\'.breadcrumb-dir:not(#breadcrumb-dir-'.$breadcrumb_dir_id.')\').each(function(){$(this).removeClass(\'active\')});$(this).closest(\'.breadcrumb-dir\').toggleClass(\'active\');"><i class="fa fa-caret-right"></i><i class="fa fa-caret-down"></i></span><div class="clearfix"></div>';
                        $files_breadcrumb .= get_files_list_breadcrumb_dropdown($file_path_breadcrumb_dropdown_path, $file_path);
                        $files_breadcrumb .= '</div>';
                    } else {
                        $files_breadcrumb .= '<a href="'.rtrim($file_path_breadcrumb_href,'/').'">'. urldecode($file_path_piece) .'</a>';
                    }
                }
                
                $files_breadcrumb .= '<span class="files-list-breadcrumb-search" style="display:none"></span><div class="clearfix"></div></div>';
                echo $files_breadcrumb;
            } else {
                echo '<div class="files-list-breadcrumb"><a href="'.site_url().'docs">Documents</a><span class="files-list-breadcrumb-search" style="display:none"></span><div class="clearfix"></div></div>';
            }
        } else {
            echo '<div class="files-list-breadcrumb"><div id="breadcrumb-dir-1" class="breadcrumb-dir"><a href="'.site_url().'docs">Documents</a><span class="files-list-crumb-caret" onclick="$(\'.breadcrumb-dir:not(#breadcrumb-dir-1)\').each(function(){$(this).removeClass(\'active\')});$(this).closest(\'.breadcrumb-dir\').toggleClass(\'active\');"><i class="fa fa-caret-right"></i><i class="fa fa-caret-down"></i></span>'.get_files_list_breadcrumb_dropdown('', $file_path).'<div class="clearfix"></div></div><span>Not found</span><span class="files-list-breadcrumb-search" style="display:none"></span><div class="clearfix"></div></div>';
        }
        
        $resources_dir_url = "http". (isset($_SERVER['HTTPS']) ? 's' : '') ."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        echo <<<EOF
            <form method="POST" action="/docs/search-docs" onsubmit="getSearchResults();return false" class="files-list-search">
                <input name="search" type="text" value="$search_filter" placeholder="Search $this_dir_name" />
                <i class="fa fa-search"></i>
                <i class="fa fa-times" style="display:none" onclick="searchResultsHide()"></i>
                <input name="file_path" type="hidden" value="$file_path" />
                <input name="dir_name" type="hidden" value="$this_dir_name" />
                <input name="resources_dir_url" type="hidden" value="$resources_dir_url" />
            </form>
            <div class="clearfix"></div>
            <div class="files-list-search-results"></div>
        </div>
EOF;
    }
    
    echo <<<EOF
        <div class="files-list" style="margin-bottom:100px">
            <div class="files-list-head">
                <div class="files-list-row files-list-head-row">
                    <div>File Name</div>
                    <div>File Type</div>
                    <div>File Size</div>
                    <span class="clearfix"></span>
                </div>
            </div>
EOF;
    
    echo '<div class="files-list-dirs files-list-body">';
    if ($dirs_count != 0) {
        foreach ($dirs as $path) {
            $dir_name = str_replace(' ', '&nbsp;', basename($path));
            $url = $_SERVER['DOCUMENT_ROOT'] . 'documents/';
            
            if ($search_filter != '') {
                if (!(stripos($dir_name, $search_filter) !== false)) {
                    // Not found
                    continue;
                }
            }
            
            if (substr($path, 0, strlen($url)) == $url) {
                $url = site_url() . 'docs/' . substr($path, strlen($url));
            } else { continue; }
            
            echo <<<EOF
                <a class="dir files-list-row" href="$url" data-id="$results_num" data-path="$path">
                    <div class="file-name"><span class="fa fa-folder-o file-icon"></span><span class="file-name-text">$dir_name</span></div>
                    <div class="file-type">File&nbsp;Folder</div>
                    <div class="file-size"></div>
                    <span class="clearfix"></span>
                </a>
EOF;
            $results_num++;
        }
    }
    echo '</div><div class="files-list-files files-list-body">';

    if ($files_count == 0) {
        if (file_exists($path_prefix)) {
            echo '<a class="files-list-row"><div>No files found.</div><div></div><div></div><span class="clearfix"></span></a>';
        } else {
            echo '<a class="files-list-row"><div>Folder not found.</div><div></div><div></div><span class="clearfix"></span></a>';
        }
    } else {
        foreach ($files as $path) {
            $file_name = str_replace(' ', '&nbsp;', basename($path));
            $url = $_SERVER['DOCUMENT_ROOT'];
            $icon_class = get_file_icon($file_name);
            
            if ($search_filter != '') {
                if (!(stripos($file_name, $search_filter) !== false)) {
                    // Not found
                    continue;
                }
            }
            
            if (substr($path, 0, strlen($url)) == $url) {
                $url = site_url() . substr($path, strlen($url));
            } else { continue; }
            
            $file_type_name = str_replace(' ', '&nbsp;', getFileType($path, true));
            
            $file_size_b = filesize($path);
            $file_size_kb = (int) round($file_size_b / 1024);
            if ($file_size_kb == 0)
                $file_size_kb = 1;
            $file_size_kb_formatted = number_format($file_size_kb);
            
            echo <<<EOF
                <a class="file files-list-row" href="$url" target="_blank" data-id="$results_num" data-path="$path">
                    <div class="file-name"><span class="fa $icon_class file-icon"></span><span class="file-name-text">$file_name</span></div>
                    <div class="file-type">$file_type_name</div>
                    <div class="file-size">$file_size_kb_formatted&nbsp;KB</div>
                    <span class="clearfix"></span>
                </a>
EOF;
            $results_num++;
        }
    }
    
    if ($search_filter != '' && $results_num == 0) {
        if (file_exists($path_prefix)) {
            echo '<a class="files-list-row"><div>No search results found.</div><div></div><div></div><span class="clearfix"></span></a>';
        } else {
            echo '<a class="files-list-row"><div>Folder not found.</div><div></div><div></div><span class="clearfix"></span></a>';
        }
    }
    
    echo '</div></div>';
}

function get_file_icon($file_name) {
    $icon_class = 'fa-file-o';
    if (endsWith($file_name, '.pdf')) {
        $icon_class = 'fa-file-pdf-o';
    } else if (endsWith($file_name, '.xlsx')) {
        $icon_class = 'fa-file-excel-o';
    } else if (endsWith($file_name, '.docx')) {
        $icon_class = 'fa-file-word-o';
    } else if (endsWith($file_name, '.mp3') || endsWith($file_name, '.wav') || endsWith($file_name, '.ogg')) {
        $icon_class = 'fa-file-audio-o';
    } else if (endsWith($file_name, '.png') || endsWith($file_name, '.jpg') || endsWith($file_name, '.bmp') || endsWith($file_name, '.tif') || endsWith($file_name, '.gif')) {
        $icon_class = 'fa-file-image-o';
    } else if (endsWith($file_name, '.txt') || endsWith($file_name, '.log') || endsWith($file_name, '.rtf')) {
        $icon_class = 'fa-file-text-o';
    } else if (endsWith($file_name, '.zip') || endsWith($file_name, '.7z') || endsWith($file_name, '.rar')) {
        $icon_class = 'fa-file-archive-o';
    } else if (endsWith($file_name, '.mp4') || endsWith($file_name, '.mov') || endsWith($file_name, '.avi')) {
        $icon_class = 'fa-file-video-o';
    } else if (endsWith($file_name, '.pptx')) {
        $icon_class = 'fa-file-powerpoint-o';
    } else if (endsWith($file_name, '.java') || endsWith($file_name, '.php') || endsWith($file_name, '.avi')) {
        $icon_class = 'fa-file-code-o';
    }
    return $icon_class;
}

// usort function. Sort by filename.
function sortfile($a, $b) {
	return $a['filename'] == $b['filename'] ? 0 : ( $a['filename'] < $b['filename'] ) ? 1 : -1;
}

// usort function. Sort by date.
function sortdate($a, $b) {
	return $a->date == $b->date ? 0 : ( $a->date < $b->date ) ? 1 : -1;
}

// Return markdown transformation
function markdown_transform($content) {
    return MarkdownExtra::defaultTransform($content);
}

// Return blog posts. 
function get_posts($posts, $page = 1, $perpage = 0){
		
	if(empty($posts)) {
		$posts = get_post_sorted();
	}
	
	$tmp = array();
	
	// Extract a specific page with results
	$posts = array_slice($posts, ($page-1) * $perpage, $perpage);
	
	foreach($posts as $index => $v){

		$post = new stdClass;
		
		$filepath = $v['dirname'] . '/' . $v['basename'];

		// Extract the date
		$arr = explode('_', $v['basename']);
		$arr[0] = $v['dirname'] . '/' . $arr[0];
		
		// Replaced string
		$replaced = substr($arr[0], 0,strrpos($arr[0], '/')) . '/';
		
		// Author string
		$str = explode('/', $replaced);
		$author = $str[count($str)-3];
		
		// The post author + author url
		$post->author = $author;
		$post->authorurl = site_url() . 'member/' .  $author;
		
		$dt = str_replace($replaced,'',$arr[0]);
		$t = str_replace('-', '', $dt);
		$time = new DateTime($t);
		$timestamp= $time->format("Y-m-d H:i:s");
		
		// The post date
		$post->date = strtotime($timestamp);
		
		// The archive per day
		$post->archive = site_url(). 'archive/' . date('Y-m-d', $post->date) ;

		// The post URL
		$post->url = site_url().date('Y/m', $post->date).'/'.str_replace('.md','',$arr[2]);
		
		$tag = array();
		$url = array();
		$bc = array();
		
		$t = explode(',', $arr[1]);
		foreach($t as $tt) {
			$tag[] = array($tt, site_url(). 'tag/' . $tt);
		}
		
		foreach($tag as $a) {
			$url[] = '<span><a href="' .  $a[1] . '">'. $a[0] .'</a></span>';
			$bc[] = '<span typeof="v:Breadcrumb"><a property="v:title" rel="v:url" href="' .  $a[1] . '">'. $a[0] .'</a></span>';
		}
		
		$post->tag = implode(', ', $url);
		
		$post->tagb = implode(' » ', $bc);
		
		$post->file = $filepath;

		// Get the contents and convert it to HTML
		$content = MarkdownExtra::defaultTransform(file_get_contents($filepath));

		// Extract the title and body
		$arr = explode('t-->', $content);
		if(isset($arr[1])) {
			$title = str_replace('<!--t','',$arr[0]);
			$title = rtrim(ltrim($title, ' '), ' ');	
			$post->title = $title;
			$post->body = $arr[1];
		}
		else {
			$post->title = 'Untitled: ' . date('l jS \of F Y', $post->date);
			$post->body = $arr[0];
		}
        $post->uid = sha1($post->date . $post->title);

		$tmp[] = $post;
	}

	return $tmp;
}

// Find post by year, month and name, previous, and next.
function find_post($year, $month, $name){

	$posts = get_post_sorted();
	
	foreach ($posts as $index => $v) {
		$url = $v['basename'];
		if( strpos($url, "$year-$month") !== false && strpos($url, $name.'.md') !== false){
		
			// Use the get_posts method to return
			// a properly parsed object

			$ar = get_posts($posts, $index+1,1);
			$nx = get_posts($posts, $index,1);
			$pr = get_posts($posts, $index+2,1);
			
			if ($index == 0) {
				if(isset($pr[0])) {
					return array(
						'current'=> $ar[0],
						'prev'=> $pr[0]
					);
				}
				else {
					return array(
						'current'=> $ar[0],
						'prev'=> null
					);
				}
			}
			elseif (count($posts) == $index+1) {
				return array(
					'current'=> $ar[0],
					'next'=> $nx[0]
				);
			}
			else {
				return array(
					'current'=> $ar[0],
					'next'=> $nx[0],
					'prev'=> $pr[0]
				);
			}
		
		}
	}
}

// Return tag page.
function get_tag($tag, $page, $perpage, $random){

	$posts = get_post_sorted();
	
	if($random === true) {
		shuffle($posts);
	}
	
	$tmp = array();
	
	foreach ($posts as $index => $v) {
		$url = $v['filename'];
		$str = explode('_', $url);
		$mtag = explode(',', $str[1]);
		$etag = explode(',', $tag);
		foreach ($mtag as $t) {
			foreach ($etag as $e) {
				if($t === $e){
					$tmp[] = $v;
				}
			}
		}
	}
	
	if(empty($tmp)) {
		not_found();
	}
	
	return $tmp = get_posts($tmp, $page, $perpage);
	
}

// Return archive page.
function get_archive($req, $page, $perpage){

	$posts = get_post_sorted();
	
	$tmp = array();
	
	foreach ($posts as $index => $v) {
		$url = $v['filename'];
		$str = explode('_', $url);
		if( strpos($str[0], "$req") !== false ){
			$tmp[] = $v;
		}
	}
	
	if(empty($tmp)) {
		not_found();
	}
	
	return $tmp = get_posts($tmp, $page, $perpage);
	
}

// Return posts list on profile.
function get_profile($profile, $page, $perpage){

	$posts = get_post_sorted();
	
	$tmp = array();
	
	foreach ($posts as $index => $v) {
		$url = $v['dirname'];
		$str = explode('/', $url);
		$author = $str[count($str)-2];
		if($profile === $author){
			$tmp[] = $v;
		}
	}
	
	if(empty($tmp)) {
		return;	
	}
	
	return $tmp = get_posts($tmp, $page, $perpage);
	
}

// Return author bio.
function get_bio($author){

	$names = get_author_names();
	
	$user_file = user_data_file($author);
	
	$tmp = array();
	
	if(!empty($names)) {
	
		foreach($names as $index => $v){
			$post = new stdClass;
			
			// Replaced string
			$replaced = substr($v, 0,strrpos($v, '/')) . '/';
			
			// Author string
			$str = explode('/', $replaced);
			$profile = $str[count($str)-2];
			
			if($author === $profile){
				// Profile URL
				$url = str_replace($replaced,'',$v);
				$post->url = site_url() . 'member/' . $profile;
				
				// Get the contents and convert it to HTML
				$content = MarkdownExtra::defaultTransform(file_get_contents($v));
	
				// Extract the title and body
				$arr = explode('t-->', $content);
				if(isset($arr[1])) {		
					$title = str_replace('<!--t','',$arr[0]);
					$title = rtrim(ltrim($title, ' '), ' ');		
					$post->title = $title;
					$post->body = $arr[1];
				}
				else {
					$post->title = $author;
					$post->body = $arr[0];
				}
	
				$tmp[] = $post;
			}
		}
	}
	return $tmp;
}

function default_profile($author) {

	$tmp = array();
	$profile = new stdClass;
	
	$profile->title = $author;
	$profile->body = '<p>This user has not yet created a profile bio.</p>';
	
	return $tmp[] = $profile;
	
}

// Return static page.
function get_static_post($static){

	$posts = get_static_pages();
	
	$tmp = array();

	if(!empty($posts)) {

		foreach($posts as $index => $v){
			if(strpos($v, $static.'.md') !== false){
			
				$post = new stdClass;
				
				// Replaced string
				$replaced = substr($v, 0, strrpos($v, '/')) . '/';
				
				// The static page URL
				$url = str_replace($replaced,'',$v);
				$post->url = site_url() . str_replace('.md','',$url);
				
				$post->file = $v;
				
				// Get the contents and convert it to HTML
				$content = MarkdownExtra::defaultTransform(file_get_contents($v));

				// Extract the title and body
				$arr = explode('t-->', $content);
				if(isset($arr[1])) {
					$title = str_replace('<!--t','',$arr[0]);
					$title = rtrim(ltrim($title, ' '), ' ');		
					$post->title = $title;
					$post->body = $arr[1];
				}
				else {
					$post->title = $static;
					$post->body = $arr[0];
				}

				$tmp[] = $post;
				
			}
		}
	
	}
	
	return $tmp;
	
}

// Return search page. [deprecated, use normal_search() instead]
function get_keyword($keyword, $page, $perpage){
    trigger_error("Deprecated: Function get_keyword() is deprecated.", E_USER_NOTICE);
    
	$posts = get_post_sorted();
	
	$tmp = array();
	
	$words = explode(' ', $keyword);
	
	foreach ($posts as $index => $v) {
		$arr = explode('_', $v['filename']);
		$filter = $arr[1] .' '. $arr[2];
		foreach($words as $word) {
			if(stripos($filter, $word) !== false) {
				$tmp[] = $v;
			}
		}
	}
	
	if(empty($tmp)) {
		return array();
	}
	
	return $tmp = get_posts($tmp, $page, $perpage);
		
}

// Return advanced search posts
function adv_search(
        $keywords_all,
        $required_phrase,
        $keywords_any,
        $keywords_none,
        $required_author,
        $required_tag,
        
        $title_keywords_all,
        $title_required_phrase,
        $title_keywords_any,
        $title_keywords_none,
        $page,
        $perpage
    ){
    
    // Split keywords arrays
    $keywords_all_arr;
    $keywords_any_arr;
    $keywords_none_arr;
    $title_keywords_all_arr;
    $title_keywords_any_arr;
	$title_keywords_none_arr;
    
    // Some variables to prevent searching unecessary things
    $fields_not_empty = false;
    $search_title = false;
    $search_content = false;
    $check_author = false;
    
    // Check empty, split keywords strings, and check what to search
    if (!empty($keywords_all)) {
        $keywords_all_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $keywords_all)));
        $fields_not_empty = true;
        $search_content = true;
    }
    if (!empty($required_phrase)) {
        $fields_not_empty = true;
        $search_content = true;
    }
    if (!empty($keywords_any)) {
        $keywords_any_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $keywords_any)));
        $fields_not_empty = true;
        $search_content = true;
    }
    if (!empty($keywords_none)) {
        $keywords_none_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $keywords_none)));
        $fields_not_empty = true;
        $search_content = true;
    }
    
    if (!empty($required_author)) {
        $fields_not_empty = true;
        $check_author = true;
    }
    if (!empty($required_tag)) {
        $fields_not_empty = true;
    }
    
    if (!empty($title_keywords_all)) {
        $title_keywords_all_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $title_keywords_all)));
        $fields_not_empty = true;
        $search_title = true;
    }
    if (!empty($title_required_phrase)) {
        $fields_not_empty = true;
        $search_title = true;
    }
    if (!empty($title_keywords_any)) {
        $title_keywords_any_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $title_keywords_any)));
        $fields_not_empty = true;
        $search_title = true;
    }
    if (!empty($title_keywords_none)) {
        $title_keywords_none_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $title_keywords_none)));
        $fields_not_empty = true;
        $search_title = true;
    }
    
    if (!$fields_not_empty) {
		return array();
    }
    
    // Posts sorted
	$posts = glob('content/*/blog/*.md', GLOB_NOSORT);
    
    if (!is_array($posts)) {
        return array();
    }
    
    $tmp = array();
    
    foreach($posts as $path) {
        $should_add = false;
        $file = basename($path, ".md");
        
		$arr = explode('_', $file);
        $post_tag = $arr[1];
        $post_title = $arr[2];
        
        if (!empty($required_tag)) { // tag
            if ($post_tag != $required_tag) {
                continue;
            } else {
                $should_add = true;
            }
        }
        
        if ($check_author) { // author
            if (explode('/', $path)[1] != $required_author) {
                continue;
            } else {
                $should_add = true;
            }
        }
        
        if ($search_title) {
            if (!empty($title_keywords_all_arr)) {
                foreach ($title_keywords_all_arr as $keyword) { // title keywords all
                    if (!(stripos($post_title, $keyword) !== false)) {
                        continue 2;
                    }
                }
                $should_add = true;
            }
            if (!empty($title_keywords_any_arr)) {
                foreach ($title_keywords_any_arr as $keyword) { // title keywords any
                    if (stripos($post_title, $keyword) !== false) {
                        $should_add = true;
                        continue;
                    }
                }
            }
            if (!empty($title_keywords_none_arr)) {
                foreach ($title_keywords_none_arr as $keyword) { // title keywords none
                    if (stripos($post_title, $keyword) !== false) {
                        continue 2;
                    }
                }
                $should_add = true;
            }
            if (!empty($title_required_phrase)) { // title required phrase
                if (stripos($post_title, str_replace(' ','-', $title_required_phrase)) !== false) {
                    $should_add = true;
                } else {
                    continue;
                }
            }
        }
        
        if ($search_content) {
            $post_content = file_get_contents($path);
            
            if (!empty($keywords_all_arr)) {
                foreach ($keywords_all_arr as $keyword) { // content keywords all
                    if (!(stripos($post_content, $keyword) !== false)) {
                        continue 2;
                    }
                }
                $should_add = true;
            }
            if (!empty($keywords_any_arr)) {
                foreach ($keywords_any_arr as $keyword) { // content keywords any
                    if (stripos($post_content, $keyword) !== false) {
                        $should_add = true;
                        continue;
                    }
                }
            }
            if (!empty($keywords_none_arr)) {
                foreach ($keywords_none_arr as $keyword) { // content keywords none
                    if (stripos($post_content, $keyword) !== false) {
                        continue 2;
                    }
                }
                $should_add = true;
            }
            if (!empty($required_phrase)) { // content required phrase
                if (!(stripos($post_content, $required_phrase) !== false)) {
                    continue;
                }
                $should_add = true;
            }
        }
        
        if ($should_add) {
            array_push($tmp, pathinfo($path));
        }
    }
	
	if(empty($tmp)) {
		return array();
	}
	
	return $tmp = get_posts($tmp, $page, $perpage);
}

// Return normal search posts
function normal_search($keywords, $page, $perpage){
    if (empty($keywords)) {
		return array();
    }
    
    // Split keywords array
    $keywords_arr = explode(' ', trim(preg_replace('!\s+!', ' ', $keywords)));
    
    // Posts sorted
	$posts = glob('content/*/blog/*.md', GLOB_NOSORT);
    
    if (!is_array($posts)) {
        return array();
    }
    
    $tmp = array();
    
    foreach($posts as $path) {
        $should_add = false;
        $file = basename($path, ".md");
        
		$arr = explode('_', $file);
        $post_tag = $arr[1];
        $post_title = $arr[2];
        $post_content = file_get_contents($path);
        
        foreach ($keywords_arr as $keyword) { // keywords any
            if ($keyword === $post_tag) {
                $should_add = true;
                continue;
            }
            
            if (stripos($post_title, $keyword) !== false) {
                $should_add = true;
                continue;
            }
            
            if (stripos($post_content, $keyword) !== false) {
                $should_add = true;
                continue;
            }
        }
        
        if ($should_add) {
            array_push($tmp, pathinfo($path));
        }
    }
	
	if(empty($tmp)) {
		return array();
	}
	
	return $tmp = get_posts($tmp, $page, $perpage);
}

// Get related posts base on post tag.
function get_related($tag) {
	$perpage = pref('related.count');
	$posts = get_tag(strip_tags($tag), 1, $perpage+1, true);
	$tmp = array();
	$req = $_SERVER['REQUEST_URI'];
	
	foreach ($posts as $post) {
		$url = $post->url;
		if( strpos($url, $req) === false){
			$tmp[] = $post;
		}
	}
	
	$total = count($tmp);
	
	if($total >= 1) {
		
		$i = 1;
		echo '<div class="related"><h4>Related posts</h4><ul>';
		foreach ($tmp as $post) {
			echo '<li title="'.$post->title.'"><a href="' . $post->url . '">' . $post->title . '</a></li>';
			if ($i++ >= $perpage) break;
		}
		echo '</ul></div>';
	}
	
}

// Return post count. Matching $var and $str provided.
function get_count($var, $str) {

	$posts = get_post_sorted();
	
	$tmp = array();
	
	foreach ($posts as $index => $v) {
		$url = $v[$str];
		if( strpos($url, "$var") !== false){
			$tmp[] = $v;
		}
	}
	
	return count($tmp);
	
}

// Return seaarch result count
function keyword_count($keyword) {

	$posts = get_post_sorted();
	
	$tmp = array();
	
	$words = explode(' ', $keyword);
	
	foreach ($posts as $index => $v) {
		$arr = explode('_', $v['filename']);
		$filter = $arr[1] .' '. $arr[2];
		foreach($words as $word) {
			if(strpos($filter, strtolower($word)) !== false) {
				$tmp[] = $v;
			}
		}
	}
	
	$tmp = array_unique($tmp, SORT_REGULAR);
	
	return count($tmp);
	
}

// Return an archive list, categorized by year and month.
function archive_list() {

	$posts = get_post_unsorted();
	$by_year = array();
	$col = array();
	
	if(!empty($posts)) {
	
		foreach($posts as $index => $v){
		
			$arr = explode('_', $v);
			
			// Replaced string
			$str = $arr[0];
			$replaced = substr($str, 0,strrpos($str, '/')) . '/';
			
			$date = str_replace($replaced,'',$arr[0]);
			$data = explode('-', $date);
			$col[] = $data;
			
		}
		
		foreach ($col as $row){
		
			$y = $row['0'];
			$m = $row['1'];
			$by_year[$y][] = $m;

		}
		
		# Most recent year first
		krsort($by_year);
		# Iterate for display
		$script = <<<EOF
	if (this.parentNode.className.indexOf('expanded') > -1){this.parentNode.className = 'collapsed';this.innerHTML = '&#9658;';} else {this.parentNode.className = 'expanded';this.innerHTML = '&#9660;';}
EOF;
		echo <<<EOF
		<style>ul.archivegroup{padding:0;margin:0;}.archivegroup .expanded ul{display:block;}.archivegroup .collapsed ul{display:none;}.archivegroup li.expanded,.archivegroup li.collapsed{list-style:none;}
		</style>
EOF;
		$i = 0; 
		$len = count($by_year);
		foreach ($by_year as $year => $months){
			if ($i == 0) {
				$class = 'expanded';
				$arrow = '&#9660;';
			} 
			else {
				$class = 'collapsed';
				$arrow = '&#9658;';
			}
			$i++;
			
			echo '<ul class="archivegroup">';
			echo '<li class="' . $class . '">';
			echo '<a href="javascript:void(0)" class="toggle" onclick="' . $script . '">' . $arrow . '</a> ';
			echo '<a href="' . site_url() . 'archive/' . $year . '">' . $year . '</a> ';
			echo '<span class="count">(' . count($months) . ')</span>';
			echo '<ul class="month">';

			$by_month = array_count_values($months);
			# Sort the months
			krsort($by_month);
			foreach ($by_month as $month => $count){
				$name = date('F', mktime(0,0,0,$month,1,2010));
				echo '<li class="item"><a href="' . site_url() .  'archive/' . $year . '-' . $month . '">' . $name .  '</a>';
				echo ' <span class="count">(' . $count . ')</span></li>';
			}

			echo '</ul>';
			echo '</li>';
			echo '</ul>';
			
		}
	
	}
	
}

// Return tag cloud.
function tag_cloud() {

	$posts = get_post_unsorted();
	$tags = array();
	
	if(!empty($posts)) {
	
		foreach($posts as $index => $v){
		
			$arr = explode('_', $v);
			
			$data = $arr[1];
			$mtag = explode(',', $data);
			foreach($mtag as $etag) {
				$tags[] = $etag;
			}
			
		}
		
		$tag_collection = array_count_values($tags);
		ksort($tag_collection);
		
		echo '<ul class="taglist">';
		foreach ($tag_collection as $tag => $count){
			echo '<li class="item"><a class="button1" href="' . site_url() . 'tag/' . $tag . '">' . $tag . ' <span class="count">(' . $count . ')</span></a></li>';
		}
		echo '</ul>';
	
	}
	
}

// Helper function to determine whether
// to show the previous buttons
function has_prev($prev){
	if(!empty($prev)) {
		return array(
			'url'=> $prev->url,
			'title'=> $prev->title
		);
	}
}

// Helper function to determine whether
// to show the next buttons
function has_next($next){
	if(!empty($next)) {
		return array(
			'url'=> $next->url,
			'title'=> $next->title
		);
	}
}

// Helper function to determine whether
// to show the pagination buttons
function has_pagination($total, $perpage, $page = 1){
	if(!$total) {
		$total = count(get_post_unsorted());
	}
	return array(
		'prev'=> $page > 1,
		'next'=> $total > $page*$perpage
	);
}

// Get the meta description
function get_description($text) {
	
	$string = explode('</p>', $text);
	$string = preg_replace('/[^A-Za-z0-9 !@#$%^&*(),.-]/u', ' ', strip_tags($string[0] . '</p>'));
	$string = ltrim($string);
	
	if (strlen($string) > 1) {
		return $string;
	}
	else {
		$string = preg_replace('/[^A-Za-z0-9 !@#$%^&*(),.-]/u', ' ', strip_tags($text));
		$string = rtrim(ltrim($string), $string);
		if (strlen($string) < config('description.char')) {
			return $string;
		}
		else {
			$string = substr($string, 0, config('description.char'));
			return $string = substr($string, 0, strrpos($string, ' '));
		}
	}

}

// Get the teaser
function get_teaser($text, $url) {

	$teaserType = pref('teaser.type');
	
	if (strlen(strip_tags($text)) < pref('teaser.char') || $teaserType === 'full') {
		echo $text;
	}
	else {
		$string = preg_replace('/\s\s+/', ' ', strip_tags($text));
		$string = substr($string, 0, pref('teaser.char'));
		$string = substr($string, 0, strrpos($string, ' '));
		$body = $string . '...' . ' <a class="readmore" href="' . $url . '#more">more</a>' ;
		echo '<p>' . $body . '</p>';
	}

}

// Get thumbnail from image and Youtube.
function get_thumbnail($text) {

	if (pref('img.thumbnail') == 'true') {

		$teaserType = pref('teaser.type');

		if (strlen(strip_tags($text)) > pref('teaser.char') && $teaserType === 'trimmed') {

			libxml_use_internal_errors(true);
			$default = config('default.thumbnail');
			$dom = new DOMDocument();
			$dom->loadHtml($text);
			$imgTags = $dom->getElementsByTagName('img');
			$vidTags = $dom->getElementsByTagName('iframe');
			if ($imgTags->length > 0) {
				$imgElement = $imgTags->item(0);
				$imgSource = $imgElement->getAttribute('src');
				return '<div class="thumbnail" style="background-image:url(' . $imgSource . ');"></div>';
			}
			elseif ($vidTags->length > 0) {
				$vidElement = $vidTags->item(0);
				$vidSource = $vidElement->getAttribute('src');
				$fetch = explode("embed/", $vidSource);
				if(isset($fetch[1])) {
					$vidThumb = '//img.youtube.com/vi/' . $fetch[1] . '/default.jpg';
					return '<div class="thumbnail" style="background-image:url(' . $vidThumb . ');"></div>';
				}
			}
			else {
				if (!empty($default)) {
					return '<div class="thumbnail" style="background-image:url(' . $default . ');"></div>';
				}
			}
			
		}
		else {
		
		}
	}
	
}

// Return edit tab on post
function editing_tabs($p) {
	$user = $_SESSION['user'];
	$role = user('role', $user);
	if(isset($p->author)) {
		if ($user === $p->author || $role === 'admin') {
			echo '<div class="edit-tab"><a href="' . $p->url . '">View</a><a href="' . $p->url .'/edit?destination=post">Edit</a></div>';
		}
	}
	else {
		echo '<div class="edit-tab"><a href="' . $p->url . '">View</a><a href="' . $p->url .'/edit?destination=post">Edit</a></div>';
	}
}

// Use base64 encode image to speed up page load time.
function base64_encode_image($filename=string,$filetype=string) {
	if ($filename) {
		$imgbinary = fread(fopen($filename, "r"), filesize($filename));
		return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
	}
}

// Social links
function social(){

	$twitter = config('social.twitter'); 
	$facebook = config('social.facebook'); 
	$google = config('social.google'); 
	$tumblr = config('social.tumblr');
	$rss = site_url() . 'feed/rss';
	
	if (!empty($twitter)) {
		echo '<a href="' . $twitter . '" target="_blank"><span class="fa fa-twitter"></span></a>';
	}
	
	if (!empty($facebook)) {
		echo '<a href="' . $facebook . '" target="_blank"><span class="fa fa-facebook"></span></a>';
	}
	
	if (!empty($google)) {
		echo '<a href="' . $google . '" target="_blank"><span class="fa fa-google-plus"></span></a>';
	}
	
	if (!empty($tumblr)) {
		echo '<a href="' . $tumblr . '" target="_blank"><span class="fa fa-tumblr"></span></a>';
	}
	
	echo '<a href="' . site_url() . 'feed/rss" target="_blank"><span class="fa fa-rss"></span></a>';
	
}

// Copyright
function copyright(){

	$blogcp = blog_copyright();
	return $blogcp;
	
}

// Disqus on post.
function disqus($title=null, $url=null){
    if (startsWith($url, '//')) {
        $url = 'http:' . $url;
        // Just add "http:" and it seems to work (even if this website is SSL). URLs starting with // don't seem to work with Disqus
    }
    
	$comment = config('comment.system');
	$disqus = config('disqus.shortname');
	$script = <<<EOF
	<script type="text/javascript">
		var disqus_shortname = '{$disqus}';
		var disqus_title = '{$title}';
		var disqus_url = '{$url}';
		(function() {
            var prevScript = document.getElementById('disqusScript');
            if (prevScript != null)
                prevScript.parentNode.removeChild(prevScript);
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true; dsq.id = 'disqusScript';
			dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
EOF;
	if (!empty($disqus) && $comment == 'disqus') {
		return $script;
	}
}

// Disqus comment count on teaser
function disqus_count(){
	$comment = config('comment.system');
	$disqus = config('disqus.shortname');
	$script = <<<EOF
	<script type="text/javascript">
		var disqus_shortname = '{$disqus}';
		(function() {
			var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			dsq.src = '//' + disqus_shortname + '.disqus.com/count.js';
			(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
		})();
	</script>
EOF;
	if (!empty($disqus) && $comment == 'disqus') {
		return $script;
	}
}

// Disqus recent comments
function recent_comments(){
	$comment = config('comment.system');
	$disqus = config('disqus.shortname');
	$script = <<<EOF
		<script type="text/javascript">
			var heading ='<h3>Comments</h3>';
			document.write(heading);
		</script>
		<script type="text/javascript" src="//{$disqus}.disqus.com/recent_comments_widget.js?num_items=5&hide_avatars=0&avatar_size=48&excerpt_length=200&hide_mods=0"></script>
		<style>li.dsq-widget-item {border-bottom: 1px solid #ebebeb;margin:0;margin-bottom:10px;padding:0;padding-bottom:10px;}a.dsq-widget-user {font-weight:normal;}img.dsq-widget-avatar {margin-right:10px; }.dsq-widget-comment {display:block;padding-top:5px;}.dsq-widget-comment p {display:block;margin:0;}p.dsq-widget-meta {padding-top:5px;margin:0;}#dsq-combo-widget.grey #dsq-combo-content .dsq-combo-box {background: transparent;}#dsq-combo-widget.grey #dsq-combo-tabs li {background: none repeat scroll 0 0 #DDDDDD;}</style>
EOF;
	if (!empty($disqus) && $comment == 'disqus') {
		return $script;
	}
}

function facebook() {
	$comment = config('comment.system');
	$appid = config('fb.appid');
	$script = <<<EOF
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId={$appid}";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<style>.fb-comments, .fb_iframe_widget span, .fb-comments iframe {width: 100%!important;}</style>
EOF;

	if(!empty($appid) && $comment == 'facebook') {
		return $script;
	}

}

// Google Publisher (Google+ page).
function publisher(){
	$publisher = config('google.publisher');
	if (!empty($publisher)) {
		return $publisher;
	}
}

// Google Analytics
function analytics(){
	$analytics = config('google.analytics.id');
	$script = <<<EOF
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '{$analytics}']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
EOF;
	if (!empty($analytics)) {
		return $script;
	}
}

// Menu
function menu(){
	$menu = config('blog.menu');
	$req = $_SERVER['REQUEST_URI'];
    
    if (!empty($menu)) {
    
        $links = preg_split('/\|(?![^{]*\})/', $menu);
		echo '<ul class="nav" id="nav-menu" style="margin-left: 0px; opacity: 1">';
		
		$i = 0;
		$len = count($links);
        
        foreach($links as $link) {
        
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
            
            $sub = explode('{', $link);
            
            $sub_menu = '';
            if (isset($sub[1])) {
                $sub_links = explode('|', rtrim($sub[1], '}'));
                $sub_menu .= '<ul>';
                foreach($sub_links as $sub_link) {
                    $sub_anc = explode('->', $sub_link);
                    if(isset($sub_anc[0]) && isset($sub_anc[1])) {
                        $sub_menu .= '<li><a href="' . $sub_anc[1] . '">' . $sub_anc[0] . '</a></li>';
                    }
                }
                $sub_menu .= '</ul>';
            }
            
            if (isset($sub[0])) {
                $anc = explode('->', $sub[0]);
                if(isset($anc[0]) && isset($anc[1])) {
                    if(strpos(rtrim($anc[1],'/').'/', site_url()) !== false) {
                        $id = substr($sub[0], strrpos($sub[0], '/')+1 );
                        $file = 'content/static/' . $id . '.md';
                        if(file_exists($file)) {
                            if(strpos($req, $id) !== false){
                                echo '<li class="main-menu-item-'. $i .' ' . $class . ' active"><a href="' . $anc[1] . '"><span class="nav-text">' . $anc[0] . '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                            } else {
                                echo '<li class="main-menu-item-'. $i .' ' . $class . '"><a href="' . $anc[1] . '"><span class="nav-text">' . $anc[0] . '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                            }
                        }
                        else {
                            if (rtrim($anc[1],'/').'/' == site_url()) {
                                if($req == site_path() . '/') {
                                    echo '<li class="main-menu-item-'. $i .' ' . $class . ' active"><a href="' . site_url() . '"><span class="nav-text">' .config('breadcrumb.home'). '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                                } else {
                                    echo '<li class="main-menu-item-'. $i .' ' . $class . '"><a href="' . site_url() . '"><span class="nav-text">' .config('breadcrumb.home'). '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                                }
                            } else {
                                if(strpos($req, $id) !== false){
                                    echo '<li class="main-menu-item-'. $i .' ' . $class . ' active"><a href="' . $anc[1] . '"><span class="nav-text">' . $anc[0] . '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                                } else {
                                    echo '<li class="main-menu-item-'. $i .' ' . $class . '"><a href="' . $anc[1] . '"><span class="nav-text">' . $anc[0] . '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                                }
                            }
                        }
                    } else {
                        echo '<li class="main-menu-item-'. $i .' ' . $class . '"><a href="' . $anc[1] . '"><span class="nav-text">' . $anc[0] . '</span><span class="nav-arrow fa fa-chevron-right"></span><div class="clearfix"></div></a>' . $sub_menu . '</li>';
                    }
                }
            }
        }
		echo '</ul>';
    } else {
		get_menu();
	}
}

// Menu active tab change JavaScript

function menu_script(){
	$menu = config('blog.menu');
	$req = $_SERVER['REQUEST_URI'];
    
    if (!empty($menu)) {
    
        $links = preg_split('/\|(?![^{]*\})/', $menu);
		echo <<<EOF
    function change_active_tab() {
        var request_url = window.location.pathname.substr(1);
        $('#menu ul.nav > li').each(function() {
            $(this).removeClass('active');
        });
        $('#jPM-menu ul.nav > li').each(function() {
            $(this).removeClass('active');
        });
EOF;
		$i = 0;
        foreach($links as $link) {
			$i++;
            $change_tab_action = '$(".main-menu-item-'. $i .'").addClass("active")';
            $sub = explode('{', $link);
            
            if (isset($sub[0])) {
                $anc = explode('->', $sub[0]);
                if(isset($anc[0]) && isset($anc[1])) {
                    if (rtrim($anc[1],'/').'/' == site_url()) {
                        echo <<<EOF
    if ("" === request_url) {
        $change_tab_action;
    }
EOF;
                    } else if(strpos(rtrim($anc[1],'/').'/', site_url()) !== false) {
                        $test = remove_first($anc[1], site_url());
                        echo <<<EOF
    if ("$test" === request_url) {
        $change_tab_action;
    }
EOF;
                    }
                }
            }
        }
		echo <<<EOF
    }
EOF;
    }
}

// Auto generate menu from static page
function get_menu() {

	$posts = get_static_pages();
	$req = $_SERVER['REQUEST_URI'];

	if(!empty($posts)) {

		krsort($posts);
		
		echo '<ul class="nav" id="nav-menu" style="margin-left: 0px; opacity: 1">';
		if($req == site_path() . '/') {
			echo '<li class="item first active"><a href="' . site_url() . '">' .config('breadcrumb.home'). '</a></li>';
		}
		else {
			echo '<li class="item first"><a href="' . site_url() . '">' .config('breadcrumb.home'). '</a></li>';
		}
		
		$i = 0; 
		$len = count($posts);
		
		foreach($posts as $index => $v){
		
				if ($i == $len - 1) {
					$class = 'item last';
				}
				else {
					$class = 'item';
				}
				$i++;
		
			// Replaced string
			$replaced = substr($v, 0, strrpos($v, '/')) . '/';
			$base = str_replace($replaced,'',$v);
			$url = site_url() . str_replace('.md','',$base);
			
			// Get the contents and convert it to HTML
			$content = MarkdownExtra::defaultTransform(file_get_contents($v));

			// Extract the title and body
			$arr = explode('t-->', $content);
			if(isset($arr[1])) {
				$title = str_replace('<!--t','',$arr[0]);
				$title = rtrim(ltrim($title, ' '), ' ');		
			}
			else {
				$title = str_replace('-',' ', str_replace('.md','',$base));
			}
			
			if(strpos($req, str_replace('.md','',$base)) !== false){
				echo '<li class="' . $class . ' active"><a href="' . $url . '">' . ucwords($title) . '</a></li>';
			}
			else {
				echo '<li class="' . $class . '"><a href="' . $url . '">' . ucwords($title) . '</a></li>';
			}
				
		}
		echo '</ul>';
	
	}
	else {
	
		echo '<ul class="nav">';
		if($req == site_path() . '/') {
			echo '<li class="item first active"><a href="' . site_url() . '">' .config('breadcrumb.home'). '</a></li>';
		}
		else {
			echo '<li class="item first"><a href="' . site_url() . '">' .config('breadcrumb.home'). '</a></li>';
		}
		echo '</ul>';
	
	}
	
}

// Search form
function search() {
    $url = site_url() . 'advanced-search';
	echo <<<EOF
	<form class="search-form" method="get" role="search">
		<input type="text" class="search-input" name="search" placeholder="Search the blog">
        <a href="$url" class="adv-search">
            <i class="fa fa-cog"></i>
        </a>
	</form>
EOF;
	if(isset($_GET['search'])) {
		$url = site_url() . 'search/' . $_GET['search']; 
		header ("Location: $url");
	}
}

function menu_search() {
    trigger_error("Deprecated: Function menu_search() is deprecated.", E_USER_NOTICE);
	echo <<<EOF
	<form id="menu-search-form" method="get" role="search">
		<div id="menu-search-form-inner">
			<input type="text" class="search-input" name="search" placeholder="Search the blog">
			<input type="submit" value="Search" class="search-button">
		</div>
	</form>
EOF;
	if(isset($_GET['search'])) {
		$url = site_url() . 'search/' . $_GET['search']; 
		header ("Location: $url");
	}
}

// The not found error
function not_found(){
	error(404, render('404', null, false));
}

// Turn an array of posts into an RSS feed
function generate_rss($posts){
	
	$feed = new Feed();
	$channel = new Channel();
	$rssLength = config('rss.char');
	
	$channel
		->title(blog_title())
		->description(blog_description())
		->url(site_url())
		->appendTo($feed);

	foreach($posts as $p){
	
		if(!empty($rssLength)) {
			if (strlen(strip_tags($p->body)) < config('rss.char')) {
				$string = preg_replace('/\s\s+/', ' ', strip_tags($p->body));
				$body = $string . '...' . ' <a class="readmore" href="' . $p->url . '#more">more</a>' ;
			}
			else {
				$string = preg_replace('/\s\s+/', ' ', strip_tags($p->body));
				$string = substr($string, 0, config('rss.char'));
				$string = substr($string, 0, strrpos($string, ' '));
				$body = $string . '...' . ' <a class="readmore" href="' . $p->url . '#more">more</a>' ;
			}
		}
		else {
			$body = $p->body;
		}
	
		$item = new Item();
		$tags = explode(',', str_replace(' ', '', strip_tags($p->tag)));
		foreach($tags as $tag) {
			$item
				->category($tag, site_url() . 'tag/' . $tag );
		}
		$item
			->title($p->title)
			->pubDate($p->date)
			->description($body)
			->url($p->url)
			->appendTo($channel);
	}
	
	echo $feed;
}

// Return post, archive url. 
function get_path(){
		
	$posts = get_post_sorted();
	
	$tmp = array();
	
	foreach($posts as $index => $v){

		$post = new stdClass;
		
		$filepath = $v['dirname'] . '/' . $v['basename'];

		// Extract the date
		$arr = explode('_', $filepath);
		
		// Replaced string
		$replaced = substr($arr[0], 0,strrpos($arr[0], '/')) . '/';
		
		// Author string
		$str = explode('/', $replaced);
		$author = $str[count($str)-3];
		
		$post->authorurl = site_url() . 'member/' .  $author;
		
		$dt = str_replace($replaced,'',$arr[0]);
		$t = str_replace('-', '', $dt);
		$time = new DateTime($t);
		$timestamp= $time->format("Y-m-d H:i:s");
		
		// The post date
		$post->date = strtotime($timestamp);
		
		// The archive per day
		$post->archiveday = site_url(). 'archive/' . date('Y-m-d', $post->date) ;
		
		// The archive per day
		$post->archivemonth = site_url(). 'archive/' . date('Y-m', $post->date) ;
		
		// The archive per day
		$post->archiveyear = site_url(). 'archive/' . date('Y', $post->date) ;

		// The post URL
		$post->url = site_url().date('Y/m', $post->date).'/'.str_replace('.md','',$arr[2]);

		$tmp[] = $post;
	}

	return $tmp;
}

// Return static page path.
function get_static_path(){

	$posts = get_static_pages();

	$tmp = array();
	
	if(!empty($posts)) {

		foreach($posts as $index => $v){
			
			$post = new stdClass;
			
			// Replaced string
			$replaced = substr($v, 0, strrpos($v, '/')) . '/';
			
			// The static page URL
			$url = str_replace($replaced,'',$v);
			$post->url = site_url() . str_replace('.md','',$url);

			$tmp[] = $post;
				
		}
	
	}
	
	return $tmp;
	
}

// Generate sitemap.xml.
function generate_sitemap($str){
	
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	
	if ($str == 'index') {
	
		echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		echo '<sitemap><loc>' . site_url() . 'sitemap.base.xml</loc></sitemap>';
		echo '<sitemap><loc>' . site_url() . 'sitemap.post.xml</loc></sitemap>';
		echo '<sitemap><loc>' . site_url() . 'sitemap.static.xml</loc></sitemap>';
		echo '<sitemap><loc>' . site_url() . 'sitemap.tag.xml</loc></sitemap>';
		echo '<sitemap><loc>' . site_url() . 'sitemap.archive.xml</loc></sitemap>';
		echo '<sitemap><loc>' . site_url() . 'sitemap.author.xml</loc></sitemap>';		
		echo '</sitemapindex>';
		
	}
	elseif ($str == 'base') {
	
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		echo '<url><loc>' . site_url() . '</loc><priority>1.0</priority></url>';
		echo '</urlset>';
		
	}
	elseif ($str == 'post') {
	
		$posts = get_path();
		
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		foreach($posts as $p) {
			echo '<url><loc>' . $p->url . '</loc><priority>0.5</priority></url>';
		}
		
		echo '</urlset>';
		
	}
	elseif ($str == 'static') {
	
		$posts = get_static_path();
		
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		if(!empty($posts)) {
		
			foreach($posts as $p) {
				echo '<url><loc>' . $p->url . '</loc><priority>0.5</priority></url>';
			}
		
		}
		
		echo '</urlset>';
		
	}
	elseif ($str == 'tag') {
	
		$posts = get_post_unsorted();
		$tags = array();
		
		if(!empty($posts)) {
			foreach($posts as $index => $v){
			
				$arr = explode('_', $v);
				
				$data = $arr[1];
				$mtag = explode(',', $data);
				foreach($mtag as $etag) {
					$tags[] = $etag;
				}
				
			}
			
			foreach($tags as $t) {
				$tag[] = site_url() . 'tag/' . $t;
			}
			
			echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			
			if(isset($tag)) {
			
				$tag = array_unique($tag, SORT_REGULAR);
				
				foreach($tag as $t) {
					echo '<url><loc>' . $t . '</loc><priority>0.5</priority></url>';
				}
			
			}
			
			echo '</urlset>';
		
		}
		
	}
	elseif ($str == 'archive') {
	
		$posts = get_path();
		$day = array();
		$month = array();
		$year = array();
	
		foreach($posts as $p) {
			$day[] = $p->archiveday;
			$month[] = $p->archivemonth;
			$year[] = $p->archiveyear;
			
		}
	
		$day = array_unique($day, SORT_REGULAR);
		$month = array_unique($month, SORT_REGULAR);
		$year = array_unique($year, SORT_REGULAR);
		
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		foreach($day as $d) {
			echo '<url><loc>' . $d . '</loc><priority>0.5</priority></url>';
		}
		
		foreach($month as $m) {
			echo '<url><loc>' . $m . '</loc><priority>0.5</priority></url>';
		}
		
		foreach($year as $y) {
			echo '<url><loc>' . $y . '</loc><priority>0.5</priority></url>';
		}
		
		echo '</urlset>';
		
	}
	elseif ($str == 'author') {
	
		$posts = get_path();
		$author = array();
		
		foreach($posts as $p) {
			$author[] = $p->authorurl;
		}
		
		$author = array_unique($author, SORT_REGULAR);
		
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		foreach($author as $a) {
			echo '<url><loc>' . $a . '</loc><priority>0.5</priority></url>';
		}
		
		echo '</urlset>';
		
	}
	
}

// Function to generate OPML file
function generate_opml(){
	
	$opml_data = array(
		'head' => array(
			'title' => blog_title() . ' OPML File',
			'ownerName' => blog_title(),
			'ownerId' => site_url() 
			),
		'body' => array(
			array(
				'text' => blog_title(),
				'description' => blog_description(),
				'htmlUrl' => site_url(),
				'language' => 'unknown',
				'title' => blog_title(),
				'type' => 'rss',
				'version' => 'RSS2',
				'xmlUrl' => site_url() . 'feed/rss'
				)
			)
		);

	$opml = new OPML($opml_data);
	echo $opml->render();
}

// Turn an array of posts into a JSON
function generate_json($posts){
	return json_encode($posts);
}

// Create Zip files
function Zip($source, $destination, $include_dir = false) {

	if (!extension_loaded('zip') || !file_exists($source)) {
		return false;
	}

	if (file_exists($destination)) {
		unlink ($destination);
	}

	$zip = new ZipArchive();
	
	if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
		return false;
	}

	if (is_dir($source) === true) {

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);

			// Ignore "." and ".." folders
			if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
				continue;

			if (is_dir($file) === true) {
				$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
			}
			else if (is_file($file) === true) {
				$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			}
		}
		
	}
	else if (is_file($source) === true) {
		$zip->addFromString(basename($source), file_get_contents($source));
	}

	return $zip->close();
}

// TRUE if the current page is the front page.
function is_front() {
	$req = $_SERVER['REQUEST_URI'];
	if($req == site_path() . '/') {
		return true;
	}
	else {
		return false;
	}
}

// TRUE if the current page is an index page like frontpage, tag index, archive index and search index.
function is_index() {
	$req = $_SERVER['REQUEST_URI'];
	if(strpos($req, '/archive/') !== false || strpos($req, '/tag/') !== false || strpos($req, '/search/') !== false || $req == site_path() . '/'){
		return true;
	}
	else {
		return false;
	}
}

// TRUE if the current page is the landing page
function is_landing() {
	$req = $_SERVER['REQUEST_URI'];
	if($req == site_path() . '/'){
		return true;
	}
	else {
		return false;
	}
}

// TRUE if the current page is a blog page
function is_blog() {
	$req = $_SERVER['REQUEST_URI'];
    
    $req_2 = remove_first($req, '/');
    $pieces = explode('/', $req_2);
    
	if(startsWith($req, '/archive') || startsWith($req, '/tag') || startsWith($req, '/blog') || startsWith($req, '/search') || startsWith($req, '/advanced-search')){
		return true;
	} else if (count($pieces) == 3 && is_numeric($pieces[0]) && is_numeric($pieces[1])) {
        return true;
    } else {
		return false;
	}
}
    
function is_events_meetings() {
    $req = $_SERVER['REQUEST_URI'];
    
	if(startsWith($req, '/events-meetings') || startsWith($req, '/meetings') || startsWith($req, '/events') || startsWith($req, '/calendar') || startsWith($req, '/s/')){
		return true;
	} else {
		return false;
	}
}

function is_get_involved() {
    $req = $_SERVER['REQUEST_URI'];
    
	if(startsWith($req, '/get-involved') || startsWith($req, '/purchase-request')){
		return true;
	} else {
		return false;
	}
}

function is_media_page() {
    $req = $_SERVER['REQUEST_URI'];
    
	if(startsWith($req, '/media')){
		return true;
	} else {
		return false;
	}
}

function is_resource_page() {
    $req = $_SERVER['REQUEST_URI'];
    
	if(startsWith($req, '/my-account') || startsWith($req, '/my-preferences') || startsWith($req, '/first')){
		return true;
	} else {
		return false;
	}
}

// Return blog title
function blog_title() {
	return config('blog.title');
}

// Return blog tagline
function blog_tagline() {
	return config('blog.tagline');
}

// Return blog description
function blog_description() {
	return config('blog.description');
}

// Return blog copyright
function blog_copyright() {
	return config('blog.copyright');
}

// Return author info
function authorinfo($title=null, $body=null) {
	if (pref('author.info') == 'true') {
        return '<div class="author-info"><h4>About <strong>' . $title . '</strong></h4>' . $body . '</div>';
	}
}

// Remove first
function remove_first($str, $prefix) {
    if (substr($str, 0, strlen($prefix)) == $prefix) {
        $str = substr($str, strlen($prefix));
    }
    return $str;
}

// Starts with
function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

// Ends with
function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

// Return head contents
function head_contents($title, $description, $canonical) {
	$output = '';
	
    $output .= '<meta charset="utf-8" />';
    $output .= '<title id="title">'. $title .'</title>';
    $output .= '<link href="'. site_url() .'favicon.ico?v=3" rel="shortcut icon" />';
    $output .= '<meta content="htmly" name="generator"/>';
    $output .= '<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    $output .= '<meta name="description" content="'. $description .'"/>';
    $output .= '<link rel="sitemap" href="' . site_url() . 'sitemap.xml" />';
    $output .= '<link rel="canonical" href="'. $canonical .'" />';
    $output .= '<link rel="image_src" href="'.site_url().'images/logo.png" />';
    $output .= '<meta property="og:site_name" content="'.site_title().'"/>';
    $output .= '<meta property="og:image" content="'.site_url().'images/logo.png"/> ';
    $output .= '<meta property="og:description" content="'. $description .'"/> ';
    $output .= '<meta property="og:title" content="'. $title .'"/> ';
    $output .= '<meta property="og:url" content="'. current_url() .'"/> ';
    $output .= '<link rel="alternate" type="application/rss+xml" title="'. blog_title() .' Feed" href="' . site_url() . 'feed/rss" />';
	$output .= '<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,400,300,600&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic" type="text/css" media="all">';
    $output .= '<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro%3A300%2C400%2C600%2C700%2C300italic%2C400italic%2C600italic%2C700italic&amp;ver=4.0-alpha" type="text/css" media="all">';
    $output .= '<link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Serif%3A400%2C700%2C400italic%2C700italic&amp;ver=4.0-alpha" type="text/css" media="all">';
    
    $output .= '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>';
    $output .= '<script src="' . site_url() . 'system/resources/jquery.jpanelmenu.min.js"></script>';
    $output .= '<script defer src="' . site_url() . 'system/resources/simple-carousel.min.js"></script>';
    $output .= '<script defer src="' . site_url() . 'system/plugins/contextmenu/contextmenu.js"></script>';
	$output .= '<link rel="stylesheet" href="' . site_url() . 'system/plugins/contextmenu/default.css" type="text/css" media="all">';
	$output .= '<link rel="stylesheet" href="' . site_url() . 'system/plugins/contextmenu/basic.css" type="text/css" media="all">';
    if (startsWith($_SERVER['REQUEST_URI'], '/forums')) {
        $output .= '<link rel="stylesheet" href="' . site_url() . 'themes/logs/css/forum.css" type="text/css" media="all">';
    }
    
	return $output;
}

// Admin toolbar
function admin_navigation() {
	$user = $_SESSION['user'];
	$role = user('role', $user);
	$base = site_url();
	echo '<div id="admin-nav" role="navigation"><ul>';
	echo '<li><a data-pane="adminfront" class="header-2" href="'.$base.'admin">Dashboard</a></li>';
	echo '<li><a class="header">Content Management</a></li>';
	if ($role === 'admin')
        echo '<li><a data-pane="all-posts" href="'.$base.'admin/posts">All posts</a></li>';
	echo '<li><a data-pane="userposts" href="'.$base.'admin/mine">My posts</a></li>';
	echo '<li><a data-pane="addpost" href="'.$base.'add/post">Add post</a></li>';
    echo '<li><a data-pane="addpage" href="'.$base.'add/page">Add page</a></li>';
	if ($role === 'admin') {
        echo '<li><a data-pane="file-upload" href="'.$base.'admin/file-upload">File Upload</a></li>';
        echo '<li><a data-pane="editevents" href="'.$base.'admin/events">Edit Meetings</a></li>';
    }
    echo '<li><a class="header">Users</a></li>';
    echo '<li><a data-pane="myaccount" href="'.$base.'admin/my-account">My Account</a></li>';
	if ($role === 'admin') {
        echo '<li><a data-pane="accounts" href="'.$base.'admin/accounts">Accounts</a></li>';
        echo '<li><a class="header">Website Settings</a></li>';
        echo '<li><a data-pane="sitesettings" href="'.$base.'admin/settings">Edit settings</a></li>';
        echo '<li><a data-pane="editnav" href="'.$base.'admin/edit-nav">Edit Menu</a></li>';
        echo '<li><a data-pane="importfeed" href="'.$base.'admin/import">Import</a></li>';
        echo '<li><a data-pane="backup" href="'.$base.'admin/backup">Backup</a></li>';
    }
		
	echo '</ul></div>';
}

function get_username() {
	$user = $_SESSION['user'];
	return $user;
}
function get_user_role() {
	$user = $_SESSION['user'];
	$role = user('role', $user);
    return $role;
}
function get_verified() {
	return $_SESSION['user_verified'];
}

function get_username_by_email($email) {
    $email_hash_files = user_data_file_glob('email-' . md5($email) . '.dat');
    if (count($email_hash_files) != 0) {
        $username = htmlspecialchars(basename(dirname($email_hash_files[0])));
        return $username;
    }
    return null;
}

function send_discord_email_webhook($sender_email, $recipient_email, $email_subject, $email_content, $webhook_url) {
    $hook_object = json_encode([
        'content' => '',
        "username" => "New Email",
        "tts" => false,
        "embeds" => [[
			'type' => 'rich',
			'description' => '',
			'url' => '',
			'timestamp' => date('c', time()),
			'color' => hexdec('1A453B'),
			'thumbnail' => [
				'url' => 'https://www.spartabots.org/images/spartabots-logo-small.png'
			],
			'footer' => [
				'text' => 'Received'
			],
			'fields' => [
				[
					'name' => 'From',
					'value' => $sender_email,
					'inline' => true
				], [
					'name' => 'To',
					'value' => $recipient_email,
					'inline' => true
				], [
					'name' => 'Subject',
					'value' => $email_subject,
					'inline' => true
				], [
					'name' => 'Content',
					'value' => $email_content,
					'inline' => false
				]
			]
		]]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $webhook_url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $hook_object,
		CURLOPT_HTTPHEADER => [
			'Content-Type: application/json'
		]
    ]);
    curl_exec($ch);
    curl_close($ch);
}