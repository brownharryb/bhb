
<?php
require_once '../dbconfig.php';



class DbHelper{
	private $conn='';

	function __construct(){
		$db = new DbConfig;
		$this->conn = $db->get_connection();
		
	}

	function get_all_in($table_name){
		$db = new DbConfig;
		$this->conn = $db->get_connection();
		$sql = "SELECT * FROM ".$table_name;
		$results = $this->conn->query($sql);
		$this->conn = null;
		return $results->fetchAll();
	}

	function get_all_column_names_from($table_name){
		$db = new DbConfig;
		$this->conn = $db->get_connection();
		$sql = "SELECT * from INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$table_name."' ORDER BY ordinal_position;";
		$results = $this->conn->query($sql);
		$this->conn = null;
		return $results->fetchAll();
	}

	function get_all_tables(){
		$db = new DbConfig;
		$this->conn = $db->get_connection();
		$sql = "SHOW TABLES";
		$results = $this->conn->query($sql);
		$this->conn = null;
		return $results->fetchAll();		
	}

	function get_connection(){
		$db = new DbConfig;
		$this->conn = $db->get_connection();
		return $this->conn;
	}

	function get_all_in_table_using_id($table,$id){

		try{
			$db = new DbConfig;

			$this->conn = $db->get_connection();
			$sql = "SELECT * FROM ".$table." WHERE id = :id;";
			$r = $this->conn->prepare($sql);
			$r->bindParam(':id',$id);

			$r->execute();
			$results = $r->fetchAll();
			$r = null;
			$this->conn = null;
			return $results;
		}catch(Exception $e){
			return false;
		}
		catch(Error $e){
			return false;
		}
	}
}

?>