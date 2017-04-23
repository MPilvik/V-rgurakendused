<?php if(!empty($_GET["q"]) && is_numeric($_GET["q"])): ?>
	<table border="1">
		<?php for($r=0; $r<$_GET["q"]; $r++): ?>
			<tr>
				<?php for($c=0; $c<$_GET["q"]; $c++): ?>
					<td>
						<?php echo "$r - $c"; ?>
					</td>
				<?php endfor; ?>
			</tr>
		<?php endfor; ?>
	</table>
<?php else: ?>

	<p>
		<?php echo "Palun sisesta number!" ?>
	</p>
<?php endif; ?>
<html>
<head>
	<meta charset="utf-8"/>
</head>
<body>
<form action="numbriline.php" method="GET">
	<input type="number" name="q"/>
	<input type="submit" name="s" value="esita!"/>
</form>
</body>
</html>