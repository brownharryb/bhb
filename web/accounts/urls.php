<?php

namespace accounts;

class SubUrl extends \BaseUrls{

	protected $first_param,$app_name;
	
	function __construct($first_param)
	{
		parent::__construct();
		$this->first_param = $first_param;
		$this->app_name = "accounts";
	}

	function get_sub_urls(){
		$return_val = array(
		array("path"=>"^".$this->first_param."login$","controller"=>"accounts_AccountsLoginView_login","named_url"=>"login_page")
		);
		return $return_val;
	}
}



?>