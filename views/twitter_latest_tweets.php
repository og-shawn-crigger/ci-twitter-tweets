<h3>Latest tweets by <?php echo $title;?></h3>
<ul class="twitter">
    <?php foreach($tweets as $k => $tweet): ?>
	<li>
		<img src="http://api.twitter.com/1/users/profile_image?screen_name=<?php echo $tweet['screen_name'];?>" alt="<?php echo $tweet['screen_name'];?>"/>
		<a href="<?php echo 'https://twitter.com/' . $tweet['screen_name'] . '/status/' . $tweet['id_str'];?>" target="_blank"><?php echo $tweet['screen_name'];?></a><br />
	    <?php echo $tweet['text']; ?><br />	    
	    <em><?php echo date("l M j \- g:ia",strtotime($tweet['created_at'])); ?></em><br />
	</li>
    <?php endforeach; ?>
</ul>