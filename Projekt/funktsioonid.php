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
			
			// kontrolli, kas kasutajanimi ja parool on täidetud
			// kui ei ole kasutajat
			if(empty($_POST['user'])){
				$err_nouser='Kasutajanime pole sisestatud!';
					// kontrolli, kas ka parooli pole täidetud
					if(empty($_POST['pass'])){
						$err_nopass='Parooli pole sisestatud!';
					}
				//require_once('vaated/register.html');
			}
			// kui ainult parooli välja pole täidetud
			else if(empty($_POST['pass'])){
				$err_nopass='Parooli pole sisestatud!';
				//require_once('vaated/register.html');
			}
			// kui on, siis
			else {
				$username = mysqli_real_escape_string($connection, $_POST['user']);
				$passw = mysqli_real_escape_string($connection, $_POST['pass']);
			
				// kontrolli, kas selline kasutajanimi juba eksisteerib andmebaasis
				$kontrolli_kasutajanimesid = mysqli_query($connection, "SELECT user FROM vallakohtud_MPilvik_kasutajad WHERE user='$username'") or die ("Ei õnnestunud!".mysqli_error());

				if(mysqli_num_rows($kontrolli_kasutajanimesid) != 0){
					$err_userexists='Selline kasutaja on juba olemas!';
					require_once('vaated/register.html');
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
						echo '<script type="text/javascript">alert("Oled registreeritud ja sisse logitud.");';
						echo 'window.location.href = "vallakohtud.php";';
						echo '</script>';
						
						
					} else {
						$err_failed = "Kasutaja registreerimine ebaõnnestus!";
						print($err_failed);
					}
				}
			}
			require_once('vaated/register.html');
		}
		require_once('vaated/register.html');
	}
}

function vali_pilt() {
	global $connection;
	if(!empty($_SESSION['user'])){
		
		require('vaated/pildivalik.html');
		// kontrolli, kas kasutaja on vormi ära saatnud
		if(isset($_SERVER['REQUEST_METHOD'])){
			// kontrolli, kas vorm on saadetud 'POST' meetodil
			if ($_SERVER['REQUEST_METHOD']=="POST"){
				
				if(!empty($_POST['fail'])){
					$_SESSION['fail'] = mysqli_real_escape_string($connection, $_POST['fail']);
					header("Location: ?page=protokollivorm");
					
				}
				else{
					$err_nofile = "Vali fail!";
					echo "<div style='color:red;'>".$err_nofile."</div>";
				}
			}
		}
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
		
		// kontrolli, kas kasutaja on vormi ära saatnud
		if(isset($_SERVER['REQUEST_METHOD'])){
			// kontrolli, kas vorm on saadetud 'POST' meetodil
			if ($_SERVER['REQUEST_METHOD']=="POST"){
				$errors = array();
				$sisestaja = $_SESSION['user'];
				$fail = "protokollide_failid/".$_SESSION['fail'];
				
				
				if (!empty($_POST['jätk'])){
					if($_POST['jätk']=="juba sisestatud protokolli jätk"){
						$jätk=TRUE;
					}
					else if ($_POST['jätk']=="uus protokoll"){
						$jätk=FALSE;
					}
				}
				else{
					$err_protjätk="Protokolli kirjeldus ei tohi olla tühi!";
					$errors[] = $err_protjätk;
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
					$err_protkuup="Kuupäev ei tohi olla tühi!";
					$errors[] = $err_protkuup;
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
					$err_pealkiri="Pealkiri ei tohi olla tühi!";
					$errors[] = $err_pealkiri;
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
					$err_sisu="Sisu ei tohi olla tühi!";
					$errors[] = $err_sisu;
				}
					
				// kui kõik vajalikud väljad said täidetud
				if(empty($errors)){
					
					// kontrolli, kas faili kohta on juba protokoll olemas
					
					// uuenda andmebaasi
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
				
					// kontrolli, kas andmebaasi uuendamine õnnestus
					if(mysqli_affected_rows($connection) > 0){
						
						echo '<script type="text/javascript">alert("Protokolli lisamine õnnestus. Soovi korral võid lisada veel protokolle.");';
						echo 'window.location.href = "vallakohtud.php";';
						echo '</script>';
						
					} else {
						$err_failed2 = "Protokolli lisamine ei õnnestunud!";
						$errors[] = $err_failed2;
					}
				}
			}
			
			require_once('vaated/protokollivorm.html');
		}
		require_once('vaated/protokollivorm.html');
	} 
	else{
		require_once('vaated/login.html');
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