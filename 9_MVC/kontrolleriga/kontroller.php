<?php 
	$gallery=array(
		array("path"=>"pildid/nameless1.jpg","alt"=>"nimetu 1","index"=>1),
		array("path"=>"pildid/nameless2.jpg","alt"=>"nimetu 2","index"=>2),
		array("path"=>"pildid/nameless3.jpg","alt"=>"nimetu 3","index"=>3),
		array("path"=>"pildid/nameless4.jpg","alt"=>"nimetu 4","index"=>4),
		array("path"=>"pildid/nameless5.jpg","alt"=>"nimetu 5","index"=>5),
		array("path"=>"pildid/nameless6.jpg","alt"=>"nimetu 6","index"=>6),
	);
	require_once('head.html');
	
	$param = "";
	
	if(!empty($_GET['page'])){
		$param = $_GET['page'];
	} else {
		$param = "pealeht";
	}
	
	switch($param){
		case "pealeht":
			include('pealeht.php');
		break;
		case "galerii":
			include('galerii.php');
		break;
		case "vote":
			include('vote.php');
		break;
		case "tulemus":
			include('tulemus.php');
		break;
	}
	
	require_once('foot.html');
	
?>