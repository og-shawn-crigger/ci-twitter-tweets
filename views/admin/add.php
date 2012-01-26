<?php echo form_open(current_url(), $attributes); ?>
<p class="formfield">
	<label for="type">Select type:</label>
	<select name="type" id="type">
		<option selected="selected" value="hashtag">hashtag</option>
		<option value="keyword">keyword</option>
		<option value="twitteruser">user</option>
	</select>				
</p>
<p class="formfield">
    <label for="value">Enter Twitter hashtag, user (id or screenname) or keyword value</label>
    <input id="value" type="text" name="value" maxlength="255" value="<?php echo set_value('value'); ?>" />
</p>
<?php echo form_submit($submitoptions);
echo form_close();?>