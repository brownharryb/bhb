<?php


class AdminModel{

	protected $table_name = "admin";
	private $conn = '';

	function __construct(){

	}

	function get_conn(){
		$c = new DbHelper;
		$this->conn = $c->get_connection();
	}


	function get_all_models(){
		$dbhelper = new DbHelper;
		return $dbhelper->get_all_tables();		
	}

	function get_model_data($model_name){

	}
}





?>