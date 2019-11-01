<?php
	require_once("essential/antiHackingSystem.php");
	require_once("essential/connector.php");
	require_once("essential/session_starter.php");
	require_once("essential/bootstrapIncludes.php");

/*
This script created by Arató Dániel
Version: 1.1.0.1
=============================================================================================================
  _                 _         _____            _     _               _____           _                 
 | |               (_)       |  __ \          (_)   | |             / ____|         | |                
 | |     ___   __ _ _ _ __   | |__) |___  __ _ _ ___| |_ ___ _ __  | (___  _   _ ___| |_ ___ _ __ ___  
 | |    / _ \ / _` | | '_ \  |  _  // _ \/ _` | / __| __/ _ \ '__|  \___ \| | | / __| __/ _ \ '_ ` _ \ 
 | |___| (_) | (_| | | | | | | | \ \  __/ (_| | \__ \ ||  __/ |     ____) | |_| \__ \ ||  __/ | | | | |
 |______\___/ \__, |_|_| |_| |_|  \_\___|\__, |_|___/\__\___|_|    |_____/ \__, |___/\__\___|_| |_| |_|
               __/ |                      __/ |                             __/ |                      
              |___/                      |___/                             |___/                       
=============================================================================================================
	Simple login and registering script. It ueses mysql database

	Require includes:
		- php_back_sites/antiHackingSystem.php
		- php_back_sites/connector.php
		- site_parts/session_starter-php

	Require styleSheets:
		- css/loginRegisterSystem.css

	Require database datas:
		Database: phtotosite_private:
			Table: FELHASZNALO_PRIVATE
			Table: REGISTRACIOS_KOD
		Database: photosite_public
			Table: FELHASZNALO_PUBLIC

=============================================================================================================
WARNING: HEAD CAN'T BE ON THE index.php
=============================================================================================================
	Public members:

	Public Functions:
		void head():					catch the Post or GEt datas, and runs: doLogin(), doRegister(), doLogout() or sends to the index.php
		string getUserName():			if user is logged in the usename, else "Guest"
		boolean isLoggedIn():			true if the user is logged in, else false
		boolean isAdmin():				true is the user is admin, else false
		void loginGuard():				if user is not logged in goes to the "index.php", else do nothing
		string getLoginRegisterForm():	writes the login and register form
		void getIncludes():				Prints the import of the css and js files

	Private Members:
		connection $conn_private:	high secuirity database connection
		connection $conn_public: 	database connection
		string $selfPage:			the name of the page (for refressing)

	Private Functions:
		string doLogin():		make login after the Post varibles, if there is an error returns the error message
		string doRegister():	make register after the Post varibles, if there is an error returns the error message
		void doLogout():		log out the logged in user
=============================================================================================================
*/




	class loginRegisterSystem
	{
		private $conn_private;
		private $conn_public;
		private $selfPage;

		public function __construct()
		{
			$this->conn_private = connector::getConnect("private");
			$this->conn_public = connector::getConnect();
			$this->selfPage = basename($_SERVER['PHP_SELF']);
		}

		public function head()
		{
			if(isset($_POST['loginEmail']) and isset($_POST['loginPassword'])) $this->doLogin();
			elseif(isset($_POST['registerEmail']) and isset($_POST['registerNickname']) and isset($_POST['registerPassword1']) and isset($_POST['registerPassword2']) and isset($_POST['registerPassword'])) $this->doRegister();
			elseif(isset($_GET['logout'])) $this->doLogout();

			if($this->isLoggedIn()) header('Location: index.php');
		}

		public function getIncludes()
		{
				echo "<link rel='stylesheet' type='text/css' href='packages/loginRegisterSystem/css/loginRegisterSystem.css'>";
		}

		public function isLoggedIn()
		{
			return isset($_SESSION['username']);
		}

		public function getUserName()
		{
			if($this->isLoggedIn())				return $_SESSION['username'];
			else 								return "Guest";
		}

		public function isAdmin()
		{
			return isset($_SESSION['admin']);
		}

		public function loginGuard()
		{
			if(!$this->isLoggedIn()) header('Location: index.php');
		}

		private function doLogin()
		{
			$email = antiHackingSystem::testString($_POST['loginEmail']);
			$password = antiHackingSystem::testString($_POST['loginPassword']);
			
			$query = "SELECT NICKNEV, ADMIN FROM FELHASZNALO_PRIVATE WHERE (EMAIL = ? OR NICKNEV = ?) AND MD5(?) = JELSZO;";

			$stmt = $this->conn_private->prepare($query);

			$stmt->bind_param('sss', $email, $email, $password);

			$stmt->execute();
			$result = $stmt->get_result();


			
			if($result->num_rows != 1)	return "Hibás felhasználónév vagy jelszó";

			$row= $result->fetch_assoc();
			$username = $row['NICKNEV'];
			$rank = $row['ADMIN'];

			$_SESSION['username'] = $username;
			$_SESSION['admin'] = $rank;
			return "Sikeres Bejelentkezés";
		}

		private function doRegister()
		{
			$email = antiHackingSystem::testString($_POST['registerEmail']);
			$username = antiHackingSystem::testString($_POST['registerNickname']);
			$password1 = antiHackingSystem::testString($_POST['registerPassword1']);
			$password2 = antiHackingSystem::testString($_POST['registerPassword2']);
			$registerCode = antiHackingSystem::testString($_POST['registerPassword']);

			if($password1 != $password2) return "A két jelszó nem egyezik";

			$sql = "SELECT * FROM REGISZTRACIOS_KOD WHERE KOD=?;";
			$stmt = $this->conn_private->prepare($sql);
			$stmt->bind_param('s', $registerCode);

			$stmt->execute();
			$result = $stmt->get_result();

			if($result->num_rows != 1)	return "Hibás a regisztrációs kód";
			
			$sql = "DELETE FROM REGISZTRACIOS_KOD WHERE KOD=?;";
			$stmt = $this->conn_private->prepare($sql);
			$stmt->bind_param('?', $registerCode);
			
			$stmt->execute();
			$result = $stmt->get_result();


			$sql = "INSERT INTO FELHASZNALO_PRIVATE (EMAIL, NICKNEV, JELSZO) VALUES ('$email', '$username', MD5(?))";
			$stmt = $this->conn_private->prepare($sql);
			$stmt->bind_param('s', $password1);

			$stmt->execute();
			

			$sql = "INSERT INTO FELHASZNALO_PUBLIC (NICKNEV, BEMUTATKOZAS) VALUES (?, '')";
			$stmt = $this->conn_private->prepare($sql);
			$stmt->bind_param('s', $username);

			$stmt->execute();
			return "A regisztráció sikeres";
		}

		public function getLoginRegisterForm()
		{
			return  "
				<div id='loginForm' class='card'>
					<form action='$this->selfPage' method='post'>
						<h1>Bejelentkezés</h1>
						<input type='text' name='loginEmail' class='form-control' placeholder='email-cím'><br>
						<input type='password' name='loginPassword' class='form-control' placeholder='jelszó'><br>
						<input type='submit' name='' class='btn btn-primary' value='Bejelentkezés'>
					</form>
					<form action='$this->selfPage' method='post'>
					<h1>Regisztráció</h1>
						<input type='text' name='registerEmail' class='form-control' placeholder='E-mail cím'><br>
						<input type='text' name='registerNickname' class='form-control' placeholder='Felhasználónév'><br>
						<input type='password' name='registerPassword1' class='form-control' placeholder='Jelszó'><br>
						<input type='password' name='registerPassword2' class='form-control' placeholder='Jelszó mégegyszer'><br>
						<input type='password' name='registerPassword' class='form-control' placeholder='Regisztrációs kód'><br>
						<input type='submit' name='' class='btn btn-primary' value='Regisztráció'>
				</form>
				</div>
			";
		}

		private function doLogout()
		{
			unset($_SESSION['username']);
			if($this->isAdmin()) unset($_SESSION['admin']);
		}
	}
?>