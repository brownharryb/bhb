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
		require_once get_forms_directory("accounts").$this->form_file_name;
		$form_class = new LoginForm();
		$form = $form_class->get_fields_form();
		$this->render_view($this->login_template,array('page_title'=>'login',"form"=>$form));
	}
}


 ?>