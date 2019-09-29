<!DOCTYPE html>
<html>
<head>
	<title>Vezérlőpult</title>
	<link rel='stylesheet' type='text/css' href='css/main.css'>
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/
		include("essential/bootstrapIncludes.php");
		//require_once("php_back_sites/messageSystem.php");
		//require_once("packages/contentCreator/contentCreator.php");
		require_once("packages/imageListingSystem/imageControllSystem.php");
		//require_once("php_back_sites/imageListingSystem.php");
		require_once("packages/loginRegisterSystem/loginRegisterSystem.php");
		require_once("packages/messageSystem/messageControllSystem.php");
		require_once("packages/loginRegisterSystem/loginControllSystem.php");
		$loginRegisterSystem = new loginRegisterSystem();
		$username = $loginRegisterSystem->getUserName();

		$loginControllSystem = new loginControllSystem();
		$loginControllSystem->getIncludes();
		$messageControllSystem = new messageControllSystem();
		$messageControllSystem->getIncludes();
		//$messageSystem = new messageSystem();
		//$contentCreator = new contentCreator('aratodana');
		$imageControllSystem = new imageControllSystem($username);
		//$imageListingSystem = new imageListingSystem();
		$loginRegisterSystem->loginGuard();
		//$messageSystem->head();
		$loginControllSystem->head();
		$messageControllSystem->head();
		$imageControllSystem->head();
		$imageControllSystem->getIncludes();
	?>
</head>
<body>
	<?php
		include("site_parts/menu/menu.php");
		/*
		if(isset($_GET['contacts']))		echo $contentCreator->getContactsControll('aratodana');
		elseif(isset($_GET['user']))		echo $contentCreator->getUserSetterForm('aratodana');
		else 								echo $contentCreator->getMainMenu();
		echo $contentCreator->getAlbumControll('aratodana');
		*/
		echo $imageControllSystem->getAlbumControll($username);
		echo $messageControllSystem->getContactsControll($username);
		echo $loginControllSystem->getUserSetterForm($username);
		include("site_parts/footer/footer.php");
	?>
</body>
</html>