<?php
/**
* 
*/
class View extends \BaseViews{

	private $page_body = "I am in login";
	private $page_title = "This is title";
	private $page_footer = "Page footer";
	protected $app_name = "accounts";
	
	
	function __construct($function_to_call='',$matches=''){
		parent::__construct();

		$globs = $this->$function_to_call($matches);

		if(isset($globs)){			
			$page_title=$this->page_title;
			require_once get_base_html();
		}
		
	}


	function login($matches){
		echo "login_page";
	}
}



?>