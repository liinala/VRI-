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
function kuva_puurid(){
	//Kontrollib, kas kasutaja on sisse logitud. Kui pole, suunab sisselogimise vaatesse
	if (!empty($_SESSION['user'])) {
		global $connection;
		$p= mysqli_query($connection, "select distinct(puur) as puur from llaumets_loomaaed order by puur asc");
		$puurid=array();
		while ($r=mysqli_fetch_assoc($p)){
			$l=mysqli_query($connection, "SELECT * FROM llaumets_loomaaed WHERE  puur=".mysqli_real_escape_string($connection, $r['puur']));
			while ($row=mysqli_fetch_assoc($l)) {
				$puurid[$r['puur']][]=$row;
			}
		}
		include_once('views/puurid.html');
	} else {
		include_once 'views/login.html';
	}
	
}
function logi(){
	//Kontrollib, kas kasutaja on juba sisse logitud. Kui on, suunab loomade vaatesse (sisselogitud kasutaja ei pea ju uuesti sisse logima)
	if (isset($_POST['user'])) {
		include_once('views/puurid.html');
	}
	//kontrollib, kas kasutaja on üritanud juba vormi saata. Kas päring on tehtud POST (vormi esitamisel) või GET (lingilt tulles) meetodil, saab teada serveri infost, mis asub massiivist $_SERVER võtmega 'REQUEST_METHOD'
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
				$query = "SELECT * FROM llaumets_kylastajad WHERE username='$username' && passw=SHA1('$passw')";
				$result = mysqli_query($connection, $query) or die("midagi läks valesti");
				//Kui selle SELECT päringu tulemuses on vähemalt 1 rida (seda saab teada mysqli_num_rows funktsiooniga) siis lugeda kasutaja sisselogituks -> luua sessiooniväli 'user' ning suunata ta loomaaia vaatesse
				$queryresult = mysqli_fetch_assoc($result);
				$role = $queryresult['roll'];
				$rows = mysqli_num_rows($result);
					if ( $rows > 0) {
						//muuta funktsiooni login() nii, et sisselogimise õnnestumisel salvestuks sessiooni ka kasutaja roll
						$_SESSION['user'] = $username;
						$_SESSION['role'] = $role;
						//muuta funktsiooni lisa() nii, et funktsiooni täielikuks käivitumiseks peab kasutaja lisaks sisse logitusele olema ka admin rollis (muul juhul suunati loomaaia vaatesse)
						if ($_SESSION['role'] == 'admin') {
							header("Location: ?page=lisa");	
						} else {
							header("Location: ?page=loomad");	
						}
						
					}
		  	}
		//igasuguste vigade korral ning lehele esmakordselt saabudes kuvatakse kasutajale sisselogimise vorm failist login.html
		} else {
			 include_once 'views/login.html';
		}
	}
	
	include_once('views/login.html');
}
function logout(){
	$_SESSION=array();
	session_destroy();
	header("Location: ?");
}
function lisa(){
	//Kontrollib, kas kasutaja on sisse logitud. Kui pole, suunab sisselogimise vaatesse
	if (empty($_SESSION['user'])) {
		include_once 'views/login.html';
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
function muuda(){
	global $connection;
	//funktsiooni muuda() alguses (kui kasutaja on sisse logitud ja admin) kontrollitakse kas päringuga (POST = vormist või GET = link ) on saadetud looma id. Puudumisel suunatakse loomaaia vaatesse, olemasolul hangitakse looma info eelnevalt loodud funktsiooni hangi_loom abil mingisse muutujasse
	if (empty($_SESSION['user'])) {
		header("Location: ?page=login");
	}
	if ($_SESSION['role'] == 'user') {
		header("Location: ?page=loomad");
	}
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id']) && $_GET['id'] != "") {
		$id = $_GET['id'];
		$animal = hangi_loom(mysqli_real_escape_string($connection, $id)); 
	} else {
		header("Location: ?page=loomad");
	}
	//Kui lehele tullakse, täidetakse vorm (editvorm.html) just eelnevas punktis saadud infoga
	//vormiga saadetud info valideerimise käigus kirjutatakse looma infot hoidva massiivi välju uute väärtustega üle (nende olemasolul POST massiivis). Pildi üleslaadmise luhtumisel kuvatavat veateadet pole enam vaja, kuna pilti ei pruugita alati tahta muuta.
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['muuda'])) {
	//vormiga saadetud info valideerimine
	$errors = array();
	if (empty($_POST['nimi'])) {
		$errors[] = "nimi puudub";
	}
	if (empty($_POST['puur'])) {
		$errors[] = "puur puudub";
	}
	
	//vigade puudumisel käivitatakse update päring, kus määratakse kõigi väljade väärtused (looma info massiivis on selleks hetkeks iga väli mingi väärtusega esindatud)
	if (empty($errors)) {
		$id = $_POST['muuda'];
		$loom = hangi_loom(mysqli_real_escape_string($connection, $id));
		
		$loom['nimi'] = mysqli_real_escape_string($connection, $_POST["nimi"]);
		$loom['puur'] = mysqli_real_escape_string($connection, $_POST["puur"]);
		$liik = upload("liik");
			if ($liik != "") {
				$loom['liik'] = $liik;
			}
		}
		$query = "UPDATE llaumets_loomaaed SET nimi='".$loom['nimi']."', liik='".$loom['liik']."', puur=".$loom['puur']."  WHERE id=".$id; 
		$result = mysqli_query($connection, $query) or die("ei muutnud midagi");
		header("location: ?page=loomad");
	}
	include_once('views/editvorm.html');
}
//lisada funkstioon hangi_loom($id), mis tagastab konkreetse looma info massiivi kujul (id väärtus sisendparameetris). Kui sellise id-ga looma baasis pole, suunata kasutaja loomaaia vaatesse
function hangi_loom($id) {
	global $connection;
	$query = "SELECT * FROM llaumets_loomaaed WHERE id=".$id;
	$result = mysqli_query($connection, $query) or die("midagi läks valesti");
 	if ($animaldata = mysqli_fetch_assoc($result)) {
		return $animaldata;
	}
	else {
		header("Location: ?page=loomad");
	}
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