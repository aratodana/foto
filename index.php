<!DOCTYPE html>
<html>
<head>
	<title>Főoldal</title>
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/
		include("packages/imageListingSystem/imageListingSystem.php");
		require_once("essential/antiHackingSystem.php");
		$imageListingSystem = new imageListingSystem();
		$imageListingSystem->getIncludes();
	
		if(isset($_GET['username']))	$username = antiHackingSystem::testString($_GET['username']);
		else 							$username = 'aratodana';
	?>
</head>
<body>
	<?php
		echo $imageListingSystem->getUserCover($username);
		include("site_parts/menu/menu.php");
		echo $imageListingSystem->getUserAlbums($username);
		//include("site_parts/footer/footer.php")
	?>
</body>
</html>