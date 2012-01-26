<h3>Latest tweets by <?php echo $title;?></h3>
<ul class="twitter">
    <?php foreach($tweets->results as $k => $tweet): ?>
	<li><img src="<?php echo $tweet->profile_image_url;?>" alt="<?php echo $tweet->from_user_name;?>"/>
		<a href="<?php echo 'https://twitter.com/' . $tweet->from_user_name . '/status/' . $tweet->id_str;?>" target="_blank"><?php echo $tweet->from_user_name;?></a><br />
	    <?php echo $tweet->text; ?><br />
	    <em><?php echo date("l M j \- g:ia",strtotime($tweet->created_at)); ?></em><br />
	</li>
    <?php endforeach; ?>
</ul>