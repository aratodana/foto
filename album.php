<!DOCTYPE html>
<html>
<head>
	<title>Album</title>
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/
		include("packages/imageListingSystem/imageListingSystem.php");
		require_once("essential/antiHackingSystem.php");
		$imageListingSystem = new imageListingSystem();
		$imageListingSystem->getIncludes();
		
		if(isset($_GET['album'])) 	$album = antiHackingSystem::testString($_GET['album']);
		else 						header("Location: index.php");
	?>
</head>
<body>
	<?php
		include("site_parts/menu/menu.php");
		echo $imageListingSystem->getAlbum($album);
		echo $imageListingSystem->getPictureModal();
		include("site_parts/footer/footer.php");
	?>
</body>
</html>