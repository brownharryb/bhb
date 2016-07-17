<?php
/**
* 
*/
class AdminView{

	protected $app_name = "admin";
	protected $admin_models;
	private $title = "Admin";


	
	function __construct($function_to_call=''){
		
		$this->admin_models = $this->get_all_admin_registered_models();
		if($this->check_admin_loggedin()){

			$this->$function_to_call();
		}
	}

	function get_page_($name){
		if(isset($this->$name)){
			return $this->$name;
		}
	}
	// function get_static_files($matches){


	// }
// *********************************************************************************
function check_admin_loggedin(){
		if(!isset($_SESSION['admin_login']) || $_SESSION['admin_login']==false){
			redirect_to_absolute_url(get_url_from_named_url('admin_login'));
			return false;
		}else{
			return true;
		}
	}

	function setupDb(){
		$db_helper = new DbHelper;
		setup_db();
	}
	function get_all_admin_registered_models(){
		$admin_models = array();
		foreach (get_all_model_classes('objects') as $each_model) {
			if($each_model->show_in_admin()){
				$admin_models[] = $each_model;
			}
		}
		return $admin_models;
	}


	function show_admin_view($globs=''){
		if($globs != ''){
			$globs['extra_css'] = "<link rel='stylesheet' type='text/css' href='".get_static_url()."admin/css/admin_css.css'>";
			$globs['extra_js'] = "<script type='text/javascript' src='".get_static_url()."admin/js/admin_js.js'></script>";
			$globs['page_body'] = get_templates_directory('admin').'/admin_base.php';
			$globs['page_title'] = $this->get_page_('title');
			require_once get_base_html();
		}
	}


}

?>