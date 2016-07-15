<?php 

/**
* 
*/
require_once 'admin_view.php';
class AdminIndexView extends \AdminView
{
	private $matches;
	
	function __construct($function_to_call='',$matches='')
	{
		$this->matches = $matches;
		parent::__construct($function_to_call,$matches);
	}

	function index(){
		$admin_path = get_templates_directory('admin').'/admin_index.php';
		$models =$this->get_all_admin_registered_models();
		if($models!=array()){
			$globs = array('admin_path'=>$admin_path,'models'=>$models);
		}
		$this->show_admin_view($globs);
	}	
}


 ?>