<?php



class BaseUrls
{
	protected $first_param ='';
	protected $app_name = '';
	
	function __construct()
	{
		
	}

	function get_sub_urls(){
		return '';
	}

	function get_urls(){
		$return_val = array();
		if($this->get_sub_urls()!= ''){
			$return_val = array_merge($this->get_sub_urls(),$return_val);
		}
		return $return_val;

	}
}



?>