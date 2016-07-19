<?php 
/**
* 
*/
class AccountsLoginView extends \BaseViews
{
	protected $app_name = "accounts";
	private $login_template = "login_html.php";
	private $form_file_name = "loginform.php";
	
	function __construct($function_to_call='',$matches='')
	{
		parent::__construct($function_to_call);
	}


	function login(){
		$post_data = $_POST;
		require_once get_forms_directory("accounts").$this->form_file_name;
		$form_class = new LoginForm();
		$form = $form_class->get_form();
		if($post_data!=array()){
			$form = $form_class->form_is_valid($post_data);
		}
		$this->render_view($this->login_template,array('page_title'=>'login',"form"=>$form));
	}
}


 ?>