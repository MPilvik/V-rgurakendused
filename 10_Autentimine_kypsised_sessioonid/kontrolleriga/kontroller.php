<?php

session_start();
require_once("vaated/head.html");

$pildid = array(
		1=>array('src'=>"pildid/nameless1.jpg", 'alt'=>"nimetu 1"),
		2=>array('src'=>"pildid/nameless2.jpg", 'alt'=>"nimetu 2"),
		3=>array('src'=>"pildid/nameless3.jpg", 'alt'=>"nimetu 3"),
		4=>array('src'=>"pildid/nameless4.jpg", 'alt'=>"nimetu 4"),
		5=>array('src'=>"pildid/nameless5.jpg", 'alt'=>"nimetu 5"),
		6=>array('src'=>"pildid/nameless6.jpg", 'alt'=>"nimetu 6"),
	);
$page="pealeht";
if (isset($_GET['page']) && $_GET['page']!=""){
	$page=htmlspecialchars($_GET['page']);
}
$id=false;


switch($page){
	case "galerii":
		include("vaated/galerii.html");
	break;
	case "vote":
		if($id==true || empty($_SESSION) == false){
			include('vaated/tulemus.html');
		} else {
			include('vaated/vote.html');
		}
	break;
	case "tulemus":
		if (isset($_POST['pilt']) && isset($pildid[$_POST['pilt']]) && empty($_SESSION)){
			$id=htmlspecialchars($_POST['pilt']);
			$_SESSION['votedFor'] = $id;
			}
		include('vaated/tulemus.html');
		
	break;
	case "exit":
		include('exit.php');
	default:
		include('vaated/pealeht.html');
}


require_once("vaated/foot.html");
?>
