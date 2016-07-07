<?php 

// controllers should be in the format app_Class_function
class AllUrl{
	private $allurls = '';
	private $all_apps = ALL_APPS;
	function __construct(){

	}

	function get_first_params(){
		$first_params_for_apps = array(
				"admin/"=>"admin",
				"accounts/"=>"accounts"
			);

		return $first_params_for_apps;
	}





	function get_all_urls(){
		$return_val = array();
		foreach ($this->all_apps as $val) {
			foreach ($this->get_first_params() as $param_key => $param_val) {
				if($val== $param_val){

					$f = $val.'/urls.php';
					if(file_exists($f)){
						$s=null;
						$temp_array=array();
						require_once $f;
						$r = $val.'\SubUrl';
						$s = new $r($param_key);
						$temp_array = $s->get_urls();
						foreach ($temp_array as $temp_val) {
							$return_val[] = $temp_val;
						}
						

			}

				}
			}			
			
		}
		return $return_val;
	}
}


 ?>