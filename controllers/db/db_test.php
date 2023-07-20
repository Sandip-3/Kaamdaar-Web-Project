<?php 

	require_once "db_kaamdaar.php";
	require_once "/opt/lampp/htdocs/kaamdaar/models/user.php";

	function main($args)
	{
		$db = new KaamdaarDBHandler();
		$db->removeUser(15);
	}

	main(null);

?>