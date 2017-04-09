<?php 
	$piirjoone_stiil="solid";
	if (isset($_POST['piirjoone_stiil']) && $_POST['piirjoone_stiil']!=""){
		$piirjoone_stiil=htmlspecialchars($_POST['piirjoone_stiil']);
	}
	$piirjoone_varv="black";
	if (isset($_POST['piirjoone_varv']) && $_POST['piirjoone_varv']!=""){
		$piirjoone_varv=htmlspecialchars($_POST['piirjoone_varv']);
	}
	$piirjoone_laius="1";
	if (isset($_POST['piirjoone_laius']) && $_POST['piirjoone_laius']!=""){
		$piirjoone_laius=htmlspecialchars($_POST['piirjoone_laius']);
	}
	$raadius="0";
	if (isset($_POST['raadius']) && $_POST['raadius']!=""){
		$raadius=htmlspecialchars($_POST['raadius']);
	}
	$tausta_varv="white";
	if (isset($_POST['tausta_varv']) && $_POST['tausta_varv']!="") {
		$tausta_varv=htmlspecialchars($_POST['tausta_varv']);
	}
	$teksti_suurus="18";
	if (isset($_POST['teksti_suurus']) && $_POST['teksti_suurus']!=""){
		$teksti_suurus=htmlspecialchars($_POST['teksti_suurus']);
	}
	$teksti_varv="black";
	if (isset($_POST['teksti_varv']) && $_POST['teksti_varv']!=""){
		$teksti_varv=htmlspecialchars($_POST['teksti_varv']);
	}
	$juhendtekst="Siia ilmub sinu tekst";
	if (isset($_POST['juhendtekst']) && $_POST['juhendtekst']!=""){
		$juhendtekst=htmlspecialchars($_POST['juhendtekst']);
	}	
?>


<!DOCTYPE html>
<html>
<head>
	<title>Ülesanne 8: vorm kasutaja stiiliga</title>
	<meta charset="utf-8">
	<style type="text/css">
		#valikud {
			border-style: <?php echo $piirjoone_stiil; ?>;
			border-color: <?php echo $piirjoone_varv; ?>;
			border-width: <?php echo $piirjoone_laius; ?>px;
			border-radius: <?php echo $raadius; ?>px;
			background-color: <?php echo $tausta_varv; ?>;
			font-size: <?php echo $teksti_suurus; ?>px;
			color: <?php echo $teksti_varv; ?>;
			margin: 10px;
			padding: 5px;
		}
		#vorm {
			border-style: solid;
			border-color: grey;
			border-width: 1px;
			margin: 10px;
			padding: 5px;
		}
	</style>
</head>
<body>
	<div>
	<div id="valikud">
		<?php echo $juhendtekst; ?>
	</div>
	<div id="vorm">
		<form action="kasutajaStiiligaVorm.php" method="post">
			<textarea name="juhendtekst">Sisesta siia oma tekst</textarea><br>
			<select name="piirjoone_stiil">
				<option>solid</option>
				<option>dashed</option>
				<option>dotted</option>
				<option>double</option>
			</select>Piirjoone tekst<br>
			<input type="color" name="piirjoone_varv"> Piirjoone värv<br>
			<input type="number" name="piirjoone_laius"> Piirjoone laius<br>
			<input type="number" name="raadius"> Piirjoone nurga raadius<br>
			<input type="color" name="tausta_varv"> Tausta värv<br>
			<input type="number" name="teksti_suurus"> Teksti suurus<br>
			<input type="color" name="teksti_varv"> Teksti värv<br>
			<input type="submit" value="Muuda"><br>
		</form>
	</div>	

</body>

</html>