<?php 
/**
* 
*/
require_once 'admin_view.php';
class AdminViewModels extends \AdminView
{
	private $matches;
	
	function __construct($function_to_call='',$matches='')
	{
		$this->matches = $matches;
		parent::__construct($function_to_call);		
	}

	function modelsView(){
		try{
			$site_model = $this->matches['site_model'][0];
			$model = '';

			$dbhelper = new DbHelper;

			$all_data = $dbhelper->get_all_in($site_model);
			// echo "function called";
			$admin_path = get_templates_directory('admin').'/model_view.php';
			foreach ($this->admin_models as $val) {
				if($val->get_table_name()==$site_model){
					$model = $val;
				}
			}
			if($model==''){
				redirect404();
			}else{
				$globs= array('admin_path'=>$admin_path,'model'=>$model,'model_data'=>$all_data);
			}
			$this->show_admin_view($globs);
		}catch(Exception $e){
			redirect404();
		}
	}
}
 ?>