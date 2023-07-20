<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="./static/index.css">
	<title>Home - Kaamdaar</title>
</head>
<body>

	<?php
		require_once "./constants.php";
    	require_once ROOT_DIR . "/views/k_auth.php";

		putenv("MAP_TOKEN=pk.eyJ1Ijoia2FhbWRhYXIiLCJhIjoiY2wwNWhwNjl5MDhjMjNjbnoxajMxOXExMCJ9.KDuSWfD7invXCiRTg_WMfg");
		putenv("FIREBASE_AUTH_TOKEN=AIzaSyD9-kR5IW-5shei47uNPaWXbVs8wM8X40A");

		if(already_logged_in())
		{
			session_start();
			$_SESSION['user_phone'] = $_COOKIE['user_phone'];
			$_SESSION['user_id'] = $_COOKIE['user_id'];
			$_SESSION['user_image'] = $_COOKIE['user_image'];

			header("location:views/profile.php");
		}
		else
			header("location:views/login.php");
	?>

</body>
</html>