<?php

	$lauljad= array( 
		array('laulja'=>'Piret', 'haaleryhm'=>'II alt', 'koorituleku aasta'=>'2013'), 
		array('laulja'=>'Marili', 'haaleryhm'=>'II alt', 'koorituleku aasta'=>'2014'), 
		array('laulja'=>'Triin', 'haaleryhm'=>'I alt', 'koorituleku aasta'=>'2014'), 
		array('laulja'=>'Karoliine', 'haaleryhm'=>'II sopran', 'koorituleku aasta'=>'2015'), 
		array('laulja'=>'Karoliina', 'haaleryhm'=>'I sopran', 'koorituleku aasta'=>'2010'), 
	);
	
	include 'algus.html';
	foreach ($lauljad as $laulja) {
		include 'lauljad.html';
	}
	include 'l6pp.html';
?>

