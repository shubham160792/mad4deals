<?php
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('ERROR_REPORTING', 1);
$dirPath = isset($_SERVER['environment']) ? '../' : '/';
define('dirPath', $dirPath);
require_once  ROOT.$dirPath.'classMap.php';

class Config{
	const urlPath = 'http://gallery.front.com/';
	const CssJSUrl = 'http://gallery.front.com/';
	const ImageViewURL = 'http://localhost/gal_uploads/';
	const imagesPath = 'http://gallery.front.com/images/';
	static $connection = array(
		'redis' => array(
			'ip' => '127.0.0.1',
			'port' => 6379
			),
		'_mysql_mobiles' => array(
			'username' => 'root',
			'password' => 'root',
			'host' => 'localhost',
			'port' => '3306',
			'db' => 'mobiles_mobiles'),
		'_mysql_111' => array(
			'username' => 'appuser',
			'password' => 'mysql18032014!',
			'host' => '180.179.213.24',
			'port' => '3306',
			'db' => 'mobiles_mobiles'));
	}


	function setErrorReporting(){
		if(ERROR_REPORTING  == 1){
			ini_set("display_errors", "ON");
			error_reporting(E_ALL);
		}
		else{
			ini_set("display_errors", "OFF");
			error_reporting(0);
		}
	}
	function __autoload($_className)
	{
	// global $_CLASSMAP;

		$_nameSpace = substr($_className, 0, strrpos($_className, '\\'));
		$_nameSpace = str_replace('\\', DIRECTORY_SEPARATOR, $_className);	

	// if(isset($_CLASSMAP[$_nameSpace])){
	// 	$_classPath = $_CLASSMAP[$_nameSpace];
	// 	if(!empty($_classPath)){
	// 		if(is_readable($_classPath)) {
	// 			require_once $_classPath;
	// 		}
	// 	}
	// }
	// else{
		$dirPath = isset($_SERVER['environment']) ? '../../' : '../';
		$_classPath = ROOT .$dirPath. str_replace('\\', '/', $_nameSpace) . '.php';
		if(is_readable($_classPath)) {
			require_once $_classPath;
		}
	// }
	}
	setErrorReporting();

	?>
