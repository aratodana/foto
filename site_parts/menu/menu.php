<?php
	require_once("packages/loginRegisterSystem/loginRegisterSystem.php");
   require_once("essential/bootstrapIncludes.php");
	$loginRegisterSystem = new loginRegisterSystem();
?>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
   <?php
         if(isset($_GET['username']))   $username = $_GET['username'];
         else                    $username = 'aratodana';

         echo "
               <a class='navbar-brand' href='index.php?username=$username'>Főoldal</a>
               <a class='navbar-brand' href='contact.php?username=$username'>Elérhetőségek</a>
         ";

   		if($loginRegisterSystem->isLoggedIn())
   		{ echo "
   			<a class='navbar-brand' href='controll.php'>Vezérlőpult</a>
   			<a class='navbar-brand' href='messages.php'>Üzenetek</a>
   			<a class='navbar-brand' href='login.php?logout'>Kijelentkezés</a>
   	     ";
         }
   		else
   			{ ?>
   			<a class="navbar-brand" href="login.php">Bejelentkezés</a>
   		<?php }
   ?>
</nav>