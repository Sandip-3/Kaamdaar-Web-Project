<?php 
	function already_logged_in()
	{
		return isset($_COOKIE['user_phone']) && isset($_COOKIE['user_id']);
	}
?>