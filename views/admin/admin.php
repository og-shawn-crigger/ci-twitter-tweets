<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Codeigniter twitter tweets manager</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/twittertweets.css" />
	<?php if (! $this->uri->segment(3) == 'add'):?>
	<script type="text/javascript" src="<?php echo base_url();?>js/mootools-core-1.4.2.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/messages.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/tinytab.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/tabs.js"></script>
	<?php endif;?>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if gte IE 9]>
		<style type="text/css"> .gradient,.gradient:hover,.gradient:active { filter: none;}</style>
	<![endif]-->
</head>
<body class="gradient">
	<nav id="top">
		<ul>
		<?php $last = end(array_keys($menu));
		foreach ($menu as $key => $item):?>
		<li <?php echo ($last == $key) ? 'class="last"' : '';?>><?php echo $item;?></li>
		<?php endforeach;?>
		</ul>
	</nav>	
	<section>
		<header>
			<h1>Codeigniter twitter tweets manager example.</h1>
			<?php if($this->session->flashdata('success')):?>
			<div class="success">
				<p><?php echo $this->session->flashdata('success');?></p>
			</div>
			<?php endif;?>
		
			<?php if($this->session->flashdata('error')):?>
			<div class="error">
				<p><?php echo $this->session->flashdata('error');?></p>
			</div>
			<?php endif;?>
			
			<?php if(validation_errors()):?>
			<div class="error form_errors">
				<?php echo validation_errors();?>
			</div>
			<?php endif;?>			
		</header>
		<article>
			<?php echo $content;?>
		</article>
		<footer>
			<nav>
				<ul>
				<?php $last = end(array_keys($footer));
				foreach ($footer as $key => $item):?>
				<li <?php echo ($last == $key) ? 'class="last"' : '';?>><?php echo $item;?></li>
				<?php endforeach;?>
				</ul>
			</nav>				
		</footer>
	</section>
</body>
</html>