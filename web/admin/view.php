<?php
/**
* 
*/
class View extends \BaseViews{

	private $page_body = "";
	private $page_title = "Admin Title";
	private $page_footer = "Page footer";
	public $admin_css = '';
	public $admin_js = '';
	protected $app_name = "admin";

	private $all_models=array();


	
	function __construct($function_to_call='',$matches=''){
		parent::__construct();
		// $this->all_models = get_all_model_classes('objects');
		foreach (get_all_model_classes('objects') as $each_model) {
			if($each_model->show_in_admin()){
				$this->all_models[] = $each_model;
			}
		}
		

		$page_title= $this->page_title;
		$page_footer = $this->page_footer;

		if(!isset($_SESSION['admin_login']) || $_SESSION['admin_login']==false){

			$globs= $this->login();

		}else{

			$globs = $this->$function_to_call($matches);

		}


		// if ($function_to_call!=''){



		// }

		if(isset($globs)){
			$globs['extra_css'] = "<link rel='stylesheet' type='text/css' href='".get_static_url()."admin/css/admin_css.css'>";
			$globs['extra_js'] = "<script type='text/javascript' src='".get_static_url()."admin/js/admin_js.js'></script>";
			$globs['page_body'] = get_templates_directory('admin').'/admin_base.php';
			require_once get_base_html();
		}

	}

	function validate_login($username, $password){
		return true;
	}	


	function login(){
		$csrf = $_SESSION['session_token'];
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['csrf_token'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$token = $_POST['csrf_token'];
			if($this->validate_login($username,$password) && $csrf==$token){
				$_SESSION['admin_login']=true;
				redirect_to_absolute_url(get_url_from_named_url('admin_index'));
			}
			else{
				echo 'error';
				$admin_error = 'Invalid login details';
				return array('admin_path'=>get_templates_directory('admin').'/admin_login.php','csrf_token'=>$csrf,'admin_error'=>$admin_error);
			}
		}
		else{
			return array('admin_path'=>get_templates_directory('admin').'/admin_login.php','csrf_token'=>$csrf);
		}
	}

	function logout(){
		session_destroy();
		redirect_to_absolute_url(get_url_from_named_url('admin_index'));
	}

	function index(){
		$admin_path = get_templates_directory('admin').'/admin_index.php';
		$models =$this->all_models;
		if($models!=array()){
			return array('admin_path'=>$admin_path,'models'=>$models);
		}
	}	

	function siteModelsView($matches){
		try{
			$site_model = $matches['site_model'][0];
			$model = '';
			$dbhelper = new DbHelper;
			$all_data = $dbhelper->get_all_in($site_model);
			$admin_path = get_templates_directory('admin').'/model_view.php';
			foreach ($this->all_models as $val) {
				if($val->get_table_name()==$site_model){
					$model = $val;
				}
			}
			if($model==''){
				redirect404();
			}else{
				return array('admin_path'=>$admin_path,'model'=>$model,'model_data'=>$all_data);
			}
		}catch(Exception $e){
			redirect404();
		}
		
	}


	function siteModelsAdd($matches){
		$form_class = new BaseForms;
		$post_data = $_POST;
		try{
			$site_model = $matches['site_model'][0];
			$model = '';
			$dbhelper = new DbHelper;
			$all_data = $dbhelper->get_all_in($site_model);
			$admin_path = get_templates_directory('admin').'/model_add.php';
			foreach ($this->all_models as $val) {
				if($val->get_table_name()==$site_model){
					$model = $val;
				}
			}
			if($model==''){
				redirect404();// Function from settings.php
			}else{
				$form_class->set_model($model);
				$form = $form_class->get_form();
				if($post_data!=[]){
					echo 'bound';
				}
				return array('admin_path'=>$admin_path,'model'=>$model,'form'=>$form);
			}

		}catch(Exception $e){
			redirect404();// Function from settings.php
		}

	}




	




	function siteModelsEdit($matches){
		$form_class = new BaseForms;
		print_r($_POST);
		


		try{
			$site_model = $matches['site_model'][0];
			$site_model_id = $matches['site_model_id'][0];
			$model = '';
			$dbhelper = new DbHelper;
			$admin_path = get_templates_directory('admin').'/model_edit.php';
			foreach ($this->all_models as $val) {
				if($val->get_table_name()==$site_model){
					$model = $val;
					$model_data = $model->get_object_data_from_id($site_model_id);
				}
			}
			if($model!='' && $model_data != array()){
				$form_class->set_model($model);
				$form = $form_class->get_form($model_data);
				return array('admin_path'=>$admin_path,'model'=>$model,'model_data'=>$model_data,'form'=>$form);
			}else{
				throw new Exception('');				
			}

		}catch(Exception $e){
			redirect404();// Function from settings.php
		}
	}
	


	function setupDb($matches){
		$db_helper = new DbHelper;
		setup_db();// Function from settings.php
	}



	// function get_static_files($matches){


	// }



}

?>