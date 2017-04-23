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
	
	<h3>Fotod</h3>
	<div id="gallery">
	
		<?php foreach($gallery as $image):?>
			<img src=<?php echo $image['path'];?> alt=<?php echo $image['alt'];?>/>
		<?php endforeach;?>
		
	</div>

<?php
	require_once('foot.html');
?>