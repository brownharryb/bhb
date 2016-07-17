<?php


/**
* 
*/
class BaseViews{
	protected $app_name = "";
	
	function __construct($function_to_call='')
	{
		if(method_exists($this, $function_to_call)){
			$this->$function_to_call();
		}
		$this->base_html = get_base_html();

	}

	function get_template($template_name){
		if($this->app_name != ""){
			return get_templates_directory($this->app_name).'/'.$template_name;
		}
	}


	function render_view($template,$args=array(),$base_html=""){
		if($base_html==""){
			$base_html = get_base_html();
		}
		if($args != array()){
			foreach ($args as $key => $value) {
				$globs[$key] = $value;
			}
		}
		$globs['page_body'] = $this->get_template($template);
		require_once $base_html;

	}



	
}



?>