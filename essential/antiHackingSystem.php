<?php
/*
This script created by Arató Dániel
Version: 1.1.0.0
=============================================================================================================
  ___        _   _   _   _            _     _               _____           _                 
 / _ \      | | (_) | | | |          | |   (_)             /  ___|         | |                
/ /_\ \_ __ | |_ _  | |_| | __ _  ___| |__  _ _ __   __ _  \ `--. _   _ ___| |_ ___ _ __ ___  
|  _  | '_ \| __| | |  _  |/ _` |/ __| '_ \| | '_ \ / _` |  `--. \ | | / __| __/ _ \ '_ ` _ \ 
| | | | | | | |_| | | | | | (_| | (__| | | | | | | | (_| | /\__/ / |_| \__ \ ||  __/ | | | | |
\_| |_/_| |_|\__|_| \_| |_/\__,_|\___|_| |_|_|_| |_|\__, | \____/ \__, |___/\__\___|_| |_| |_|
                                                     __/ |         __/ |                      
                                                    |___/         |___/                                   
=============================================================================================================
	Simple script protect the site from the XSS attacs
	Protect from: php, javascript, shtml
=============================================================================================================	
	Private members:
		static $evilCodes:	the list of the codes, which the script identificate as attac
		static replaceMent:	the string witch with the script replaces it

	Public Functions:
		string testString(): returns the XSS cleared string
=============================================================================================================
*/
	class antiHackingSystem
	{
		private static $evilCodes = ["<script>(.*)</script>", "<?php (.*) ?>", '<!--(.*)-->', "'(.*) OR (.*)'"];
		private static $replaceMent = "";

		public static function testString($tmp)
		{
			foreach(antiHackingSystem::$evilCodes as $code)
			{
				$tmp = str_replace($code, antiHackingSystem::$replaceMent, $tmp);
			}
			return $tmp;
		}

		public static function testImage($fileName, $i = 0)
		{
			//Watching the fileName
			if($_FILES[$fileName]["tmp_name"][$i] == "" or $_FILES[$fileName]["tmp_name"][$i] == null)	return false;


			//Check is the image an image
			$check = getimagesize($_FILES[$fileName]["tmp_name"][$i]);
			if(!$check)		return false;

			//Check the image size
			if ($_FILES[$fileName]["size"][$i] < 500 or $_FILES[$fileName]["size"][$i] > 5000000)		return false;

			//Check the image filetype
			$imageFileType = strtolower(pathinfo($_FILES[$fileName]["name"][$i],PATHINFO_EXTENSION));
			if($imageFileType != "jpg")																	return false;

			return true;
		}
	}
?>