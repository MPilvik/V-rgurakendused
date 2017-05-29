<!-- Kasutatud on Võrgurakendused I kursusel kasutatud materjale ning skriptiosi. Kui skriptiosi on kasutatud muutmata kujul, on sellele vastavas kohas viidatud. -->

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

function login(){
	global $connection;
	
	// kas kasutaja on sisse loginud (kui on, siis suunab pealehele)
	if(!empty($_SESSION['user'])){
		include_once('vaated/avaleht.html');
	}
	// kas kasutaja on üritanud vormi saata
	if (isset($_SERVER['REQUEST_METHOD'])) {
		
		// kui meetodiks on POST
		if ($_SERVER['REQUEST_METHOD']=="POST"){
			// kontrolli, kas vormiväljad on täidetud ja tekita vajadusel vastavaid veateateid
			$errors = array();
			if(empty($_POST['user']) || empty($_POST['pass'])){
				$errors[]='Kasutajanime või parooli pole sisestatud!';
			}
			// kui väljad täidetud, üritada andmebaasist selekteerida kasutajat, kelle kasutajanimi ja parool on vastavad
			else{
				$username = mysqli_real_escape_string($connection, $_POST['user']);
				$passw = mysqli_real_escape_string($connection, $_POST['pass']);
				
				$query = "SELECT id FROM vallakohtud_MPilvik_kasutajad WHERE user = '$username' AND password = SHA1('$passw')";
				$result = mysqli_query($connection, $query) or die ("Ei õnnestunud!".mysqli_error());
				
				// kui päringu tulemuses vähemalt 1 rida, siis on kasutaja sisse logitud ning suunatakse avalehele
				
				if(mysqli_num_rows($result)>0){
					$_SESSION['user'] = $username;
					header("Location: ?");
				} else {
					$errors[] = "Sellist kasutajat ei ole!";
					}
			}
		} else {
			include_once ('vaated/login.html');
		}
	}
	
	include_once('vaated/login.html');
}

function register(){
	global $connection;
	
	// kontrolli, kas kasutaja on juba sisse loginud (kui on, siis suuna ta avalehele)
	if(!empty($_SESSION['user'])){
		include_once('vaated/avaleht.html');
	}
	
	// võta välja kasutajate arv enne uue kasutaja registreerimist, et kontrollida, kas pärast registreerimist on kasutajaid rohkem (= kas registreerimine õnnestus)
	$kysi_kasutajaid1 = "SELECT user FROM vallakohtud_MPilvik_kasutajad";
	$tulemus = mysqli_query($connection, $kysi_kasutajaid1) or die ("Ei õnnestunud!".mysqli_error());
	$kasutajaid1 = mysqli_num_rows($tulemus);
	
	// kontrolli, kas kasutaja on vormi ära saatnud
	if(isset($_SERVER['REQUEST_METHOD'])){
		// kontrolli, kas vorm on saadetud 'POST' meetodil
		if ($_SERVER['REQUEST_METHOD']=="POST"){
			$errors = array();
			// kontrolli, kas nii kasutajanime kui ka parooli väljad on täidetud 
			if(empty($_POST['user']) || empty($_POST['pass'])){
				$errors[]='Kasutajanime või parooli pole sisestatud!';
			}
			// kui on, siis
			else {
				$username = mysqli_real_escape_string($connection, $_POST['user']);
				$passw = mysqli_real_escape_string($connection, $_POST['pass']);
			
				// kontrolli, kas selline kasutajanimi juba eksisteerib andmebaasis
				$kontrolli_kasutajanimesid = mysqli_query($connection, "SELECT user FROM vallakohtud_MPilvik_kasutajad WHERE user='$username'") or die ("Ei õnnestunud!".mysqli_error());

				if(mysqli_num_rows($kontrolli_kasutajanimesid) != 0){
					$errors[]='Selline kasutaja on juba olemas!';
				}
				// kui ei eksisteeri, siis
				else {
					
					// sisesta andmebaasi uus kasutaja
					$query = "INSERT INTO vallakohtud_MPilvik_kasutajad (user, password) VALUES ('$username', SHA1('$passw'))";
					$result = mysqli_query($connection, $query) or die ("Ei õnnestunud!".mysqli_error());
					
					// kontrolli, kas kasutaja sisestamine õnnestus (kas andmebaasi uues versioonis on rohkem kasutajaid, kui enne)
					$kysi_kasutajaid2 = "SELECT user FROM vallakohtud_MPilvik_kasutajad";
					$tulemus2 = mysqli_query($connection, $kysi_kasutajaid2) or die ("Ei õnnestunud!".mysqli_error());
					
					// kui kõik õnnestus, siis logi ka kasutaja sisse
					if(mysqli_num_rows($tulemus2)>$kasutajaid1){
						$_SESSION['user'] = $username;
						if(isset($_SESSION['user'])){
							
							$teade = "Oled registreeritud ja sisse logitud.";
							echo "<script type='text/javascript'>alert('$teade');</script>";
							header('Location: ?');
						}
						
					} else {
						$errors[] = "Kasutaja registreerimine ebaõnnestus!";
					}
				}
			}
		}
		else {
			include_once('vaated/register.html');
		}
	}
	else{
		include_once('vaated/register.html');
	}
	
}

