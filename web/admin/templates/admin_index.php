
<?php 
$models = (isset($globs['models']))? $globs['models']:'';
?>

<div class="midpage">
	<h3>Site Models</h3>
	<hr style="margin:5px 0px;border:1px solid #ddd;">
	<div id="models_container">
		<ul>
		<?php
				foreach ($models as $val) { 
					if($val->show_in_admin()==true){ 
						$name = ucfirst($val->get_table_name());
						$link = $val->get_view_link();
						?>								
					<li><a href="<?php echo $link; ?>"><?php echo $name; ?></a></li>
			<?php } 
		}?>
		</ul>
	</div>	
</div>