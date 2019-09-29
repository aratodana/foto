<?php
	/*
	This script created by Arató Dániel
	Version: 1.0.0.1
	*/
	require_once("packages/messageSystem/messageSystem.php");
	require_once("packages/loginRegisterSystem/loginRegisterSystem.php");

	$loginRegisterSystem = new loginRegisterSystem();
	$loginRegisterSystem->loginGuard();
	$messageSystem = new messageSystem('aratodana');
	$messageSystem->getIncludes();
	if(!isset($_GET['messageId']))	header("Location: index.php");
	$messageId = $_GET['messageId'];
	echo $messageSystem->getMessageModalContent($messageId);
?>