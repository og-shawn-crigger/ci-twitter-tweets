<ul class="tabs">
	<li>Screennames</li>
	<li>User ids</li>
	<li>Hashtags</li>
	<li>Keywords</li>
</ul>
<ul class="tab-contents">
	<li>
			<table class="grid">	    
			    <?php if (! empty($results['screen_names'])):
			    foreach ($results['screen_names'] as $k=>$row): ?>
			     	<tr>
			     		<td><?php echo $row->value;?></td>
			     		<td><?php echo $row->anchor;?></td>
			     	</tr>
				<?php endforeach; else: ?>
					<tr><td><?php echo sprintf($this->lang->line('no_records_found'),'screen names'); ?></td></tr>
				<?php endif;?>
			</table>	
	</li>
	<li>
			<table class="grid">	    
			    <?php if (! empty($results['user_ids'])):
			    foreach ($results['user_ids'] as $k=>$row): ?>
			     	<tr>
			     		<td><?php echo $row->value;?></td>
			     		<td><?php echo $row->anchor;?></td>
			     	</tr>
				<?php endforeach; else: ?>
					<tr><td><?php echo sprintf($this->lang->line('no_records_found'),'user ids'); ?></td></tr>
				<?php endif;?>
			</table>
	</li>
	<li>
			<table class="grid">	    
			    <?php if (! empty($results['hashtags'])):
			    foreach ($results['hashtags'] as $k=>$row): ?>
			     	<tr>
			     		<td><?php echo $row->value;?></td>
			     		<td><?php echo $row->anchor;?></td>
			     	</tr>
				<?php endforeach; else: ?>
					<tr><td><?php echo sprintf($this->lang->line('no_records_found'),'hashtags'); ?></td></tr>
				<?php endif;?>
			</table>
	</li>
	<li>
			<table class="grid">	    
			    <?php if (! empty($results['keywords'])):
			    foreach ($results['keywords'] as $k=>$row): ?>
			     	<tr>
			     		<td><?php echo $row->value;?></td>
			     		<td><?php echo $row->anchor;?></td>
			     	</tr>
				<?php endforeach; else: ?>
					<tr><td><?php echo sprintf($this->lang->line('no_records_found'),'keywords'); ?></td></tr>
				<?php endif;?>
			</table>
	</li>
</ul>