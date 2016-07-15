<?php 


class BaseForms
{
	protected $model = '';
	private $model_id = 0;
	protected $req_info="";
	protected $excluded_columns = array('id');
	protected $function_map=array(
		'checkbox'=>['func'=>'get_boolean_tag','validation'=>'validate_boolean_tag'],
		'textarea'=>['func'=>'get_longtext_tag','validation'=>'validate_longtext_tag'],
		'int'=>['func'=>'get_int_tag','validation'=>'validate_int_tag'],
		'text'=>['func'=>'get_varchar_tag','validation'=>'validate_varchar_tag'],
		'email'=>['func'=>'get_email_tag','validation'=>'validate_email_tag'],
		'password'=>['func'=>'get_password_tag','validation'=>'validate_password_tag'],
		'date'=>['func'=>'get_date_time_tag','validation'=>'validate_date_time_tag']	
		);
	protected $form_function_map=[];
	protected $valid_post_data = array();
	function __construct()
	{
		$this->function_map = array_merge($this->function_map,$this->form_function_map);
		
	}

	protected function get_function_map(){
		return $this->function_map;
	}



	// function get_form($model_data='',$form_error_msg='',$tag_error_msgs=array()){
	// 	$returned_form = $this->set_csrf_token();
	// 	$initial_val = '';
	// 	if($this->req_info!=''){
	// 		// this checks if data is available for editing
	// 		if($model_data!='' && $model_data[0]['id']>0){
	// 			$returned_form .= $this->get_id_form_for_edit($model_data[0]['id']);
	// 		}
			
	// 		foreach ($this->req_info as $req_info_key => $req_info_value) {
	// 			if(!array_key_exists('form_type', $req_info_value)){continue;}
	// 			foreach ($this->function_map as $map_key => $map_value) {
	// 				$initial_val = '';
	// 				if($req_info_value['form_type']==$map_key){
	// 					if($model_data!=''){							
	// 						if(array_key_exists($req_info_key, $model_data[0])){
	// 							$initial_val = $model_data[0][$req_info_key];
	// 						}
	// 					}
	// 					$functn = $map_value['func'];
	// 					if (array_key_exists($req_info_key, $tag_error_msgs)) {
	// 						$returned_form.=$this->$functn(array($req_info_key=>$req_info_value),$initial_val,$tag_error_msgs[$req_info_key]);
	// 					}else{
	// 						$returned_form.=$this->$functn(array($req_info_key=>$req_info_value),$initial_val);
	// 					}
	// 					break;
	// 				}
	// 			}
	// 		}
	// 		return $returned_form;
	// 	}
	// }

	function get_bound_form($model, $data_error_array){
		print_r($data_error_array);
		$function_map = $this->get_function_map();
		$returned_form = $this->set_csrf_token();
		$model_form_info = $model->get_columns_exec();

		foreach ($model_form_info as $model_form_info_key => $model_form_info_value) {
			if(array_key_exists("form_type", $model_form_info_value)){
				foreach ($function_map as $function_map_key => $function_map_value) {
					if($model_form_info_value['form_type'] == $function_map_key){
						$tag_function = $function_map_value['func'];
						foreach ($data_error_array as $data_error_array_key => $data_error_array_value) {
							if($data_error_array_value['form_key']==$model_form_info_key){
								$returned_form .= $this->$tag_function([$model_form_info_key=>$model_form_info_value],$data_error_array_value['form_val'],$data_error_array_value['error_msg']);

							}						
						}

					}

				}

			}

		}
		return $returned_form;

	}

	function get_model_form($model,$model_data=""){
		$function_map = $this->get_function_map();
		$returned_form = $this->set_csrf_token();
		$model_form_info = $model->get_columns_exec();


		// this checks if data is available for editing
		if($model_data!='' && $model_data[0]['id']>0){
			$returned_form .= $this->get_id_form_for_edit($model_data[0]['id']);
		}


		foreach ($model_form_info as $model_form_info_key => $model_form_info_value) {
			if(array_key_exists("form_type", $model_form_info_value)){
				foreach ($function_map as $function_map_key => $function_map_value) {
					if($model_form_info_value['form_type'] == $function_map_key){
						$tag_function = $function_map_value['func'];
						if($model_data==""){
							$returned_form.=$this->$tag_function([$model_form_info_key=>$model_form_info_value]);
						}elseif(array_key_exists($model_form_info_key, $model_data[0])){
							$returned_form.=$this->$tag_function([$model_form_info_key=>$model_form_info_value],$model_data[0][$model_form_info_key]);
						}												
					}
				}
			}
			
		}
		return $returned_form;
	}



	function set_csrf_token(){
		$csrf = $_SESSION['session_token'];
		return '<input type="hidden" value="'.$csrf.'" name="csrf_token">';
	}



// **************************************VALIDATE A BOUND ADMIN FORM********************************
	function validate_admin_data_on_form($form,$post_data){
		$form_ok = false;
		if(!array_key_exists('csrf_token', $post_data) || $_SESSION['session_token'] != $post_data['csrf_token']){
			$form =  $this->add_error_to_form($form,'Invalid session try again!!');
		}else{
			$form = $this->validate_all($form,$post_data);

		}
		return $form;
	}

