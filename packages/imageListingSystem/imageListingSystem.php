<?php
	require_once("./essential/antiHackingSystem.php");
	require_once("./essential/connector.php");
	require_once("./essential/session_starter.php");
	require_once("./essential/bootstrapIncludes.php");
/*
This script created by Arató Dániel
Version: 1.1.0.2
=============================================================================================================
  ___                              _     _     _   _               ____            _                 
 |_ _|_ __ ___   __ _  __ _  ___  | |   (_)___| |_(_)_ __   __ _  / ___| _   _ ___| |_ ___ _ __ ___  
  | || '_ ` _ \ / _` |/ _` |/ _ \ | |   | / __| __| | '_ \ / _` | \___ \| | | / __| __/ _ \ '_ ` _ \ 
  | || | | | | | (_| | (_| |  __/ | |___| \__ \ |_| | | | | (_| |  ___) | |_| \__ \ ||  __/ | | | | |
 |___|_| |_| |_|\__,_|\__, |\___| |_____|_|___/\__|_|_| |_|\__, | |____/ \__, |___/\__\___|_| |_| |_|
                      |___/                                |___/         |___/                       
=============================================================================================================
	Simple script, listing the albums and images from database and file

	Require includes:
		- php_back_sites/antiHackingSystem.php
		- php_back_sites/connector.php
		- site_parts/session_starter-php

	Require styleSheets:
		- css/imageListingSystem.css

	Require database datas:

		Database: photosite_public
			Table: KEP
			Table: POZICIOK
			Table: ALBUM
			Table: FELHASZNALO_PUBLIC

	Reqire Folders
		uploads/fullimages
		uploads/thumbnail
		uploads/covers

=============================================================================================================
WARNING: THE FOLDERS MUST BE WRITEABLE
=============================================================================================================
	Public members:

	Public Functions:
		string getAlbum($albumId): 				returns the pictures in table in the album with id $albumId
		string getAlbumTumbnail($albumId):		returns the thumbnail card of the album with the id $albumId
		string getUserAlbums($username):		returns the thumbnails of the public albums of the user
		string getUserCover($username):			returns the cover picture full name and introdution of the user

	Private Members:
		connection $conn_public: 	database connection

	Private Functions:
		
=============================================================================================================
*/
	class imageListingSystem
	{
		private $conn_public;

		public function __construct()
		{
			$this->conn_public = connector::getConnect();
		}


		public function getAlbum($albumId)
		{
			$sql = "SELECT * FROM POZICIOK INNER JOIN KEP ON POZICIOK.KEPID = KEP.ID INNER JOIN ALBUM ON POZICIOK.ALBUMID = ALBUM.ID WHERE ALBUM.ID='$albumId' ORDER BY POZICIOK.Y DESC, POZICIOK.X DESC;";
			$result = $this->conn_public->query($sql);
			$s = "";
			$pictureNames = "";
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				$albumName = $row['NEV'];
				$albumTime = $row['DATUM'];
				$albumName = "<h1>$albumName</h1><small><time>$albumTime</time></small>";
				$s = "</table></tr>";
				$i = 0;
				do
				{
					$imageName = $row['KEPID'];
					$pictureNames .= ", $imageName";
					$s = "<td><img src='uploads/thumbnail/IMG_$imageName.jpg' class='albumThumbnail' onclick='setCurrentPicture($i)'></td>" . $s;
					if($row['X'] == 0) $s = "</tr><tr>" . $s;
					$i++;
				}
				while($row = $result->fetch_assoc());
				$s = "<tr><table>" . $s;
				$pictureNames = ltrim($pictureNames, ", ");
				echo "<script type='text/javascript'>var array = [$pictureNames]; var index = 0;</script>";
				return "<div id='albumDiv'>" . $albumName . $s . "</div>";
			}
			return "<center><h1>Hiba az oldal betöltése során, lehet, hogy  nem létezik ilyen album</h1></center>";

		}

		public function getIncludes()
		{
			echo "<link rel='stylesheet' type='text/css' href='packages/imageListingSystem/css/imageListingSystem.css'>";
			echo "<script src='packages/imageListingSystem/javascript/imageListingSystem.js'></script>";
		}

		public function getAlbumTumbnail($albumId)
		{
			$sql = "SELECT * FROM POZICIOK INNER JOIN KEP ON POZICIOK.KEPID = KEP.ID INNER JOIN ALBUM ON POZICIOK.ALBUMID = ALBUM.ID WHERE ALBUM.ID='$albumId' ORDER BY POZICIOK.Y ASC, POZICIOK.X ASC LIMIT 1;";
			$result = $this->conn_public->query($sql);
			$s = "";
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				$imageName = $row['KEPID'];
				$albumName = $row['NEV'];
				$albumIntro = $row['LEIRAS'];
				$albumId = $row['ID'];
				$albumTime = $row['DATUM'];
				$imageLink = "uploads/thumbnail/IMG_$imageName.jpg";

				$s = "<div class='card albumLinkCard'>
						  <img class='card-img-top' src='$imageLink'>
						  <div class='card-body'>
						    <h4 class='card-title'>$albumName</h4>
						    <small><time>$albumTime</time></small>
						    <p class='card-text'>$albumIntro</p>
						    <a href='album.php?album=$albumId' class='btn btn-primary'>Megtekintés</a>
						  </div>
						</div>"
						;


			}
			return $s;
		}

		public function getUserAlbums($username)
		{
			$sql = "SELECT ID FROM ALBUM WHERE TULAJ = '$username';";
			$result = $this->conn_public->query($sql);
			$s = "";
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					$id = $row['ID'];
					$s .= $this->getAlbumTumbnail($id);
				}
			}
			$s = "<div id='albumList'>" . $s . "</div>";
			return $s;
		}

		public function getUserCover($username)
		{
			$sql = "SELECT * FROM FELHASZNALO_PUBLIC WHERE NICKNEV = '$username';";
			$result = $this->conn_public->query($sql);
			
			$s = '';
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				$fullName = $row['TELJES_NEV'];
				$intro = $row['BEMUTATKOZAS'];
				$s .= "<h1>$fullName</h1>
				<p>$intro</p>";
			}
			// style='background: #ffffff url(uploads/covers/$username.jpg);'
			// style='background-image: url(uploads/covers/$username.jpg);'
			$s = 	"<div id='profileBox' class='profileBox'><div id='introdutionBox'>" . $s . "</div></div>";
			$s .= "<style>
						div.profileBox
						{
							background-image: url(uploads/covers/$username.jpg);
						}
					</style>";
			return $s;
		}

		public function getPictureModal()
		{
			$s = "<div class='modal' id='pictureModal'>
					<div id='pictureControllBar'>
						<button class='btn but-primary' onclick='closeModal();''>X</button>
						<button class='btn but-primary'  onclick='prevPicture();''><</button>
						<button class='btn but-primary'  onclick='nextPicture();''>></button>
					</div>
				  <div class='modal-dialog'>
				    <div class='modal-content'  id='pictureModalContent'>
				    </div>
				  </div>
				</div>";
			return $s;
		}

	}
?>