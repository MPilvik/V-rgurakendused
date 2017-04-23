	<h3>Vali oma lemmik :)</h3>
	<form action="?page=tulemus" method="POST">
		
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