<?php
	session_start();

	$SESSION_COOKIE_NAMES = ['user_phone', 'user_pass', 'user_id', 'business_id', 'business_name'];

	foreach ($SESSION_COOKIE_NAMES as $name) {
		if (isset($_COOKIE[$name])) 
		{
		    unset($_COOKIE[$name]);
		    setcookie($name, null, -1, '/');
		    setcookie($name, null, -1, 'kaamdaar/views/login.php'); 
		}

		if(isset($_SESSION[$name]))
			unset($_SESSION[$name]);
	}

	header("location:login.php");
?>