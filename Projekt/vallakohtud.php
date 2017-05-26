<?php
require_once("funktsioonid.php");
session_start();
// connect_db();

$page="pealeht";
if (!empty($_GET["page"])){
	$page=htmlspecialchars($_GET["page"]);
}

include_once("vaated/head.html");

switch($page){
	case "login":
		logi();
	break;
	case "protokoll":
		lisa_protokoll();
	break;
	case "logout":
		logout();
	break;
	case "vaata":
		vaata();
	break;
	default:
		include("vaated/avaleht.html");
	break;
}

include_once("vaated/foot.html");

?>