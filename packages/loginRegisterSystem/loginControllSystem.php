<?php
	require_once("essential/antiHackingSystem.php");
	require_once("essential/connector.php");
	require_once("essential/session_starter.php");
	require_once("essential/bootstrapIncludes.php");
/*
This script created by Arató Dániel
Version: 1.1.1.2
=============================================================================================================
                                                                  (                                
 (                         (                   )           (   (  )\ )              )              
 )\      (  (  (           )\               ( /( (         )\  )\(()/( (         ( /(   (     )    
((_) (   )\))( )\   (    (((_)   (    (     )\()))(    (  ((_)((_)/(_)))\ )  (   )\()) ))\   (     
 _   )\ ((_))\((_)  )\ ) )\___   )\   )\ ) (_))/(()\   )\  _   _ (_)) (()/(  )\ (_))/ /((_)  )\  ' 
| | ((_) (()(_)(_) _(_/(((/ __| ((_) _(_/( | |_  ((_) ((_)| | | |/ __| )(_))((_)| |_ (_))  _((_))  
| |/ _ \/ _` | | || ' \))| (__ / _ \| ' \))|  _|| '_|/ _ \| | | |\__ \| || |(_-<|  _|/ -_)| '  \() 
|_|\___/\__, | |_||_||_|  \___|\___/|_||_|  \__||_|  \___/|_| |_||___/ \_, |/__/ \__|\___||_|_|_|  
        |___/                                                          |__/                        
=============================================================================================================
	This script helps to menage contacts 

	Require includes:
		- essential/antiHackingSystem.php
		- essential/connector.php
		- essential/session_starter-php
=============================================================================================================
	Public members:

	Public Functions:
		void head():				Starts the current method: refressUserDatas()
		string getUserSetterForm():	Returs the from for the settings of the user datas
		void refressUserDatas():	Refresses the user datas after the form
		void getIncludes();			Prints the include css and js files


	Private Members:
		connection $conn_public:	The connection to the database	
		string $selfPage:			The name of this page

	Private Functions:

=============================================================================================================
*/
	class loginControllSystem
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
			if(isset($_POST['introdution']) and isset($_POST['fullname']))			$this->refressUserDatas();
		}

		public function getIncludes()
		{
			echo "<link rel='stylesheet' type='text/css' href='packages/loginRegisterSystem/css/loginControllSystem.css'>";
		}

		//Set the users datas
		public function getUserSetterForm($userName)
		{
			$query = "SELECT * FROM FELHASZNALO_PUBLIC WHERE NICKNEV = ?";


			$stmt = $this->conn_public->prepare($query);

			$stmt->bind_param('s', $userName);

			$stmt->execute();
			$result = $stmt->get_result();

			$s = "<div class='card login_settingsCard'><h1>Főbb adatok</h1>";
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				$fullName = $row['TELJES_NEV'];
				$introdution = $row['BEMUTATKOZAS'];
				$s .= "<form action='$this->selfPage' method='post'>
						<input type='text' name='fullname' class='form-control' placeholder='Teljes név' value='$fullName'><br>
						<textarea name='introdution' class='form-control' placeholder='Mutatkozz be! ...'>$introdution</textarea><br>
						<input type='submit' class='btn btn-primary'>
					</form>";
			}
			$s .= "</div>";
			return $s;
		}

		public function refressUserDatas()
		{
			$fullname= antiHackingSystem::testString($_POST['fullname']);
			$introdution= antiHackingSystem::testString($_POST['introdution']);

			$sql = "UPDATE FELHASZNALO_PUBLIC SET TELJES_NEV='$fullname', BEMUTATKOZAS=? WHERE NICKNEV='aratodana';";
			$stmt = $this->conn_private->prepare($query);

			$stmt->bind_param('s', $introdution);

			$stmt->execute();
		}
	
	}

?>