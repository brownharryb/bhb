<div id="admin_container">
<header>
<ul class="midpage">
	<li><?php echo SITE_NAME; ?> Administration</li>

	<?php if(isset($_SESSION['admin_login']) && $_SESSION['admin_login']==true){?>
		<li style="float:right;text-transform:uppercase;font-size:10px;"><a href="<?php echo get_full_url_from_named_url('admin_logout'); ?>">logout</a></li>
		<div class="clearfix"></div>
	<?php } ?>	
</ul>	
	<hr style="margin:5px 0px;">
</header>
<?php require_once $globs['admin_path']; ?>
</div>
