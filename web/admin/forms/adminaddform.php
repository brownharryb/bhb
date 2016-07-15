<?php 
/**
* 
*/
class AdminAddForm extends \BaseForms
{
	protected $model;
	protected $form_fields = array(
		);
	
	function __construct()
	{
		parent::__construct();

	}

	function set_model($model){
		$this->model = $model;
		if($model->get_columns_exec()!=''){
			$this->req_info =  $model->get_columns_exec();
		}
	}

	function set_id_for_edit($form,$id){
		$form .= '<input type="hidden" value="'.$id.'" name="model_id">';
		return $form;
	}


	function get_admin_form($model_data='',$form_error_msg='',$tag_error_msgs=array()){
		// return $this->get_form($model_data,$form_error_msg,$tag_error_msgs);
		return $this->get_model_form($this->model);
	}
}

 ?>