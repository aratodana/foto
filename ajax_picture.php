
<?php
	/*
	This script created by Arató Dániel
	Version: 1.1.0.1
	*/
	require_once("essential/antiHackingSystem.php");
	
	if(isset($_GET['picture'])) 	$picture = antiHackingSystem::testString($_GET['picture']);
	else 						header("Location: index.php");

	echo "<img src='uploads/fullimages/IMG_$picture.jpg' alt='kép' id='fullImage'>";
?>
