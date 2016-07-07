<?php
$model = $globs['model'];
$model_data = $globs['model_data'];
$model_id = $model_data[0]['id'];
$form = $globs['form'];
?>


<div id="body_content" class="midpage">
	<ul id="sub_title_container">
		<li><h3><?php echo strtoupper($model->get_display_name());?> </h3></li>
		<li>Edit [Id=<?php echo $model_id;?>]</li>
	</ul>
	<div class="clearfix"></div>	
	<hr style="border:1px solid #bbb;margin:20px 0px;">
	<div id="model_add_edit_container">
		<form method="post">
			<?php echo $form; ?>
			<input type="submit" value="Submit">
		</form>
	</div>
	

</div>
</div>

