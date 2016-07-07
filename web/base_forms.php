<?php 


class BaseForms
{
	protected $model = '';
	private $model_id = 0;
	private $req_info="";
	protected $excluded_columns = array('id');
	private $function_map=array(
		'checkbox'=>['func'=>'get_boolean_tag','validate'=>'validate_boolean'],
		'textarea'=>['func'=>'get_longtext_tag','validate'=>'validate_longtext'],
		'int'=>['func'=>'get_int_tag','validate'=>'validate_int'],
		'text'=>['func'=>'get_varchar_tag','validate'=>'validate_varchar'],
		'email'=>['func'=>'get_email_tag','validate'=>'validate_email'],
		'password'=>['func'=>'get_password_tag','validate'=>'validate_password'],
		'date'=>['func'=>'get_date_time_tag','validate'=>'validate_date']	
		);
	protected $form_function_map=[];
	function __construct()
	{
		$this->function_map = array_merge($this->function_map,$this->form_function_map);
		
	}
	function set_model($model){
		$this->model = $model;
		if($model->get_columns_exec()!=''){
			$this->req_info =  $model->get_columns_exec();
		}
	}


	// function set_model_data($model_data){
	// 	$this->model_data = $model_data;
	// }

	// function get_model_data(){
	// 	return $this->model_data;
	// }

	function set_csrf_token(){
		$csrf = $_SESSION['session_token'];
		return '<input type="hidden" value="'.$csrf.'" name="csrf_token">';
	}
	function set_id_for_edit($form,$id){
		$form .= '<input type="hidden" value="'.$id.'" name="model_id">';
		return $form;
	}

// *********************************BUILD FORMS FROM FUNCTIONS********************************
	function get_form($model_data=''){
		$returned_form = $this->set_csrf_token();
		$initial_val = '';
		if($this->req_info!=''){
			if($model_data!='' && $model_data[0]['id']>0){
				$returned_form = $this->set_id_for_edit($returned_form,$model_data[0]['id']);
			}
			
			foreach ($this->req_info as $req_info_key => $req_info_value) {
				if(!array_key_exists('form_type', $req_info_value)){continue;}
				foreach ($this->function_map as $map_key => $map_value) {
					if($req_info_value['form_type']==$map_key){
						if($model_data!=''){
							$initial_val = '';
							if(array_key_exists($req_info_key, $model_data[0])){
								$initial_val = $model_data[0][$req_info_key];
							}
						}
						$functn = $map_value['func'];
						$returned_form.=$this->$functn(array($req_info_key=>$req_info_value),$initial_val);
						break;
					}
				}
			}
			return $returned_form;
		}
	}

// **************************************VALIDATE A BOUND FORM********************************
	function is_valid($form,$post_data){
		if(!array_key_exists('csrf_token', $post_data) || $_SESSION['session_token'] != $post_data['csrf_token']){
			$form =  $this->add_error_to_form($form,'Invalid session try again!!');
		}else{
			$form = $this->validate_all($form,$post_data);
		}
		return $form;
	}

	function validate_all($form,$post_data){
		// FINISH THIS
		print_r($this->model->get_columns_exec());
		return $form;
	}

	function add_error_to_form($form,$error_msg){
		$form .= '<p style="color:red;font-size:11px;">'.$error_msg.'</p>';
		return $form;
	}

// *************************************** INT*****************************************
// ******TAG*******
	function get_int_tag($args,$initial_val=''){		
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}
		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<p><input type='text' ".$attr." > Only Number Required</p>";
		return $tag;
	}
// ************************************** VARCHAR **********************************************
	function get_varchar_tag($args,$initial_val=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'value="'.$initial_val.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='text' ".$attr."></p>";
		return $tag;

	}
// *****************************************EMAIL****************************************************

	function get_email_tag($args,$initial_val=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		$attr .= 'value="'.$initial_val.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='email' ".$attr."></p>";
		return $tag;

	}
// *****************************************LONGTEXT**********************************************
	function get_longtext_tag($args,$initial_val=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'value="'.$initial_val.'"';
		$attr .= 'name="'.$args_key.'"';

		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<textarea style='min-width:100px;min-height:100px;'".$attr."></textarea></p>";
		return $tag;
	}
// ****************************************DATE TIME ********************************************
	function get_date_time_tag($args,$initial_val=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'value="'.$initial_val.'"';
		$attr .= 'name="'.$args_key.'"';
		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='date' ".$attr."></p>";
		return $tag;
	}
// *******************************************BOOLEAN*********************************************
	
	function get_boolean_tag($args,$initial_val=''){
		$attr= '';
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$attr .= 'name="'.$args_key.'"';

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		if (in_array($initial_val, [1,'checked',true])){
			$attr .= "checked";
		}
		$tag = "<p><input type='checkbox'".$attr."><span>".$display_name."</span></p>";
		return $tag;
	}
// **************************************** PASSWORD **************************************
	function get_password_tag($args,$initial_val=''){
		$args_key = array_keys($args)[0];
		$args_values = array_values($args)[0];

		$display_name = $args_key;
		if(array_key_exists('verbose', $args_values)){
			$display_name = $args_values['verbose'];
		}

		$attr = 'placeholder="'.ucfirst($display_name).'"';
		$attr .= 'name="'.$args_key.'"';
		if(array_key_exists('max_length', $args_values)){
			$attr .= 'maxlength="'.$args_values['max_length'].'"';
		}

		$tag = "<p>";
		$tag .= "<span>".ucfirst($display_name).": </span>";
		$tag .= "<input type='password' ".$attr.">";
		if($initial_val!=''){
			$tag .="<span style='color:#aaa;font-size:11px;'> Leave this empty for the same password</span>";
		}
		$tag .= "<p>";
		return $tag;
	}

}


?>