	function validate_all($form,$post_data){
		if(!is_string($this->model)){
			$form = $this->model_validation($form,$post_data,$this->model);
		}		
		// $form = $this->get_form('','',['created'=>'not a good password']);
		return $form;
	}

// MODEL VALIDATION (IF SET)*********
	function model_validation($form,$post_data,$model){
		$error_msg = '';
		$column_exec = $model->get_column_exec();
		$function_map = $this->get_function_map();
		$data_error_array = array();
		$error_available = false;

		foreach ($post_data as $post_data_key => $post_data_value) {
			foreach ($column_exec as $column_exec_key => $column_exec_value) {
				$error_msg='';
				if($post_data_key==$column_exec_key){

					// validates from specified model validation function
					if (array_key_exists("validation", $column_exec_value)) {
						$validation_model_func = $column_exec_value['validation'];
						$error_msg = $model->$validation_model_func($post_data_value);
						
					}

					// validates from specified tag validation function
					if(array_key_exists("form_type", $column_exec_value)){
						foreach ($function_map as $function_map_key => $function_map_value) {
							if($column_exec_value['form_type']==$function_map_key){
								$validation_form_func = $function_map_value['validation'];
								$error_msg = $this->$validation_form_func($post_data_value);
								
							}
						}
					}
					if($error_msg!=""){
						$error_available=true;						
					}
					$data_error_array[] = array('form_key'=>$post_data_key, 'form_val'=>$post_data_value,'error_msg'=>$error_msg);

				}



			}
			
		}

		if($error_available){
			$form = $this->get_bound_form($model,$data_error_array);
		}else{
			$form="";
		}

		return $form;
	}


	function get_valid_post_data(){
		return $this->valid_post_data;
	}

// FORM FIELD VALIDATION (IF SET)********************************
	function form_field_validation($form,$post_data,$form_fields){

	}


// *********************

	function add_error_to_tag($tag,$error_msg){
		$tag .= '<span class="form_tag_error">'.$error_msg.'</span>';
		return $tag;
	}
	function add_error_to_form($form,$error_msg){
		$form .= '<p class="form_error">'.$error_msg.'</p>';
		return $form;
	}

// *************************************** INT*****************************************
// ******TAG*******
	function get_int_tag($args='',$initial_val='',$error_msg=''){		
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}
		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';
		$attr .= 'value="'.$initial_val.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<p><input type='text' ".$attr." > ";

		if($error_msg != ''){
			$tag = $this->add_error_to_tag($tag,$error_msg);
		}else{
			$tag .= "Only Number Required";
		}
		$tag .= "</p>";
		return $tag;
	}
// ********VALIDATION********
	function validate_int_tag($data){
		if (!is_numeric($data)) {
			return "Please enter a valid number!";
		}

	}

// ************************************** VARCHAR **********************************************
	function get_varchar_tag($args='',$initial_val='',$error_msg=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';
		$attr .= 'value="'.$initial_val.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='text' ".$attr.">";
		if($error_msg != ''){
			$tag = $this->add_error_to_tag($tag,$error_msg);
		}
		$tag .= "</p>";
		return $tag;

	}
// ********VALIDATION********
	function validate_varchar_tag($data){
		$aValid = array('-', '_');

		if(!ctype_alnum($data)){
			return "Only numbers and letters allowed!";
		} 
	}
// *****************************************EMAIL****************************************************

	function get_email_tag($args='',$initial_val='',$error_msg=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';
		$attr .= 'value="'.$initial_val.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='email' ".$attr.">";
		if($error_msg != ''){

			$tag = $this->add_error_to_tag($tag,$error_msg);
		}
		$tag .= "</p>";
		return $tag;
	}
// ********VALIDATION********
	function validate_email_tag($data){
		return "This is not ok";
	}
// *****************************************LONGTEXT**********************************************
	function get_longtext_tag($args='',$initial_val='',$error_msg=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'value="'.$initial_val.'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';

		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<textarea style='min-width:100px;min-height:100px;'".$attr."></textarea>";
		if($error_msg != ''){
			$tag = $this->add_error_to_tag($tag,$error_msg);
		}
		$tag .= "</p>";
		return $tag;
	}
// ********VALIDATION********
	function validate_longtext_tag($data){
		
	}
// ****************************************DATE TIME ********************************************
	function get_date_time_tag($args='',$initial_val='',$error_msg=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'value="'.$initial_val.'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='date' ".$attr.">";
		if($error_msg != ''){
			$tag = $this->add_error_to_tag($tag,$error_msg);
		}
		$tag .= "</p>";
		return $tag;
	}
// ********VALIDATION********
	function validate_date_time_tag($data){
		
	}
// *******************************************BOOLEAN*********************************************
	
	function get_boolean_tag($args='',$initial_val='',$error_msg=''){
		$attr= '';
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		if (in_array($initial_val, [1,'checked',true])){
			$attr .= "checked";
		}
		$tag = "<p><input type='checkbox'".$attr."><span>".$display_name."</span></p>";
		if($error_msg != ''){
			$tag = $this->add_error_to_tag($tag,$error_msg);
		}
		return $tag;
	}
// ********VALIDATION********
	function validate_boolean_tag($data){
		
	}
// **************************************** PASSWORD **************************************
	function get_password_tag($args='',$initial_val='',$error_msg=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'id="form_id_'.$args_key.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}

		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='password' ".$attr.">";
		if($initial_val!=''){
			$tag .="<span style='color:#aaa;font-size:11px;'> Leave this empty for the same password</span>";
		}		
		if($error_msg != ''){
			$tag = $this->add_error_to_tag($tag,$error_msg);
		}
		$tag .= "</p>";
		return $tag;
	}
// ********VALIDATION********
	function validate_password_tag($data){
		
	}

}


?>