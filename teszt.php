<!DOCTYPE html>
<html>
<head>
	<title>Tesztoldal</title>
</head>
<body>
	<?php
				require_once("essential/antiHackingSystem.php");
			
				$countfiles = count($_FILES['picture']['name']);
				for($i=0;$i<$countfiles;$i++)
				{

					$file_tmp="picture";
					$check =  antiHackingSystem::testImage($file_tmp);
					if($check)	echo "Minden oké";
					else 		echo "Valami nem oké";

			}
	?>
	<div class='card image_settingsCard'>
		<form action='teszt.php' method='post' enctype='multipart/form-data'>
			<h1>Kép feltöltése</h1>
			<input type='file' name='picture[]' class='form-control-file'  multiple><br>
			<input type='submit' name='submit1' class='btn btn-primary'>
			<input type='hidden' id='custId' name='albumId' value='$albumId'>
		</form>
	</div>
</body>
</html>