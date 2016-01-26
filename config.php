<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);

$server = "localhost";
$db_user = "shopiapp_upsell";
$db_pwd = "upsell121*1";
$db_name = "shopiapp_shareviewer";

$db_obj = mysql_connect($server,$db_user, $db_pwd);
mysql_select_db($db_name);
mysql_set_charset('utf-8');

$sql_details = array(
    'user' => $db_user,
    'pass' => $db_pwd,
    'db' => $db_name,
    'host' => $server
);

define('DOMAIN_NAME', 'shopiapps.io/shareviewer');
define('SITE_URL', 'https://shopiapps.io/shareviewer');

session_start();
  
if (!$db_obj) {
	echo "Failed to connect to MySQL: " . mysql_error();
}

define('SHOPIFY_API_KEY', '8d0be82a9d497fb4290d3997f8440a49');
define('SHOPIFY_SECRET', 'fcffdbc5a5d9dbbe2bb3208c6af8bd8e');
define('SHOPIFY_SCOPE', 'write_products,write_content,write_themes');
define('PLAN', 'free'); /* free/paid */
define('PLAN_PRICE', 27.99); /* 2.99 */
define('PLAN_TRIAL', 7); /* 7 */
define('PLAN_MODE', true); /* true/false */

define('APP_NAME',"Share Viewer");
function loopAndFind($array, $index, $search){
	$returnArray = array();
	foreach($array as $k=>$v){
		if($v[$index] == $search){   
			$returnArray[] = $v;
		} 
	}
	return $returnArray;
}
?>