
<?php

/**
* 
*/
class BaseModels
{
	protected $admin_register=false;
	protected $conn='';
	protected $table_name='';
	protected $columns_exec='';
	protected $model_id=0;

	function __construct()
	{
		
	}
	function set_model_id($model_id){
		$this->model_id = $model_id;
	}
	function get_object_data_from_id($model_id=''){
		$return_val = array();
		if ($model_id=='') {
			$model_id = $this->model_id;
		}
		if($model_id > 0){
			$dbhelper = new DbHelper;
			$return_val = $dbhelper->get_all_in_table_using_id($this->get_table_name(),$model_id);

		}
		return $return_val;
	}

	function get_conn(){
		$dbhelper = new DbHelper;
		$this->conn = $dbhelper->get_connection();
	}

	function show_in_admin(){
		return $this->admin_register;
	}

	function get_table_name(){
		return $this->table_name;
	}
	function get_view_link(){
		return get_full_url_from_named_url("admin_site_model_view", array('site_model'=>$this->table_name));
	}
	function get_add_link(){
		return get_full_url_from_named_url("admin_site_models_add", array('site_model'=>$this->table_name));
	}

	function get_all_columns(){
		$columns = '';
		if($this->columns_exec!=''){
			$columns = array_keys($this->columns_exec);
		}
		return $columns;
	}

	function get_columns_exec(){
		return $this->columns_exec;
	}

	function get_display_name(){
		return $this->table_name;
	}


	function create_table(){
		$this->get_conn();
		$sql ="";
		if($this->conn!='' && $this->table_name!='' && $this->columns_exec!=''){
			$sql = "CREATE TABLE IF NOT EXISTS ".$this->get_table_name()." (id int PRIMARY KEY AUTO_INCREMENT";
			foreach ($this->columns_exec as $key => $value) {
				$sql.=",".$key." ".$value['sql'];
			}
			$sql.=");";
		}
		if($sql!=""){
			$this->conn->exec($sql);
		}
		$this->conn = null;
	}




	// function get_all(){
	// 	$this->get_conn();
	// 	$return_val = array();
	// 	if($table_name!=""){
	// 		$sql = "SELECT * FROM ".$table_name;
	// 		$r = $this->conn->query($sql);
	// 		$return_val = $r->fetchAll()
	// 		return $return_val;
	// 	}

	// 	$this->conn = null;
		
	// }
}

?>