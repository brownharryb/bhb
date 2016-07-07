<?php
$model = (isset($globs['model']))?$globs['model']:'';
$link_to_add = $model->get_add_link();

?>
<div id="model_view_container" class="midpage">
	<ul id="sub_title_container">
		<li><span style="font-size:15px;font-weight:bold;"><?php echo strtoupper($model->get_display_name());
			?></span></li>
		<li><a href="<?php echo $link_to_add; ?>">Add</a></li>
	</ul>
	<div class="clearfix"></div>	
	<hr style="border:1px solid #ddd;margin:5px 0px;">

	<div id="model_info_container">
		<form method="post">
		<div id="batch_operation">
				<label for="select_all">Select All</label>
				<input id="select_all_check_box" class="subitems" type="checkbox" name="select_all">		
				
				<select class="subitems">
					<option>Select Action</option>
					<option>Delete</option>
				</select>
				<input class="subitems" type="submit" name="submit" value="GO">
		</div>
		<hr style="border:1px solid #ddd;margin:5px 0px;">
		<div id="model_table_container">	
		<table id="model_info_table">
			<tr>
			<?php	echo '<th></th><th>Id</th>';
				foreach($model->get_all_columns() as $val){?>
					<th><?php echo ucfirst($val); ?></th>
				  <?php }?>
			</tr>




			<?php 

			if(isset($globs['model_data']) && $globs['model_data']!=array()){
				foreach ($globs['model_data'] as $key=>$val){ 
					$model_match = array(
										'site_model'=>$model->get_table_name(),
										'site_model_id'=>$val['id']
										);
					$link = get_full_url_from_named_url('admin_site_model_edit', $model_match);

					echo '<tr onclick="window.document.location='.$link.';">';
					echo '<td><input class="each_item_checkbox" type="checkbox" name="idforitem_'.$val["id"].'"></td>';
						
							foreach ($val as $each_key=>$each_val) {
								if(is_numeric($each_key))continue;
								if($each_key=='id') {
									
									echo '<td><a href="'.$link.'">'.$each_val.'</a></td>';
								}else{
									echo '<td>'.$each_val.'</td>';
								}

								
							}
					
					echo '</tr>';
				 }

				 } ?>





		</table>
		<hr style="border:1px solid #bbb;margin:20px 0px;">
		</div>
		</form>
		
	</div>
</div>