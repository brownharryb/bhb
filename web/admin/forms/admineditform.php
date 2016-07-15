<?php 

/**
* 
*/
class AdminEditForm extends \BaseForms
{	
	protected $model;
	function __construct()
	{
		
	}

	function set_model($model){
		$this->model = $model;
		if($model->get_columns_exec()!=''){
			$this->req_info =  $model->get_columns_exec();
		}
	}

	function get_id_form_for_edit($id){
		return '<input type="hidden" value="'.$id.'" name="model_id">';
		
	}

	function get_admin_form($model_data){
		return $this->get_model_form($this->model,$model_data);
	}
}
 ?>