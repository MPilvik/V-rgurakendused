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
?>	
	
	<h3>Vali oma lemmik :)</h3>
	<form action="tulemus.php" method="GET">
		
		<?php foreach($gallery as $image):?>
			<p>
				<label for="p<?php echo $image['index'];?>">
					<img src=<?php echo $image['path'];?> alt=<?php echo $image['alt'];?> height="100"/>
				</label>
				<input type='radio' value="<?php echo $image['index'];?>" id="p<?php echo $image['index'];?>" name="pilt"/>
			</p>
		<?php endforeach;?>
		
		<br/>
		<input type="submit" value="Valin!"/>
	</form>

<?php 
	require_once('foot.html');
?>