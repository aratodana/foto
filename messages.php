<!DOCTYPE html>
<html>
<head>
	<title>Üzenetek</title>
	<link rel='stylesheet' type='text/css' href='css/main.css'>
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/
		include("essential/bootstrapIncludes.php");
		require_once("essential/antiHackingSystem.php");

		if(isset($_GET['username']))	$username = antiHackingSystem::testString($_GET['username']);
		else 							$username = 'aratodana';


		require_once("packages/messageSystem/messageSystem.php");
		require_once("packages/loginRegisterSystem/loginRegisterSystem.php");
		$loginRegisterSystem = new loginRegisterSystem();
		$loginRegisterSystem->getIncludes();
		$messageSystem = new messageSystem($username);
		$messageSystem->getIncludes();
		$loginRegisterSystem->loginGuard();
		
		$username = $loginRegisterSystem->getUserName();
	?>
</head>
<body>
	<?php
		include("site_parts/menu/menu.php");
		echo $messageSystem->getMessageModal();
		echo $messageSystem->listMessages($username);
	?>
</body>
</html>