<?php
/**
 * Dumb-Namic
 * 
 * Static-dynamic(ish) web framework
 * 
 * @version b0.1
 * @author The Juan Who Code <caserjan@gmail.com>
 * @package core
 */


define('NICE_URL',true);

define('DOMAIN','http://www.dumbnamic.dev'); 

define('POST_EXTENSION','html');

// Absolute path
define('ABSPATH',dirname(__FILE__).'/');

// Application path
define('APPDIR',ABSPATH.'application/');

// Clear all global variable
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	unset($_POST);
}else{
	unset($_GET);
}

// Load application functions
if(file_exists(APPDIR.'functions.php')) include(APPDIR.'functions.php');

	
$page = array();

if(defined('NICE_URL') && NICE_URL == true && file_exists('.htaccess')){
	
	$uri = array();	
	// Get URI     
	$raw_uri = rtrim(ltrim($_SERVER['REQUEST_URI'],'/'),'/');
	$raw_uri = parse_url($raw_uri);
	$pi = pathinfo($raw_uri['path']);
	
	$pi['dirname'] = isset($pi['dirname']) ? ($pi['dirname'] == '.' ? '' : $pi['dirname']) : '';
	
	$page['type'] = (isset($pi['extension']) && $pi['extension'] == POST_EXTENSION ? 'post' : 'page');
	
	// URL Query
	if(isset($raw_uri['query'])){
		
		$query = array();	
		
		parse_str($raw_uri['query'],$query);
	
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$_POST = $query;
			
		}elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
			
			$_GET = $query;
			
		}
	}
	
	if(empty($pi['dirname'])){ // Main page
		if(empty($pi['basename'])){
			$page['script'][] = APPDIR.'pages/main.php';
			$page['name'] = 'root';
		}else{
			if($page['type'] == 'post'){				
				
				$page['script'][] = APPDIR.'pages/'.$pi['filename'].'.php';
				
			}else{
				
				$page['script'][] = APPDIR.'pages/'.$pi['basename'].'/dynamic.php';
				$page['script'][] = APPDIR.'pages/'.$pi['basename'].'/main.php';				
				
			}
			$page['name'] = $pi['basename'];
		}
	}else{
		
		if($page['type'] == 'post'){
			
			$page['script'][] = APPDIR.'pages/'. $pi['dirname'].'/'.$pi['filename'].'.php';
			
		}else{
			
			if(!empty($pi['extension'])){
				$page['script'][] = APPDIR.'pages/'. $pi['dirname'].'/'.$pi['basename'].'/main.php';
			}else{
				$page['script'][] = APPDIR.'pages/'. $pi['dirname'].'/'.$page['type'].'-'.$pi['filename'].'.php';
				$page['script'][] = APPDIR.'pages/'. $pi['dirname'].'/'.$pi['filename'].'/main.php';					
			}
		}			
		
		$page['name'] = ($page['type'] == 'post' ? $pi['filename'] : $pi['basename']);
	}
}


$inc = '';
$page = (object)$page;

foreach($page->script as $script){
	if(file_exists($script) && $inc == ''){
		$inc = $script;
		break;
	}
}

if(file_exists($inc)){
	include($inc);
}else{
	if(file_exists(APPDIR.'404.php')){
		include(APPDIR.'404.php');

	}else{
		// internal 404 page
	}
}
?>