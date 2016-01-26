<?php
header('Access-Control-Allow-Origin: *');
include("config.php");

$current_date = date("Y-m-d H:i:s");

$shop = "";
if(isset($_REQUEST['shop']) && $_REQUEST['shop'] != "") {
    $shop = $_REQUEST["shop"];
    $shop = trim($shop);
}

$type = "";
if(isset($_REQUEST['type']) && $_REQUEST['type'] != "") {
    $type = $_REQUEST["type"];
    $type = trim($type);
}

if(($type == 'shared' || $type == 'bounce') && $shop != "") {
    $bid = $_REQUEST["bid"];
	$pid = $_REQUEST["pid"];
	
    
	if ($_REQUEST["tracking_id"] == "") {
		$sql_insert = "Insert into button_stats(`shop`,bid,pid,shared,bounce,created_date,updated_date) ".
				"VALUES('".mysql_real_escape_string($shop)."','".$bid."','".$pid."','".($type== "shared" ? 1: 0)."','".($type== "bounce" ? 1: 0)."','".$current_date."','".$current_date."')";
		$result_sql = mysql_query($sql_insert);
		if (!$result_sql) {
			echo 'Invalid query: '.mysql_error();
		} else {
			$sql_id_q = " select bsid from button_stats where `shop` = '".mysql_real_escape_string($shop)."' and bid= '".$bid."' and pid = '".$pid."' and created_date = '".$current_date."'";
			$sql_id_rs = mysql_query($sql_id_q);
			$sql_id_data = mysql_fetch_array($sql_id_rs);
			echo json_encode(array("key" => base64_encode($sql_id_data["bsid"])));
			exit;
		}
	} else {
		$tracking_id = base64_decode($_REQUEST["tracking_id"]);
		$sql_insert = "update button_stats set shared = ".($type== "shared" ? 1: 0).",bounce = 0,updated_date = '".$current_date."' where bsid = $tracking_id;";
		$result_sql = mysql_query($sql_insert);
		if (!$result_sql) {
			echo 'Invalid query: '.mysql_error();
		} else {
			echo json_encode(array("success" => "true"));
			exit;
		}
	}
} else {
    echo "error";
}
?>