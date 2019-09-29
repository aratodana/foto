<?php
	require_once("essential/antiHackingSystem.php");
	require_once("essential/connector.php");
	require_once("essential/session_starter.php");
	require_once("essential/bootstrapIncludes.php");
/*
This script created by Arató Dániel
Version: 1.1.0.2
=============================================================================================================
___  ___                                 _____           _                 
|  \/  |                                /  ___|         | |                
| .  . | ___  ___ ___  __ _  __ _  ___  \ `--. _   _ ___| |_ ___ _ __ ___  
| |\/| |/ _ \/ __/ __|/ _` |/ _` |/ _ \  `--. \ | | / __| __/ _ \ '_ ` _ \ 
| |  | |  __/\__ \__ \ (_| | (_| |  __/ /\__/ / |_| \__ \ ||  __/ | | | | |
\_|  |_/\___||___/___/\__,_|\__, |\___| \____/ \__, |___/\__\___|_| |_| |_|
                             __/ |              __/ |                      
                            |___/              |___/                                    
=============================================================================================================
	This script helps to menage contacts 

	Require includes:
		- php_back_sites/antiHackingSystem.php
		- php_back_sites/connector.php
		- site_parts/session_starter-php

	Require styleSheets:
		- css/messageSystem.css

	Require database datas:
		Database: photosite_public
			Table: FELHASZNALO_PUBLIC
			Table: KONTAKT_TIPUSOK
			Table: KONTAKTOK
			Table: UZENETEK
=============================================================================================================
	Public members:

	Public Functions:
		void head(): 								catch the post datas, and run sendGuestMessages(), deleteContact(), addNewContact()
		string getContactsOfUser():					returns the box contains the contacts of user
		string getGuestMessageForm(): 				returns the form, witch can send message
		string listMessages(#userName):				returns the messages, written to the user
		string getMessageModal():					returns the modal for the messageSystem
		string getMessageModalContent($messageId):	returns one message, created for ajax calling
		void getIncludes():							prints the import of the css and the js files

	Private Members:
		connection $conn_public: 	database connection
		string $selfPage:			the name of the page (for refressing)


	Private Functions:
		void sendGuestMessage():	adds the message to the database


=============================================================================================================
*/


	class messageSystem
	{
		private $conn_public;
		private $selfPage;
		private $siteOwner;
		
		public function __construct($siteOwner)
		{
			$this->conn_public = connector::getConnect();
			$this->conn_private = connector::getConnect("private");
			$this->selfPage = basename($_SERVER['PHP_SELF']);
			$this->siteOwner = $siteOwner;
		}

		public function head()
		{
			if(isset($_POST['sender']) and isset($_POST['subject']) and isset($_POST['message'])) $this->sendGuestMessage();
		}

		public function getIncludes()
		{
			echo "<link rel='stylesheet' type='text/css' href='packages/messageSystem/css/messageSystem.css'>";
			echo "<script src='packages/messageSystem/javascript/messageSystem.js'></script>";
		}

		public function getContactsOfUser($userName)
		{
			$s = "<div class='card contactOfUser'><h1>Elérhetőségek</h1><table class='table'>";

			$sql = "SELECT * FROM KONTAKTOK INNER JOIN KONTAKT_TIPUSOK ON KONTAKTOK.TIPUS = KONTAKT_TIPUSOK.ID WHERE TULAJ = '$userName' AND PUBLIKUS = 1;";
			$result = $this->conn_public->query($sql);
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					$address = $row['CIM'];
					$icon = "images/icons/" . $row['IKON'];
					//<td><img src='$icon' alt='Email ikon'></td>
					$s .=  "<tr><td>$address</td></tr>";
				}
			}
			$s .= "</table></div>";
			return $s;
		}

		private function sendGuestMessage()
		{
			$sender = antiHackingSystem::testString($_POST['sender']);
			$subject = antiHackingSystem::testString($_POST['subject']);
			$message = antiHackingSystem::testString($_POST['message']);

			$adress = $this->siteOwner;

			$sql = "INSERT INTO UZENETEK (KULDO, CIMZETT, TARGY, SZOVEG) VALUES ('$sender', '$adress','$subject', '$message')";
			$this->conn_public->query($sql);


			$sql = "SELECT EMAIL FROM FELHASZNALO_PRIVATE WHERE NICKNEV = '$adress';";
			$result = $this->conn_private->query($sql);
			if($result->num_rows == 0) die("Hiba");
			$row = $result->fetch_assoc();
			$emailAddress = $row['EMAIL'];

			mail($emailAddress, "Üzenet: $subject", $message);
		}

		public function getGuestMessageForm()
		{
			return " 	<div class='card contactOfUser'>
							<h1>Üzenet írása</h1>
							<form action='$this->selfPage' method='post'>
								<input type='text' name='sender' class='form-control' placeholder='email cím vagy telefonszám'><br>
								<input type='text' name='subject' class='form-control' placeholder='Tárgy'><br>
								<textarea name='message' class='form-control' placeholder='Üzenet szövege'></textarea><br>
								<input type='submit' class='btn btn-primary' name=''>			
							</form>
						</div>";
		}

		public function listMessages($userName)
		{
			$s = "<div class='card contactOfUser'><h1>Üzenetek</h1><table class='table table-hover'>";

			$sql = "SELECT * FROM UZENETEK";
			$result = $this->conn_public->query($sql);
			if($result->num_rows > 0)
			{
				$s .= "<tr><th>Tárgy</th><th>Felado</th><th>Dátum</th></tr>";
				while($row = $result->fetch_assoc())
				{
					$subject = $row['TARGY'];
					$sender =  $row['KULDO'];
					$date =  $row['DATUM'];
					$id = $row['ID'];
					$s .=  "<tr><td>$subject</td><td>$sender</td><td>$date</td><td><button class='btn btn-primary' onclick='loadMessage($id)'>Megtekintés</button></td></tr>";
				}
			}
			$s .= "</table></div>";
			return $s;
		}

		public function getMessageModal()
		{
			$s = "<div class='modal' id='messageModal'>
				  <div class='modal-dialog'>
				    <div class='modal-content' id='messageModalContent'>
				    </div>
				  </div>
				</div>";
			return $s;
		}

		public function getMessageModalContent($messageId)
		{
			$sql = "SELECT * FROM UZENETEK WHERE ID = '$messageId'";
			$result = $this->conn_public->query($sql);
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();

				$subject = $row['TARGY'];
				$date = $row['DATUM'];
				$sender = $row['KULDO'];
				$text = $row['SZOVEG'];

				$s = "	<div class='modal-header'>
				        <h4 class='modal-title'>$subject</h4>
				        <button type='button' class='close' data-dismiss='modal'>&times;</button>
				      </div>

				      <div class='modal-body'>
				        $text;
				      </div>";
			}

			return $s;
		}

	}
?>