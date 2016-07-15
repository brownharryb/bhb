<?php 




define('SITE_NAME', 'DevBBH.com');









function filterUrl($urlarg){
		$allowed_chars = "_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/";
		$urlarg = str_replace(" ", "",$urlarg);
		$urlarg = strtolower($urlarg);
		if (!empty($urlarg)){		
			foreach(str_split($urlarg) as $eachchar){
			if (strpos($allowed_chars, $eachchar)===false){
				redirect404();
				return false;
			}
		}
	}
		return true;
	}

function get_home_url(){

	return "http://localhost/2/";

	// TODO DELETE ABOVE AND CHANGE TO BELOW FOR PRODUCTION
  // return sprintf(
  //   "%s://%s",
  //   isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
  //   $_SERVER['SERVER_NAME']
  // );
}

function get_static_url(){
	return get_home_url().'web/static/';
}


function redirect404(){
	return header('Location: '.get_home_url().'page404.php');
}

function get_base_html(){
	return getcwd().'/base_html.php';
}

function get_template_file($filename){
	foreach (ALL_APPS as $val) {
		$file = get_templates_directory($val).$filename;
		if(file_exists($file)){
			return $file;
		}
	}
}

function get_templates_directory($app_name){
	return getcwd().'/'.$app_name.'/templates/';
}

function get_models_directory($app_name){
	return getcwd().'/'.$app_name.'/models/';
}

function get_full_url_from_named_url($named_url, $matches=''){
	$home_url = get_home_url();
	$relative_url = get_url_from_named_url($named_url,$matches);
	return $home_url.$relative_url;
}

function get_url_from_named_url($named_url, $matches=''){
	$a = new AllUrl;
	$allowed_urls = $a->get_all_urls();
	$pattern='';
	$regex_pattern = '';
	$return_val = '';
	foreach ($allowed_urls as $val) {
		if($val['named_url']==$named_url){
			$pattern = $val['path'];
			break;
		}
	}
	$regex_pattern = '#'.$pattern.'#';
	$pattern = str_replace('#','', $pattern);
	$pattern = str_replace('^','', $pattern);
	$pattern = str_replace('$','', $pattern);

	if($matches==''){
		$return_val = $pattern;
	}
	else{
	

		$str_arr = explode('/', $pattern);

		foreach ($str_arr as $k=>$v) {
			foreach ($matches as $k2 => $v2) {

				if(strpos($v, $k2)&&strpos($v, '?P')){
					$str_arr[$k]=$v2;
				}
			}
		}
		$return_val = implode('/',$str_arr);

	}

	if(preg_match($regex_pattern, $return_val)){
		return $return_val;
	}else{
		throw new Exception("Pattern does not match url in settings.php", 1);
		
	}


	

}

function confirm_regex_match($regex,$sub_url){

	$regex = '#'.$regex.'#';

	// echo 'suburl = '.$sub_url;

	if(preg_match($regex,$sub_url,$matches,PREG_OFFSET_CAPTURE)){
		return $matches;
	}
	return false;
	
}


function get_controller_and_matches_for_url($url){
	$a = new AllUrl;
	$allowed_urls = $a->get_all_urls();
	foreach ($allowed_urls as $val) {
		$matches = confirm_regex_match($val['path'],$url);
		if($matches){
			return array("controller"=>$val['controller'],"matches"=>$matches);
		}
	}
	redirect404();
	return false;
}

function redirect_to_absolute_url($relative_url){
	$full_url = get_home_url().$relative_url;
	header("Location:".$full_url);
}


function genetrate_secret_token($num){
	$allowed_chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$chars_len = strlen($allowed_chars)-2;
	$count = 0;
	$return_val = '';
	while ( $count <= $num) {
		$char = rand(0,$chars_len);
		$return_val.=$allowed_chars[$char];
		$count+=1;
	}
	return $return_val;
}


function get_all_model_classes($flag=''){
	$return_val=array();
	$class_objs = array();
	foreach (ALL_APPS as $val) {
		$all_files = glob($val.'/models/*.php');
		foreach ($all_files as $val) {
			require_once $val;
			foreach (get_declared_classes() as $class) {
				if(is_subclass_of($class, 'BaseModels')){
					if(!in_array($class, $return_val)){
						$return_val[] = $class;
						$class_objs [] = new $class;
					}
				}
			}
		}
	}
	if($flag==''){
		return $return_val;
	}
	if($flag=='objects'){
		return $class_objs;
	}
}

function get_all_model_table_names(){
	$return_val = array();
	foreach (get_all_model_classes() as $val) {
		require_once get_class_file($val);
		$c = new $val;
		if(method_exists($c,'get_table_name'))
		$return_val[] = $c->get_table_name();
	}
	return $return_val;
}

function get_class_file($class){
	$reflector_class = new ReflectionClass($class);
	$f_name = $reflector_class->getFileName();
	return $f_name;
}
function setup_db(){
	$all_model_classes = get_all_model_classes();
	foreach ($all_model_classes as $val) {
		create_table($val);
	}

}


function create_table($class){
	$c = new $class;
	$c->create_table();

}

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function get_db_table_info_from($table,$required_keys=''){
	$dbhelper = new DbHelper;
	$temp = array();
	$column_names = $dbhelper->get_all_column_names_from($table);
	$required_keys = ($required_keys=='') ? array('column_name','column_type','character_maximum_length'):$required_keys;

	foreach ($column_names as $val) {
			$t = array();			
			foreach ($required_keys as $req_key) {
				if(array_key_exists(strtoupper($req_key), $val)){
				$t[$req_key] = $val[strtoupper($req_key)];
			}
		}

		$temp[] = $t;
		}
	return $temp;
}

// function get_all_css_links_for($app_name){
// 	$static_css_patterns = getcwd().'/'.$app_name.'/static/css/*.css';
// 	echo "<style>";
// 	foreach (glob($static_css_patterns) as $val) {
// 		include_once $val;
// 	}
// 	echo "</style>";
// }



// *********************************ADMIN VIEW********************************

// **************************************************************************

 ?>