<!DOCTYPE html>
<html>
<head>
	<title>Album szerkesztése</title>
	
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/

		include("essential/bootstrapIncludes.php");
		require_once("essential/antiHackingSystem.php");
		//require_once("packages/contentCreator/contentCreator.php");
		require_once("packages/imageListingSystem/imageListingSystem.php");
		require_once("packages/imageListingSystem/imageControllSystem.php");
		require_once("packages/loginRegisterSystem/loginRegisterSystem.php");
		$loginRegisterSystem = new loginRegisterSystem();
		$username = $loginRegisterSystem->getUserName();
		$imageControllSystem = new imageControllSystem($username);
		$imageListingSystem = new imageListingSystem();
		$loginRegisterSystem = new loginRegisterSystem();
		$loginRegisterSystem->loginGuard();
		$imageControllSystem->head();
		
		$albumId = 0;
		if(isset($_GET['albumId']))		$albumId = antiHackingSystem::testString($_GET['albumId']);
		else 							header('Location: index.php');
	?>
</head>
<body>
	<a href='controll.php'>Vissza</a>
	<?php
		echo $imageControllSystem->getAlbumPropertySetter($albumId);
		echo $imageControllSystem->getPictureUploadBox($albumId);
		echo "<div class='card settingsCard_big'>" . $imageListingSystem->getAlbum($albumId) . "</div>";
	?>
</body>
</html>