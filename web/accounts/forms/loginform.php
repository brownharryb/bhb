<?php 
/**
* 
*/
class LoginForm extends \BaseForms
{
	protected $form_fields = array(
			'username'=>['form_type'=>'text','display_name'=>'Username','validation'=>'username_validation'],
			'password'=>['form_type'=>'password','display_name'=>'Password','validation'=>'password_validation'],
		);
	function __construct()
	{
		parent::__construct();
	}

	function username_validation($username){

		return $username;

	}

	function password_validation($password){

		return $password;
		
	}
}

 ?>