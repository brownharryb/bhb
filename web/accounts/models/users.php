<?php

class UserModel extends BaseModels{
	protected $admin_register=true;
	protected $table_name = 'site_users3';
	protected $columns_exec = array(
		'username'=>
		array('sql'=>'boolean','max_length'=>25,'form_type'=>'text','verbose'=>'Username','validation'=>'username_validation'),


		'password'=>
		array('sql'=>'varchar(255)','max_length'=>25,'form_type'=>'password','verbose'=>'Password','validation'=>'password_validation'),

		'firstname'=>
		array('sql'=>'varchar(255)','max_length'=>2,'form_type'=>'text','validation'=>'firstname_validation'),

		'lastname'=>
		array('sql'=>'varchar(255)','max_length'=>25,'form_type'=>'text','validation'=>'lastname_validation'),

		// 'email'=>
		// array('sql'=>'varchar(255)','max_length'=>255,'form_type'=>'email','validation'=>'email_validation'),

		'created'=>
		array('sql'=>'datetime','max_length'=>25,'form_type'=>'date','verbose'=>'Date Created','validation'=>'date_created_validation')
		);


	function __construct(){
		parent::__construct();
	}


	function get_column_exec(){
		return $this->columns_exec;
	}

	function username_validation($username){
		return $username;
	}
	function password_validation($username){
		return $username;
	}
	function firstname_validation($username){
		return $username;
	}
	function lastname_validation($username){
		return $username;
	}
	function email_validation($username){
		return $username;
	}
	function date_created_validation($username){
		return $username;
	}	
	
}


?>