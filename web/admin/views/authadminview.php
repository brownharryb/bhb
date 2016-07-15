<?php
/**
* 
*/
require_once 'admin_view.php';
class AuthAdminView extends \AdminView
{
	private $matches;
	
	function __construct($function_to_call='',$matches='')
	{
		$this->matches;
		$this->$function_to_call();
	}

	function logout(){
		session_destroy();
		redirect_to_absolute_url(get_url_from_named_url('admin_index'));
	}



	function login(){
		if(isset($_SESSION['admin_login']) && $_SESSION['admin_login']==true){
			redirect_to_absolute_url(get_url_from_named_url('admin_index'));
		}
		$csrf = $_SESSION['session_token'];
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['csrf_token'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			$token = $_POST['csrf_token'];
			if($this->validate_login($username,$password) && $csrf==$token){
				$_SESSION['admin_login']=true;
				redirect_to_absolute_url(get_url_from_named_url('admin_index'));
				// redirect_to_absolute_url($this->matches);
			}
			else{
				$admin_error = 'Invalid login details';
				$globs =  array('admin_path'=>get_templates_directory('admin').'/admin_login.php','csrf_token'=>$csrf,'admin_error'=>$admin_error);
				
			}
		}
		else{
			$globs =  array('admin_path'=>get_templates_directory('admin').'/admin_login.php','csrf_token'=>$csrf);
			
		}
		$this->show_admin_view($globs);
	}

	function validate_login($username,$password){
		return true;
	}

}







?>