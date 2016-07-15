<?php 
/**
* 
*/
require_once 'admin_view.php';
class AdminAddModels extends \AdminView
{
	private $matches;

	function __construct($function_to_call='',$matches='')
	{
		$this->matches = $matches;
		parent::__construct($function_to_call);

	}

	function modelsAdd(){
		require_once __DIR__.'/../forms/adminaddform.php';
		$form_class = new AdminAddForm;
		$post_data = $_POST;
		try{
			
			$site_model = $this->matches['site_model'][0];
			$model = '';
			$dbhelper = new DbHelper;
			$all_data = $dbhelper->get_all_in($site_model);
			$admin_path = get_templates_directory('admin').'/model_add.php';
			foreach ($this->admin_models as $val) {
				if($val->get_table_name()==$site_model){
					$model = $val;
				}
			}
			if($model==''){
				redirect404();
			}else{
				$form_class->set_model($model);
				$form = $form_class->get_admin_form();
				if($post_data!=[]){  //form is bound
					// check valid form
					$form= $form_class->validate_admin_data_on_form($form,$post_data);
					if($form==""){//form is valid
						$this->addDataToDatabase($form_class->get_valid_post_data());
						return;
					}
				}				

				$globs = array('admin_path'=>$admin_path,'model'=>$model,'form'=>$form);
			}
			$this->show_admin_view($globs);

		}catch(Exception $e){
			redirect404();// Function from settings.php
		}
	}


	function addDataToDatabase($post_data){

	}



}
 ?>