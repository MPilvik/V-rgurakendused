<?php

global $role;

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
	global $connection;
	// siia on vaja funktsionaalsust (13. nädalal)
	// kas kasutaja on sisse loginud (kui on, siis suunab loomade vaatesse)
	if(!empty($_SESSION['user'])){
		header("Location: ?page=loomad");
	}
	// kas kasutaja on üritanud vormi saata
	if (isset($_SERVER['REQUEST_METHOD'])) {
		// kui meetodiks on POST
		if ($_SERVER['REQUEST_METHOD']=="POST"){
			// kontrolli, kas vormiväljad on täidetud ja tekita vajadusel vastavaid veateateid
			$errors = array();
			if(empty($_POST['user'])){
				$errors[]='Kasutajanime pole sisestatud!';
			}
			else if(empty($_POST['pass'])){
				$errors[]='Parooli pole sisestatud!';
			}
			// kui väljad täidetud, üritada andmebaasist selekteerida külalist, kelle kasutajanimi ja parool on vastavad
			else{
				$username = mysqli_real_escape_string($connection, $_POST['user']);
				$passw = mysqli_real_escape_string($connection, $_POST['pass']);
				
				$query = "SELECT id FROM loomaaedMPilvik_kylastajad WHERE username = '$username' AND passw = SHA1('$passw')";
				$result = mysqli_query($connection, $query) or die ("Ei õnnestunud!");
				
				// kui päringu tulemuses vähemalt 1 rida, siis on kasutaja sisse logitud ning suunatakse loomaaia vaatesse
				
				if(mysqli_num_rows($result)>0){
					
					$query_role = "SELECT roll FROM loomaaedMPilvik_kylastajad WHERE username = '$username' AND passw = SHA1('$passw')";
					$role_result = mysqli_query($connection, $query_role) or die ("Ei õnnestunud!");
					$roll = mysqli_fetch_assoc($role_result);
					$_SESSION['role'] = $roll['roll'];
					$_SESSION['user'] = $username;
					header("Location: ?page=loomad");
					
				} else {
					$errors[] = "Sellist kasutajat ei ole!";
					}
			}
		} else {
			include_once ('views/login.html');
		}
	}
	
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
	//if(empty($_SESSION['user'])){header("Location: ?page=login")};
	$puurid = array();
	$query = "SELECT DISTINCT puur FROM loomaaedMPilvik";
	$result = mysqli_query($connection, $query) or die ("Ei õnnestu: ".mysqli_error($connection));
	
	while($rida = mysqli_fetch_assoc($result)){
		$puuri_nr = $rida['puur'];
		$puuriloomad = "SELECT * FROM loomaaedMPilvik WHERE puur=$puuri_nr";
		$result2 = mysqli_query($connection, $puuriloomad) or die ("Ei õnnestu: ".mysqli_error($connection));
		
		while($loomarida = mysqli_fetch_assoc($result2)){
			$puurid[$puuri_nr][]=$loomarida;
		}
	}
	include_once('views/puurid.html');
	
}

function lisa(){
	// siia on vaja funktsionaalsust (13. nädalal)
	global $connection;
	// kas kasutaja on sisse loginud. Kui pole, suunab sisselogimise vaatesse
	if(empty($_SESSION['user'])){
		include_once('views/login.html');
	}
	
	// kui kasutaja ei ole admini rollis, siis suuna ta loomaaia leheküljele
	if($_SESSION['role']!='admin'){
		header("Location: ?page=loomad");
	}

	// kas kasutaja on üritanud vormi saata
	$errors = array();
	if (isset($_SERVER['REQUEST_METHOD'])) {
		// Kui meetodiks on POST
		if($_SERVER['REQUEST_METHOD']=='POST'){
			// kontrolli, kas kõik vormiväljad on täidetud ja tekita vajadusel vastavaid veateateid
			if(empty($_POST['nimi'])){
				$errors[]='Nime pole sisestatud!';
			}
			if(empty($_POST['puur'])){
				$errors[]='Puuri pole sisestatud!';
			}
			$pilt = upload('liik');
			if($pilt == ""){
				$errors[]='Pilti pole lisatud!';
			}
			if(empty($errors)){
				$looma_nimi = mysqli_real_escape_string($connection, $_POST["nimi"]);
				$puuri_nr = mysqli_real_escape_string($connection, $_POST["puur"]);
				$query = "INSERT INTO loomaaedMPilvik(nimi, liik, puur) VALUES ('$looma_nimi', '$pilt', '$puuri_nr')";
				$result = mysqli_query($connection, $query) or die ("Ei õnnestunud!");
				
				// kontrolli, kas õnnestus
				if(mysqli_insert_id($connection) > 0){
					header("Location: ?page=loomad");
				} else {
					$errors[] = "Lisamine ei õnnestunud!";
				}
			}
		} 
		
	} else {
		$errors[] = "Vormi pole saadetud!";
	}
	
	if(!empty($errors)){
		include_once('views/loomavorm.html');
	}
	
	
	include_once('views/loomavorm.html');
	
}

function hangi_loom($id){
	global $connection;
	$query = "SELECT * FROM loomaaedMPilvik WHERE id = $id";
	$result = mysqli_query($connection, $query) or die("Ei õnnestunud!");
 	if ($yks_loom = mysqli_fetch_assoc($result)) {
		return $yks_loom;
	}
	else {
		header("Location: ?page=loomad");
	}
}

function muuda(){
	// siia on vaja funktsionaalsust (13. nädalal)
	global $connection;
	// kas kasutaja on sisse loginud. Kui pole, suunab sisselogimise vaatesse
	if(empty($_SESSION['user'])){
		include_once('views/login.html');
	}
	
	// kui kasutaja ei ole admini rollis, siis suuna ta loomaaia leheküljele
	if($_SESSION['role']!='admin'){
		header("Location: ?page=loomad");
	}

	// kas kasutaja on üritanud vormi saata
	$errors = array();
	if (isset($_SERVER['REQUEST_METHOD'])) {
		
		if($_SERVER['REQUEST_METHOD']=='GET'){
			if(!empty($_GET['id'])){
				$id = $_GET['id'];
				$Loom = hangi_loom(mysqli_real_escape_string($connection, $id));
			} else {
				header("Location: ?page=loomad");
			}
		}
			
		// Kui meetodiks on POST
		if($_SERVER['REQUEST_METHOD']=='POST'){
			
			if(!empty($_POST['ID'])){
				if(empty($_POST['nimi'])){
					$errors[]='Nime pole sisestatud!';
				}
				if(empty($_POST['puur'])){
					$errors[]='Puuri pole sisestatud!';
				}
				
			}
			
			
			if(empty($errors)){
				$id = $_POST['ID'];
				$loom = hangi_loom(mysqli_real_escape_string($connection, $id));
				$loom['nimi'] = mysqli_real_escape_string($connection, $_POST["nimi"]);
				$loom['puur'] = mysqli_real_escape_string($connection, $_POST["puur"]);
				$liik = upload("liik");
				if($liik != ""){
					$loom['liik'] = $liik;
				}
				
				$query = "UPDATE loomaaedMPilvik SET nimi='".$loom['nimi']."', liik='".$loom['liik']."', puur='".$loom['puur']."' WHERE id='$id'";
				$result = mysqli_query($connection, $query) or die ("Ei teinud midagi!");
				
				
				header("Location: ?page=loomad");
				
			}
		} 
		
	} else {
		$errors[] = "Vormi pole saadetud!";
	}
	
	if(!empty($errors)){
		include_once('views/loomavorm.html');
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