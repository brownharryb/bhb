<?php 

// TODO WRITE CODE TO VALIDATE EACH TAG

class BaseForms
{
	private $model_id = 0;
	protected $form_html="";
	// protected $req_info="";
	protected $main_function_map=array(
		'checkbox'=>['func'=>'get_boolean_tag','validation'=>['validate_boolean_tag']],
		'textarea'=>['func'=>'get_longtext_tag','validation'=>['validate_longtext_tag']],
		'int'=>['func'=>'get_int_tag','validation'=>['validate_int_tag']],
		'text'=>['func'=>'get_varchar_tag','validation'=>['validate_varchar_tag']],
		'email'=>['func'=>'get_email_tag','validation'=>['validate_email_tag']],
		'password'=>['func'=>'get_password_tag','validation'=>['validate_password_tag']],
		'date'=>['func'=>'get_date_time_tag','validation'=>['validate_date_time_tag']]	
		);
	protected $function_map=[];
	protected $valid_post_data = array();
	function __construct()
	{
		$this->function_map = array_merge($this->function_map,$this->main_function_map);
		
	}

	protected function get_function_map(){
		return $this->function_map;
	}

	function get_form_fields(){
		if(isset($this->form_fields)){
			return $this->form_fields;
		}
		else{
			return "";
		}
	}

	function set_csrf_token(){
		$csrf = $_SESSION['session_token'];
		return '<input type="hidden" value="'.$csrf.'" name="csrf_token">';
	}

	function get_form(){
		$form = '';
		if(isset($this->model)){
			$form = $this->get_model_form($this->model);
		}elseif(isset($this->form_fields)){
			$form = $this->get_fields_form();
		}
		$this->form_html= $form;
		return $form;
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
							$returned_form.=$this->$tag_function([$model_form_info_key=>$model_form_info_value],$model_data[0][$model_form_info_key]); //set initial value if present
						}												
					}
				}
			}
			
		}
		return $returned_form;
	}


	function get_bound_form($attrs, $data_error_array){
		$function_map = $this->get_function_map();
		$returned_form = $this->set_csrf_token();
		$model_form_info = $attrs;

		foreach ($model_form_info as $model_form_info_key => $model_form_info_value){
			if(array_key_exists("form_type", $model_form_info_value)){
				foreach ($function_map as $function_map_key => $function_map_value){
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

	function get_fields_form(){
		$function_map = $this->get_function_map();
		$returned_form = $this->set_csrf_token();
		if(isset($this->form_fields)){
			$form_fields = $this->form_fields;
		}else{
			throw new Exception("Form fields not found in class!!", 1);
			return;			
		}

		foreach ($form_fields as $form_fields_key => $form_fields_value) {
			if(array_key_exists('form_type', $form_fields_value)){
				foreach ($function_map as $function_map_key => $function_map_value) {
					if($form_fields_value['form_type'] == $function_map_key){
						$tag_function = $function_map_value['func'];
						$returned_form.= $this->$tag_function([$form_fields_key=>$form_fields_value]);
					}
				}
			}
		}
		return $returned_form;
	}

	function validate_form_with_form_field($form,$post_data){
		
	}
// ***************************************VALIDATIONS**********************************************
	function form_is_valid($post_data){
		// $form_html = $this->form_html;
		if(!$this->confirm_csrf($post_data)){
			return;
		}
		if(isset($this->model)){
			$attrs = $this->model->get_columns_exec();
		}
		elseif(isset($this->form_fields)){
			$attrs = $this->get_form_fields();
		}
		$form = $this->form_validation($post_data,$attrs);
		return $form;
	}

// TAG VALIDATION******************
	function tag_validation($data_array, $value_to_validate){
		$error_msg = "";
		$function_map = $this->get_function_map();

		if(array_key_exists("form_type", $data_array)){
			foreach ($function_map as $function_map_key => $function_map_value) {
				if($data_array['form_type']==$function_map_key){
					$validation_form_func_array = $function_map_value['validation'];
					foreach ($validation_form_func_array as $validation_form_func) {
						if (method_exists($this, $validation_form_func)){
							if($error_msg==""){
								$error_msg = $this->$validation_form_func($value_to_validate);
							}
						}
					}
				}
			}
		}
		return $error_msg;

	}

	function form_validation($post_data,$attrs){
		$error_msg = '';
		if(isset($this->model)){
			$f_class = $this->model;
		}elseif(isset($this->form_fields)){
			$f_class = $this;
		}
		$function_map = $this->get_function_map();
		$data_error_array = array();
		$error_available = false;

		foreach ($post_data as $post_data_key => $post_data_value) {
			foreach ($attrs as $attrs_key => $attrs_value) {
				$error_msg='';
				if($post_data_key==$attrs_key){

					// validates from specified class validation function
					if (array_key_exists("validation", $attrs_value)) {
						$validation_func_array = $attrs_value['validation'];
						foreach ($validation_func_array as $validation_func) {						
							$error_msg = $f_class->$validation_func($post_data_value);
						}						
					}

					// validates from specified tag validation function
					if($error_msg==""){
						$error_msg = $this->tag_validation($attrs_value,$post_data_value);
					}
					if($error_msg!=""){
						$error_available=true;						
					}
					$data_error_array[] = array('form_key'=>$post_data_key, 'form_val'=>$post_data_value,'error_msg'=>$error_msg);
				}
			}
			
		}

		if($error_available){
			$form="";
			$form = $this->get_bound_form($attrs,$data_error_array);
		}else{
			$form="ok";
		}
		return $form;
	}

	// FORM FIELD VALIDATION (IF SET)********************************
	function form_field_validation($post_data){
		$error_msg = '';
		$function_map = $this->get_function_map();
		$form_fields = $this->form_fields;
		$data_error_array = array();
		$error_available = false;

		foreach ($post_data as $post_data_key => $post_data_value) {
			foreach ($form_fields as $form_fields_key => $form_fields_value) {
				if($post_data_key == $form_fields_key){

				}
			}			
		}

	}	

// **************************************VALIDATE A BOUND ADMIN FORM********************************
	function validate_admin_data_on_form($post_data){
		if($this->confirm_csrf($post_data)){
			$form = $this->form_is_valid($post_data);
			return $form;
		}
	}

	function confirm_csrf($post_data){
		if(!array_key_exists('csrf_token', $post_data) || $_SESSION['session_token'] != $post_data['csrf_token']){
			redirect404();			
			return false;
		}
		return true;
	}

	function get_valid_post_data(){
		return $this->valid_post_data;
	}

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