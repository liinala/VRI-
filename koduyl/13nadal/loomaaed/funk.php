<?php


function connect_db(){
	global $connection;
	$host="localhost";
	$user="test";
	$pass="t3st3r123";
	$db="test";
	$connection = mysqli_connect($host, $user, $pass, $db) or die("ei saa ühendust mootoriga- ".mysqli_error());
	mysqli_query($connection, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse - ".mysqli_error($connection));
}

function logi(){
	// siia on vaja funktsionaalsust (13. nädalal)
	 if (isset($_POST['user'])) {
		include_once('views/puurid.html');
	}
	//kontrollib, kas kasutaja on juba vormi saata üritanud. Kas päring on tehtud POST (vormi esitamisel) või GET (lingilt tulles) meetodil, saab teada serveri infost, mis asub massiivist $_SERVER võtmega 'REQUEST_METHOD'
	if (isset($_SERVER['REQUEST_METHOD'])) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		  	
		  	//Kui meetodiks oli POST, kontrollida kas vormiväljad olid täidetud. Vastavalt vajadusele tekitada veateateid (massiiv $errors)
		  	$errors = array();
		
		  	if (empty($_POST['user']) || empty($_POST['pass'])) {
		  		if(empty($_POST['user'])) {
			    	$errors[] = "kasutajanimi on puudu";
				}
				if(empty($_POST['pass'])) {
					$errors[] = "parool on puudu";
				} 
		  	} else {
		  		//kui kõik väljad olid täidetud, üritada andmebaasitabelist <sinu kasutajanimi/kood/>_kylalised selekteerida külalist, kelle kasutajanimi ja parool on vastavad 
		  		global $connection;
		  		$username = mysqli_real_escape_string($connection, $_POST["user"]);
		  		$passw = mysqli_real_escape_string($connection, $_POST["pass"]);
		  		
				$query = "SELECT id FROM llaumets_kylastajad WHERE username='$username' && passw=SHA1('$passw')";
				$result = mysqli_query($connection, $query) or die("midagi läks valesti");
			
				//Kui selle SELECT päringu tulemuses on vähemalt 1 rida (seda saab teada mysqli_num_rows funktsiooniga) siis lugeda kasutaja sisselogituks -> luua sessiooniväli 'user' ning suunata ta loomaaia vaatesse
				$ridu = mysqli_num_rows($result);
					if ( $ridu > 0) {
						$_SESSION['user'] = $username;
						header("Location: ?page=loomad");
					}
		  	}
		//igasuguste vigade korral ning lehele esmakordselt saabudes kuvatakse kasutajale sisselogimise vorm failist login.html
		} else {

	include_once('views/login.html');
}

function logout(){
	$_SESSION=array();
	session_destroy();
	header("Location: ?");
}

function kuva_puurid(){
	// siia on vaja funktsionaalsust 
global $connection;
	$puurid = array();
	$sql ="SELECT DISTINCT puur FROM llaumets_loomaaed";
	$result = mysqli_query($connection, $sql) or die("midagi on valesti");
	while($rida = mysqli_fetch_assoc($result)){
		$sql_puurielanikud="SELECT * FROM llaumets_loomaaed WHERE puur=".$rida["puur"];
		$result_puurielanikud = mysqli_query($connection, $sql_puurielanikud) or die("midagi on valesti");
		while($puurielanikega_rida = mysqli_fetch_assoc($result_puurielanikud)){
			 $puurid[$rida["puur"]][]=$puurielanikega_rida;
		}
	}
	//print_r($puurid);

	include_once('views/puurid.html');
	
}

function lisa(){
	// siia on vaja funktsionaalsust (13. nädalal)
	if (empty($_SESSION['user'])) {
		include_once 'views/loomavorm.html';
	
}


	//kontrollib, kas kasutaja on üritanud juba vormi saata. Kas päring on tehtud POST (vormi esitamisel) või GET (lingilt tulles) meetodil, saab teada serveri infost, mis asub massiivist $_SERVER võtmega 'REQUEST_METHOD'
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//kui meetodiks oli POST, tuleb kontrollida, kas kõik vormiväljad olid täidetud ja tekitada vajadusel vastavaid veateateid (massiiv $errors). 
		$errors = array();
  	
  		if(empty($_POST['nimi'])) {
	    	$errors[] = "nimi on puudu";
		}
		if(empty($_POST['puur'])) {
			$errors[] = "puur on puudu";
		}
		
		$pilt = upload("liik");
		if ($pilt == "") {
			$errors[] = "pilt on puudu";
		}
	  	if (empty($errors)) {
	  		//Kui vigu polnud, siis üritada see loom andmebaasitabelisse <sinu kasutajanimi/kood/>_loomaaed lisada. 
	  		global $connection;
	  		$loomanimi = mysqli_real_escape_string($connection, $_POST["nimi"]);
	  		$puurinr = mysqli_real_escape_string($connection, $_POST["puur"]);
			$query = "INSERT INTO llaumets_loomaaed (nimi, liik, puur) VALUES ('$loomanimi', '$pilt', '$puurinr')";
			$result = mysqli_query($connection, $query) or die("midagi läks valesti");;
		
			//Kas looma lisamine õnnestus või mitte, saab teada kui kontrollida mis väärtuse tagastab mysqli_insert_id funktsioon. Kui väärtus on nullist suurem, suunata kasutaja loomade vaatessse 
			if (mysqli_insert_id($connection) > 0) {
				header("Location: ?page=loomad");
			}
	  	} 
	}
	include_once('views/loomavorm.html');
}

function upload($name){
	$allowedExts = array("jpg", "jpeg", "gif", "png");
	$allowedTypes = array("image/gif", "image/jpeg", "image/png","image/pjpeg");
	$extension = end(explode(".", $_FILES[$name]["name"]));

	if ( in_array($_FILES[$name]["type"], $allowedTypes)
		&& ($_FILES[$name]["size"] < 100000)
		&& in_array($extension, $allowedExts)) {
    // fail õiget tüüpi ja suurusega
		if ($_FILES[$name]["error"] > 0) {
			$_SESSION['notices'][]= "Return Code: " . $_FILES[$name]["error"];
			return "";
		} else {
      // vigu ei ole
			if (file_exists("pildid/" . $_FILES[$name]["name"])) {
        // fail olemas ära uuesti lae, tagasta failinimi
				$_SESSION['notices'][]= $_FILES[$name]["name"] . " juba eksisteerib. ";
				return "pildid/" .$_FILES[$name]["name"];
			} else {
        // kõik ok, aseta pilt
				move_uploaded_file($_FILES[$name]["tmp_name"], "pildid/" . $_FILES[$name]["name"]);
				return "pildid/" .$_FILES[$name]["name"];
			}
		}
	} else {
		return "";
	}
}
?>