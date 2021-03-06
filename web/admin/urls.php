<?php

/**
* 
*/
namespace admin;

class SubUrl extends \BaseUrls{

	protected $first_param,$app_name;
	
	function __construct($first_param)
	{
		parent::__construct();
		$this->first_param = $first_param;
		$this->app_name = "admin";
	}

	function get_sub_urls(){
		$return_val = array(
		array("path"=>"^".$this->first_param."setupdb$","controller"=>"admin_View_setupDb","named_url"=>"admin_setup_db"),
		array("path"=>"^".$this->first_param."$","controller"=>"admin_View_index","named_url"=>"admin_index"),
		array("path"=>"^".$this->first_param."logout$","controller"=>"admin_View_logout","named_url"=>"admin_logout"),
		array("path"=>"^".$this->first_param."(?P<site_model>[a-zA-Z0-9_]+)$","controller"=>"admin_View_siteModelsView","named_url"=>"admin_site_model_view"),
		array("path"=>"^".$this->first_param."(?P<site_model>[a-zA-Z0-9_]+)/add$","controller"=>"admin_View_siteModelsAdd","named_url"=>"admin_site_models_add"),
		array("path"=>"^".$this->first_param."(?P<site_model>[a-zA-Z0-9_]+)/(?P<site_model_id>[0-9]+)/$","controller"=>"admin_View_siteModelsEdit","named_url"=>"admin_site_model_edit")
		);
		return $return_val;
	}
}



?>