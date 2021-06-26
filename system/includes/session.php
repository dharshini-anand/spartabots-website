<?php

$session_httponly = true;

if (in_array('sha512', hash_algos())) {
  @ini_set('session.hash_function', 'sha512'); // Set hash
}
ini_set('session.hash_bits_per_character', 5); // hash bits per character (4-6)

// Force the session to only use cookies, not URL variables.
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);

$cookieParams = session_get_cookie_params();
session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, true);

session_start();

if(isset($_COOKIE['login'])) {
	$login_auth_log = ";\n";
	$login = $_COOKIE['login'];
	$login_data = explode(';', urldecode($login));
	if (count($login_data) == 2) {
		$user = $login_data[0];
		$token = $login_data[1];
		$login_auth_log .= "Received:\n";
		$login_auth_log .= "  - User: $user\n";
		$login_auth_log .= "  - Token: $token\n";
		
		if (file_exists(user_data_file($user))) {
			$token_file = user_data_dat_file($user, 'email-' . md5(user('email', $user)));
			$real_token = file_get_contents($token_file);
			if ($token === $real_token) {
				$login_auth_log .= "Token auth accepted\n";
				$new_token = login_regenerate_token($user);
				setcookie('login', $user . ';' . $new_token,time()+3600*24*30,'/');
				file_put_contents($token_file, $new_token);
			} else {
				$login_auth_log .= "Token mismatch\n";
                session_unset();
                session_destroy();
                unset($_COOKIE['login']);
                setcookie('login', '', time()-3600,'/');
			}
		} else {
			$login_auth_log .= "User does not exist\n";
			session_unset();
			session_destroy();
			unset($_COOKIE['login']);
			setcookie('login', '', time()-3600,'/');
		}
	} else {
		$login_auth_log .= "Count not 0\n";
        session_unset();
        session_destroy();
        unset($_COOKIE['login']);
        setcookie('login', '', time()-3600,'/');
	}
	$login_auth_log .= ";\n";
	/*
	$fh = fopen('config/login_auth.log', 'a');
	fwrite($fh, $login_auth_log);
	fclose($fh);*/
}

// Function to get the client ip address
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function login() {
	
	if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
		return true;
	}
	else {
		return false;
	}

}