
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title;?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo get_static_url();?>css/main_style.css">
	<!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> -->
	<?php if(isset($globs['extra_css'])){echo $globs['extra_css'];}?>
</head>
<body>
<div id="bodywrap">
<?php if(isset($globs['page_body'])){require $globs['page_body'];}?>
</div>
<script type="text/javascript" src="<?php echo get_static_url();?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo get_static_url();?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo get_static_url();?>js/main_script.js"></script>
<?php if(isset($globs['extra_js'])){echo $globs['extra_js'];}?>
</body>
<footer>
	<?php echo $page_footer;?>
</footer>
</html>