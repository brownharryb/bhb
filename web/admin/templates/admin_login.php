
<div id="admin_login_container" class="center_div">
	<h3>Login</h3>
	<form method="post" action="<?php echo get_full_url_from_named_url('admin_login'); ?>">
		<input type="hidden" name="csrf_token" value="<?php echo $globs['csrf_token']; ?>">
		<input id="admin_username" type="text" name="username" placeholder="Username">
		<input id="admin_password" type="password" name="password" placeholder="Password">
		<?php if(isset($globs['admin_error'])){?>
			<p id="admin_error_login"><?php echo $globs['admin_error']; ?></p>
		<?php } ?>
		
		<input id="admin_login_submit" type="submit" name="submit" value="Login">		
	</form>
</div>