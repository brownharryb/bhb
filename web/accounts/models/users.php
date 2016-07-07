<?php

class UserModel extends BaseModels{
	protected $admin_register=true;
	protected $table_name = 'site_users3';
	protected $columns_exec = array(
		'username'=>
		array('sql'=>'boolean','max_length'=>25,'form_type'=>'text','verbose'=>'Username'),


		'password'=>
		array('sql'=>'varchar(255)','max_length'=>25,'form_type'=>'password','verbose'=>'Password'),

		'firstname'=>
		array('sql'=>'varchar(255)','max_length'=>2,'form_type'=>'text'),

		'lastname'=>
		array('sql'=>'varchar(255)','max_length'=>25,'form_type'=>'text'),

		'email'=>
		array('sql'=>'varchar(255)','max_length'=>255,'form_type'=>'email'),

		'created'=>
		array('sql'=>'datetime','max_length'=>25,'form_type'=>'date','verbose'=>'Date Created')
		);


	function __construct(){
		parent::__construct();
	}	
	
}


?>