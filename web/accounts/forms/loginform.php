<?php 
/**
* 
*/
class LoginForm extends \BaseForms
{
	protected $form_fields = array(
			'username'=>['form_type'=>'text','display_name'=>'Username','validation'=>['field_validation']],
			'password'=>['form_type'=>'password','display_name'=>'Password','validation'=>['field_validation']],
		);
	function __construct()
	{
		parent::__construct();
	}

	function field_validation($username){

		return $username;

	}
	// function form_validation(){

	// }
}

 ?>