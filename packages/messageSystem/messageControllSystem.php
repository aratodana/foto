<?php
	require_once("essential/antiHackingSystem.php");
	require_once("essential/connector.php");
	require_once("essential/session_starter.php");
	require_once("essential/bootstrapIncludes.php");
/*
This script created by Arató Dániel
Version: 1.1.1.2
=============================================================================================================
                                            _____            _             _ _  _____           _                 
                                           / ____|          | |           | | |/ ____|         | |                
  _ __ ___   ___  ___ ___  __ _  __ _  ___| |     ___  _ __ | |_ _ __ ___ | | | (___  _   _ ___| |_ ___ _ __ ___  
 | '_ ` _ \ / _ \/ __/ __|/ _` |/ _` |/ _ \ |    / _ \| '_ \| __| '__/ _ \| | |\___ \| | | / __| __/ _ \ '_ ` _ \ 
 | | | | | |  __/\__ \__ \ (_| | (_| |  __/ |___| (_) | | | | |_| | | (_) | | |____) | |_| \__ \ ||  __/ | | | | |
 |_| |_| |_|\___||___/___/\__,_|\__, |\___|\_____\___/|_| |_|\__|_|  \___/|_|_|_____/ \__, |___/\__\___|_| |_| |_|
                                 __/ |                                                 __/ |                      
                                |___/                                                 |___/                                        
=============================================================================================================
	This script helps to menage contacts 

	Require includes:
		- essential/antiHackingSystem.php
		- essential/connector.php
		- essential/session_starter-php
=============================================================================================================
	Public members:

	Public Functions:	
		void head():									Starts the current method: refressUserDatas()
		string getContactsControll($userName):			Returs the from for the settings of the user's controll
		void deleteContact($contact):					Delete a contact by id
		void addNewContact($username, $data, $type):	Adds a new contact to user
		void getIncludes()								Prints the import of the require css and js files


	Private Members:
		connection $conn_public:	The connection to the database	
		string $selfPage:			The name of this page

	Private Functions:

=============================================================================================================
*/
	class messageControllSystem
	{
		private $conn_public;
		private $selfPage;

		public function __construct()
		{
			$this->conn_public = connector::getConnect();
			$this->selfPage = basename($_SERVER['PHP_SELF']);
		}

		public function head()
		{
			if(isset($_GET['delete']))												$this->deleteContact(antiHackingSystem::testString($_GET['delete']));
			if(isset($_POST['typeOf']) and isset($_POST['Adress']))					$this->addNewContact($this->username, antiHackingSystem::testString($_POST['Adress']), antiHackingSystem::testString($_POST['typeOf']));
		}

		public function getIncludes()
		{
			echo "<link rel='stylesheet' type='text/css' href='packages/messageSystem/css/messageControllSystem.css'>";
		}

		public function addNewContact($username, $data, $type)
		{
			$sql = "INSERT INTO KONTAKTOK (TULAJ, CIM, TIPUS) VALUES ('$username', '$data', (SELECT ID FROM KONTAKT_TIPUSOK WHERE NEV='$type'));";

			$this->conn_public->query($sql);
		}

		public function deleteContact($contact)
		{
			$sql = "DELETE FROM KONTAKTOK WHERE ID=?";
			$stmt = $this->conn_private->prepare($sql);

			$stmt->bind_param('s', $contact);

			$stmt->execute();
			$result = $stmt->get_result();
			header("Location: $this->selfPage");
		}

		//Set the contacts of user
		public function getContactsControll($userName)
		{
			$query = "SELECT KONTAKTOK.CIM, KONTAKTOK.ID, KONTAKT_TIPUSOK.NEV FROM KONTAKTOK INNER JOIN KONTAKT_TIPUSOK ON KONTAKTOK.TIPUS = KONTAKT_TIPUSOK.ID WHERE TULAJ = ?;";


			$stmt = $this->conn_public->prepare($query);
			$stmt->bind_param('s', $userName);
			$stmt->execute();
			
			$result = $stmt->get_result();
			$s = "<div class='card message_settingsCard'><h1>Elérhetőségek</h1><table class='table'>";
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
							$mail = $row['CIM'];
							$id = $row['ID'];
					$s .=  "<tr><td>" . $mail . "</td><td><a href='$this->selfPage?delete=$id'>Törlés</a></td></tr>";
					$s .= $id;
				}
			}
			$s .= "<div>
						<form action='$this->selfPage' method='post'>
							<input type='text' name='Adress' placeholder='Email cím vagy telefonszám' class='form-control'><br>
							<select name='typeOf' class='form-control'><br>
					";

			
			$sql = "SELECT * FROM KONTAKT_TIPUSOK;";
			$result = $this->conn_public->query($sql);
			if($result->num_rows >0)
			{
				while($row = $result->fetch_assoc())
				{
					$name = $row['NEV'];
					$s .= "<option value='$name'>$name</option>";
				}
			}
			
			$s .= "				</select><br>
							<input type='submit' class='btn btn-primary'>
						</form>
					</div><br>";

			$s .= "</table></div>";
			return $s;
		}

	}

?>