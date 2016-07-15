<?php

/**
* 
*/
class Controller{
	
	private $controller_name, $matches, $app_name, $class_name, $function_name,$page_name;

	function __construct($controller_name,$matches){
			$this->controller_name = $controller_name;
			$this->matches = $matches;
			$this->separate_app_class_function_names($controller_name);
			$this->link_url_to_function();
	}

	function separate_app_class_function_names($controller_name){
			$separated_names = explode("_",$controller_name);
			$this->app_name = $separated_names[0];
			$this->class_name = $separated_names[1];
			$this->function_name = $separated_names[2];
			$this->page_name = strtolower($this->class_name).'.php';
	}

	function link_url_to_function(){
			require_once $this->app_name.'/views/'.$this->page_name;

			$f_name = $this->function_name;
			$c = new $this->class_name($function_to_call=$f_name,$matches=$this->matches);
			
			if(isset($c->admin_css)){
				$admin_css = $c->admin_css;
			}
			if(isset($c->admin_js)){
				$admin_js = $c->admin_js;
			}	
			
	}



}



?>