
	<h3>Fotod</h3>
	<div id="gallery">
	
		<?php foreach($gallery as $image):?>
			<img src=<?php echo $image['path'];?> alt=<?php echo $image['alt'];?>/>
		<?php endforeach;?>
		
	</div>
