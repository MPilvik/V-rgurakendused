<?php
if(!empty($_POST)){
	if(!empty($_POST["q"])){
		$q = urlencode($_POST['q']);
		header("Location: https://www.google.ee?#q={$q}");
		exit(0);
	} else {
		echo "Palun sisesta otsingusõna!";
	}
}
?>

<html>
<head>
	<meta charset="utf-8"/>
</head>
<body>
<form action="minuGoogle.php" method="post">
	<input type="text" name="q"/>
	<input type="submit" name="s" value="Otsi Google'ist"/>
</form>
</body>
</html>