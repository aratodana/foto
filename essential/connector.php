<?php
/*
This script created by Arató Dániel
Version: 1.0.1.0
=============================================================================================================
  ,----..                                                         ___                       
 /   /   \                                                      ,--.'|_                     
|   :     :  ,---.        ,---,      ,---,                      |  | :,'   ,---.    __  ,-. 
.   |  ;. / '   ,'\   ,-+-. /  | ,-+-. /  |                     :  : ' :  '   ,'\ ,' ,'/ /| 
.   ; /--` /   /   | ,--.'|'   |,--.'|'   |   ,---.     ,---. .;__,'  /  /   /   |'  | |' | 
;   | ;   .   ; ,. :|   |  ,"' |   |  ,"' |  /     \   /     \|  |   |  .   ; ,. :|  |   ,' 
|   : |   '   | |: :|   | /  | |   | /  | | /    /  | /    / ':__,'| :  '   | |: :'  :  /   
.   | '___'   | .; :|   | |  | |   | |  | |.    ' / |.    ' /   '  : |__'   | .; :|  | '    
'   ; : .'|   :    ||   | |  |/|   | |  |/ '   ;   /|'   ; :__  |  | '.'|   :    |;  : |    
'   | '/  :\   \  / |   | |--' |   | |--'  '   |  / |'   | '.'| ;  :    ;\   \  / |  , ;    
|   :    /  `----'  |   |/     |   |/      |   :    ||   :    : |  ,   /  `----'   ---'     
 \   \ .'           '---'      '---'        \   \  /  \   \  /   ---`-'                     
  `---`                                      `----'    `----'                                                         
=============================================================================================================
	Simple script returns the connect, to help the configuration
=============================================================================================================	
	Private Functions:
		connection getPublic(): return the connection to the public database
		connection getPrivate(): return the connection to the private database

	Public Functions:
		connection getConnect($private = False): if true returns getPublic() else returns getPrivate()
=============================================================================================================
*/
	class connector
	{
		public static function getConnect($arg = "default")
		{
			if($arg == "private")	return connector::getPrivate();
			if($arg == "logs") 		return connector::getLog();
			else 					return connector::getPublic();
		}

		private static function getPrivate()
		{
            $database = "foto_priv";
            $adress = "localhost";
            $username = "";
            $password = "";

			$conn = new mysqli($adress, $username, $password, $database);
			if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);} 
			return $conn;
		}

		private static function getLog()
		{
            $database = "foto_logs";
            $adress = "localhost";
            $username = "";
            $password = "";

			$conn = new mysqli($adress, $username, $password, $database);
			if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);} 
			return $conn;
		}

		private static function getPublic()
		{
            $database = "foto_pub";
            $adress = "localhost";
            $username = "";
            $password = "";

			$conn = new mysqli($adress, $username, $password, $database);
			if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
			return $conn;
		}
	}

?>