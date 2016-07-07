<?php error_reporting( E_ALL ); 
ini_set('display_errors', 1);?>
<?php
session_start();

if(!isset($_SESSION['user_id'])){
	$_SESSION['user']="Guest";
}


$user = $_SESSION['user'];

require 'all_apps.php';
require 'base_urls.php';
require 'base_views.php';
require 'base_forms.php';
require 'urls.php';
require 'db_helper.php';
require 'settings.php';
require 'base_models.php';
if(!isset($_SESSION['session_token'])){
	$_SESSION['session_token']=genetrate_secret_token(20);
}




$url = $_GET['url'];

if(filterUrl($url)){
	
	$url_controller_and_matches = get_controller_and_matches_for_url($url);
	if ($url_controller_and_matches) {
		$controller_name = $url_controller_and_matches["controller"];
		$matches = $url_controller_and_matches["matches"];
		require_once 'controller.php';
		new Controller($controller_name, $matches);
	}
}
?>