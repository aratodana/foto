<?
	require_once("essential/antiHackingSystem.php");
	require_once("essential/connector.php");
	require_once("essential/session_starter.php");
	require_once("essential/bootstrapIncludes.php");
/*
This script created by Arató Dániel
Version: 1.1.1.2
=============================================================================================================
                                                                        (                                
                                 (                   )           (   (  )\ )              )              
 (      )       )  (  (     (    )\               ( /( (         )\  )\(()/( (         ( /(   (     )    
 )\    (     ( /(  )\))(   ))\ (((_)   (    (     )\()))(    (  ((_)((_)/(_)))\ )  (   )\()) ))\   (     
((_)   )\  ' )(_))((_))\  /((_))\___   )\   )\ ) (_))/(()\   )\  _   _ (_)) (()/(  )\ (_))/ /((_)  )\  ' 
 (_) _((_)) ((_)_  (()(_)(_)) ((/ __| ((_) _(_/( | |_  ((_) ((_)| | | |/ __| )(_))((_)| |_ (_))  _((_))  
 | || '  \()/ _` |/ _` | / -_) | (__ / _ \| ' \))|  _|| '_|/ _ \| | | |\__ \| || |(_-<|  _|/ -_)| '  \() 
 |_||_|_|_| \__,_|\__, | \___|  \___|\___/|_||_|  \__||_|  \___/|_| |_||___/ \_, |/__/ \__|\___||_|_|_|  
                  |___/                                                      |__/                              
=============================================================================================================
	This script helps to menage contacts 

	Require includes:
		- essential/antiHackingSystem.php
		- essential/connector.php
		- essential/session_starter-php
=============================================================================================================
	Public members:

	Public Functions:
		void head():								Starts the current method: refressUserDatas(),
		string getAlbumControll($userName):			Returns the controllpanel of an album by id
		string getAlbumPropertySetter($albumId):	Returns the setterbox of an album
		void addNewAlbum():							Adds a new empty album
		void getPictureUploadBox($albumId):			Returns the uploadBox
		void uploadNewPicture($albumId)				Uploads image, create thumbnail, and give it to album (by id)
		void getIncludes()							Prints the include css-s and js files



	Private Members:
		connection $conn_public:	The connection to the database	
		string $selfPage:			The name of this page

	Private Functions:

=============================================================================================================
*/

	class imageControllSystem
	{
		private $conn_public;
		private $selfPage;
		private $username;

		public function __construct($username)
		{
			$this->conn_public = connector::getConnect();
			$this->selfPage = basename($_SERVER['PHP_SELF']);
			$this->username = $username;
		}
	

		public function head()
		{
			if(isset($_GET['deleteAlbum']))											$this->deleteAlbum($_GET['deleteAlbum']);
			if(isset($_GET['newAlbum']))											$this->addNewAlbum();
			if(isset($_POST['albumName']) and isset($_POST['introdution']) and isset($_POST['albumId'])) $this->setAlbumProperties($_POST['albumName'], $_POST['introdution'], $_POST['albumId']);
			if(isset($_POST['submit1']) and isset($_POST['albumId']))	$this->uploadNewPicture($_POST['albumId']);
		}

		//Menage albums
		public function getAlbumControll($userName)
		{
			$s = "<div class='card image_settingsCard'><h1>Albumok</h1><table class='table table-hover'>";

			$sql = "SELECT * FROM ALBUM WHERE TULAJ = '$userName'";
			$result = $this->conn_public->query($sql);

				if($result->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
					{
						$albumName = $row['NEV'];
						$id = $row['ID'];
						$s .= "<tr><td>$albumName</td><td><a href='album.php?album=$id'>Megtekintés</a></td><td><a href='albumEditor.php?albumId=$id'>Szerkesztés</a></td><td><a href='$this->selfPage?deleteAlbum=$id'>Törlés</a></td></tr>";
					}
				}


			$s .= "<a href='$this->selfPage?newAlbum'>Album hozzáadása<a></table></div>";
			return $s;
		}

		public function addNewAlbum()
		{
			$sql = "INSERT INTO ALBUM (NEV, TULAJ, LEIRAS) VALUES ('Új album', '$this->username', '')";
			$this->conn_public->query($sql);
		}

		public function getIncludes()
		{
			echo "<link rel='stylesheet' type='text/css' href='packages/imageListingSystem/css/imageControllSystem.css'>";
		}

		public function deleteAlbum($albumId)
		{
			$sql = "DELETE KEP, POZICIOK FROM KEP INNER JOIN POZICIOK ON POZICIOK.KEPID = KEP.ID WHERE ALBUMID=$albumId;";
			$this->conn_public->query($sql);
			$sql = "DELETE FROM ALBUM WHERE ID = $albumId";
			$this->conn_public->query($sql);
		}

		//Edit albums
		public function getAlbumPropertySetter($albumId)
		{
			$s = "<div class='card image_settingsCard'>";

			$sql = "SELECT * FROM ALBUM WHERE ID = $albumId";
			$result = $this->conn_public->query($sql);

				if($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$albumName=$row['NEV'];
					$introdution = $row['LEIRAS'];
					$s .= "<form action='$this->selfPage?albumId=$albumId' method='post'>
						<input type='text' name='albumName' placeholder='Albumnév' value='$albumName' class='form-control'><br>
						<textarea name='introdution' class='form-control'>$introdution</textarea><br>
						<input type='hidden' id='custId' name='albumId' value='$albumId'>
						<input type='submit' class='btn btn-primary'>
					</form>";
				}
			$s .= "</div>";
			return $s;
		}

		public function getPictureUploadBox($albumId)
		{
			$s = "<div class='card image_settingsCard'>";
				$s .= "	<form action='$this->selfPage?albumId=$albumId' method='post' enctype='multipart/form-data'>
							<h1>Kép feltöltése</h1>
							<input type='file' name='picture[]' class='form-control-file'  multiple><br>
							<input type='submit' name='submit1' class='btn btn-primary'>
							<input type='hidden' id='custId' name='albumId' value='$albumId'>
						</form>";

			$s .= "</div>";
			return $s;
		}

		public function getCoverUploadBox()
		{
			$s = "<div class='card image_settingsCard'>";
				$s .= "	<form action='$this->selfPage' method='post' enctype='multipart/form-data'>
							<h1>Kép feltöltése</h1>
							<input type='file' name='picture' class='form-control-file'><br>
							<input type='submit' name='submit2' class='btn btn-primary'>
						</form>";

			$s .= "</div>";
		}

		public function setAlbumProperties($albumName, $introdution, $albumId)
		{
			$sql = "UPDATE ALBUM SET NEV='$albumName', LEIRAS='$introdution' WHERE ID='$albumId';";
			$this->conn_public->query($sql);
		}


		public function uploadNewPicture($albumId)
		{
				$target_dir = "uploads/fullimages/";
				$thumb_dir = "uploads/thumbnail/";
				$medium_dir = "uploads/fullimages/";
				$full_dir = "uploads/originals/";
				$img_tag = "IMG_";
				$owner = 'aratodana';

				//Get properties of album
				$sql = "SELECT * FROM POZICIOK INNER JOIN ALBUM ON POZICIOK.ALBUMID = ALBUM.ID ORDER BY POZICIOK.Y DESC, POZICIOK.X DESC LIMIT 1;";
				$result = $this->conn_public->query($sql);
				$x = 0;
				$y = 0;
				if($result->num_rows > 0)
				{
					$row = $result->fetch_assoc();
					$x = $row['X'];
					$y = $row['Y'];
				}

				$countfiles = count($_FILES['picture']['name']);
				for($i=0;$i<$countfiles;$i++)
				{
					//Testing the secure of the iamges
					if(antiHackingSystem::testImage("picture", $i))
					{

						//Adding picture to database
						$sql = "INSERT INTO KEP (TULAJ) VALUES ('$owner');";
						$this->conn_public->query($sql);
						$lastId = mysqli_insert_id($this->conn_public);
						$target_file = $target_dir . $img_tag . $lastId . ".jpg";
						$file_tmp=$_FILES["picture"]["tmp_name"][$i];

						//Get the image size
						$percentThumb = 0.1;
						$percentMedium = 0.3;
						list($width, $height) = getimagesize($file_tmp);
						$source = imagecreatefromjpeg($file_tmp);

						//Crate and save thumbnail
						$newwidth = $width * $percentThumb;
						$newheight = $height * $percentThumb;
						$thumb = imagecreatetruecolor($newwidth, $newheight);
						imagecopyresized($thumb,$source,0,0,0,0,$newwidth,$newheight,$width,$height);
						imagejpeg($thumb, $thumb_dir . $img_tag . $lastId . ".jpg");
						imagedestroy($thumb);

						//Resize the picture
						$newwidth = $width * $percentMedium;
						$newheight = $height * $percentMedium;
						$mediumPicture = imagecreatetruecolor($newwidth, $newheight);
						imagecopyresized($mediumPicture,$source,0,0,0,0,$newwidth,$newheight,$width,$height);
						imagejpeg($mediumPicture, $medium_dir . $img_tag . $lastId . ".jpg");
						imagejpeg($source, $full_dir . $img_tag . $lastId . ".jpg");
						imagedestroy($source);

						//Give WaterMark
						$watermark = imagecreatefrompng('images/watermark.png');
						list($width, $height) = getimagesize($file_tmp);
						imagecopyresampled($mediumPicture, $watermark, 0 , 0 , 0 , 0, imagesx($mediumPicture), imagesy($mediumPicture) , imagesx($watermark) , imagesy($watermark));
						imagejpeg($mediumPicture, $target_dir . $img_tag . $lastId . ".jpg");
						imagedestroy($mediumPicture);
						

						$sql = "INSERT INTO POZICIOK (ALBUMID, KEPID, X, Y) VALUES ('$albumId', '$lastId', '$x', '$y');";
						$this->conn_public->query($sql);

						//Add to album
						$x++;
						if($x>2)
						{
							$y++;
							$x = 0;
						}

				}
			}
		}

		

	}
?>