<?php 
/**
* 
*/
require_once 'admin_view.php';
class AdminEditModels extends \AdminView
{
	private $matches;

	function __construct($function_to_call='',$matches='')
	{
		$this->matches = $matches;
		parent::__construct($function_to_call);
		

	}

	function modelsEdit(){
		require_once __DIR__.'/../forms/admineditform.php';
		$form_class = new AdminEditForm;

		try{
			$site_model = $this->matches['site_model'][0];
			$site_model_id = $this->matches['site_model_id'][0];
			$model = '';
			$dbhelper = new DbHelper;
			$admin_path = get_templates_directory('admin').'/model_edit.php';
			foreach ($this->admin_models as $val) {
				if($val->get_table_name()==$site_model){
					$model = $val;
					$model_data = $model->get_object_data_from_id($site_model_id);
				}
			}
			if($model!='' && $model_data != array()){
				$form_class->set_model($model);
				$form = $form_class->get_admin_form($model_data);
				$globs = array('admin_path'=>$admin_path,'model'=>$model,'model_data'=>$model_data,'form'=>$form);
			}
			// else{
			// 	throw new Exception('');				
			// }
			$this->show_admin_view($globs);

		}catch(Exception $e){
			redirect404();// Function from settings.php
		}
	}
}
 ?>