function lisa_protokoll(){
	
	global $connection;
	
	// kontrolli, kas kasutaja on ikka sisse loginud 
	if(!empty($_SESSION['user'])){
		$jätk = "";
		$lk_nr = "";
		$kuupäev = "";
		$number = "";
		$pealkiri = "";
		$kohtumehed = "";
		$sisu = "";
		$fail = "";
		require('vaated/protokollivorm.html');
		
		// kontrolli, kas kasutaja on vormi ära saatnud
		if(isset($_SERVER['REQUEST_METHOD'])){
			// kontrolli, kas vorm on saadetud 'POST' meetodil
			if ($_SERVER['REQUEST_METHOD']=="POST"){
				$errors = array();
				$sisestaja = $_SESSION['user'];
				$fail = "protokollide_failid/".mysqli_real_escape_string($connection, $_POST['fail']);
				
				if (!empty($_POST['jätk'])){
					if($_POST['jätk']=="juba sisestatud protokolli jätk"){
						$jätk=TRUE;
					}
					else if ($_POST['jätk']=="uus protokoll"){
						$jätk=FALSE;
					}
				}
				else{
					$errors[]="Protokolli kirjeldus ei tohi olla tühi!";
				}
					
				if(!empty($_POST['lk_nr']) && is_numeric($_POST['lk_nr'])){
					$lk_nr = mysqli_real_escape_string($connection, $_POST['lk_nr']);
				}
				else{
					$lk_nr = "";
				}
				if(!empty($_POST['kuupäev'])){
					$kuupäev = mysqli_real_escape_string($connection, $_POST['kuupäev']);
				}
				else{
					$errors[]="Kuupäev ei tohi olla tühi!";
				}
				if(!empty($_POST['number']) && is_numeric($_POST['number'])){
					$number = mysqli_real_escape_string($connection, $_POST['number']);
				}
				else{
					$number = "";
				}
				if(!empty($_POST['pealkiri'])){
					$pealkiri = mysqli_real_escape_string($connection, $_POST['pealkiri']);
				}
				else{
					$errors[]="Pealkiri ei tohi olla tühi!";
				}
				if(!empty($_POST['kohtumehed'])){
					$kohtumehed = mysqli_real_escape_string($connection, $_POST['kohtumehed']);
				}
				else{
					$kohtumehed="";
				}
				if(!empty($_POST['sisu'])){
					$sisu = mysqli_real_escape_string($connection, $_POST['sisu']);
				}
				else{
					$errors[]="Sisu ei tohi olla tühi!";
				}
					
				print_r($errors);
				
				// kui kõik vajalikud väljad said täidetud
				if(empty($errors)){
					
					
					$query = "UPDATE vallakohtud_MPilvik 
					SET 
					sisestaja = '$sisestaja', 
					jätk = '$jätk',
					lk_nr = '$lk_nr',
					kuupäev = '$kuupäev',
					number = '$number',
					pealkiri = '$pealkiri',
					kohtumehed = '$kohtumehed',
					sisu = '$sisu'
					WHERE failinimi='$fail'";
					$result = mysqli_query($connection, $query) or die ("Ei õnnestunud lisada!".mysqli_error($connection));
				
					/*
					// kontrolli, kas õnnestus
					if(mysqli_insert_id($connection) > 0){
						header("Location: ?page=loomad");
					} else {
						$errors[] = "Lisamine ei õnnestunud!";
					}
					*/
				}
			}
			else {
				include_once('vaated/protokollivorm.html');
			}
		}
		else {
			include_once('vaated/protokollivorm.html');
		}
	}
	else {
		include_once('vaated/login.html');
	}
	
}
/*
$dir = "big"; // kausta nimi, mida avada
$failid = array(); // massiiv, kuhu lisatakse leitud failid
if ($dh = opendir($dir)) { // kui funktsioon opendir vastava sisendiga õnnestub, siis jäta viide kaustale meelde muutujasse $dh ning läbi järgnev koodiblokk
  while (($file = readdir($dh)) !== false) { // seni, kuni funktsiooniga readdir vastavas kaustas saab kätte mingi kirje (fail/kaust), salvesta see kirje muutujasse $file ning läbi järgnev koodiblokk
    if(!is_dir($file)) { // juhul, kui saadud kirje ei ole kaust, siis lisa antud kirje failide massiivi
      $failid[] = $file;
    }
  }
  closedir($dh); // kui kausta lugemine on läbi, sulge ühendus kaustaga.
} else { // kui funktsioon opendir luhtub(kaust puudub), siis esita veateade ja lõpeta programmi töö
  die("Ei suuda avada kataloogi $dir");
}
print_r($failid);
*/
function logout(){
	$_SESSION=array();
	session_destroy();
	header("Location: ?");
}

?>