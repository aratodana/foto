<!DOCTYPE html>
<html>
<head>
	<title>Bejelentkezés</title>
	<link rel='stylesheet' type='text/css' href='css/main.css'>
	<?php
		/*
		This script created by Arató Dániel
		Version: 1.0.0.1
		*/
		require_once("packages/loginRegisterSystem/loginRegisterSystem.php");
		$loginRegisterSystem = new loginRegisterSystem();
		$loginRegisterSystem->head();
	?>
</head>
<body>
	<?php
		include("site_parts/menu/menu.php");
		echo $loginRegisterSystem->getLoginRegisterForm();
		//include("site_parts/footer.php");
	?>
</body>
</html>