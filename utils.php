<?php 
	require_once "constants.php";

	function redirect($url) : void
	{
		header("location:$url.php");
	}

	$static_file_dir = ROOT_DIR . "static/";

	function change_static_file_dir($new_path) : void
	{
		global $static_file_dir;
		$static_file_dir = $new_path;
	}

	function url_for($filename) : string
	{
		return $static_file_dir . "/" . $filename;
	}

	// Source: https://www.php.net/manual/en/function.uniqid.php#120123
	function random_uniqid(string $prefix = '', int $length = 11)
	{
		if(function_exists("random_bytes"))
			$bytes = random_bytes(ceil($length / 2));
		elseif (function_exists("openssl_random_pseudo_bytes")) 
			$bytes = openssl_random_pseudo_bytes(ceil($length / 2));

		return $prefix . substr(bin2hex($bytes), 0, $length);
	}

	function get_user_location($fields=['country', 'region', 'city', 'lat', 'lon'])
	{
		$query = @unserialize(file_get_contents('http://ip-api.com/php/'));

		$result = [];
		if($query)
		{
			foreach($fields as $field)
			{
				$result[$field] = $query[$field];
			}
		}
		return $result;
	}
?>