<!DOCTYPE html>
<html>
<head>
	<title>Főoldal</title>
	<link rel='stylesheet' type='text/css' href='css/main.css'>
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/
		include("packages/messageSystem/messageSystem.php");
		require_once("essential/antiHackingSystem.php");
		$messageSystem = new messageSystem('aratodana');
		$messageSystem->getIncludes();
	
		if(isset($_GET['username']))	$username = antiHackingSystem::testString($_GET['username']);
		else 							$username = 'aratodana';

		echo $messageSystem->head();
	?>
</head>
<body>
	<?php
		include("site_parts/menu/menu.php");
		echo $messageSystem->getContactsOfUser($username);
		echo $messageSystem->getGuestMessageForm($username);
		//include("site_parts/footer.php")
	?>
</body>
</html>