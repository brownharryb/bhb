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
	}

	function set_id_for_edit($form,$id){
		$form .= '<input type="hidden" value="'.$id.'" name="model_id">';
		return $form;
	}


	function get_admin_form(){
		return $this->get_model_form($this->model);
	}
}

 ?>