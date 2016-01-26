<?php
header('Access-Control-Allow-Origin: *');
include("../config.php");
include("../shopify_api.php");

$current_date = date("Y-m-d H:i:s");

$type = "";
if(isset($_REQUEST['type']) && $_REQUEST['type'] != "") {
    $type = $_REQUEST["type"];
    $type = trim($type);
}

if($type == 'save_app_id') {
	$facebook_app_id = $_REQUEST['facebook_app_id'];
    $sql_update = "Update app set facebook_app_id = '$facebook_app_id' where shop='".mysql_real_escape_string($shop)."'";
    $result_sql = mysql_query($sql_update);
    if (!$result_sql) {
        echo 'Invalid query: '.mysql_error();
    } else {
        echo "success";
	}
exit;
}



if($type == 'b_add') {
$app_id = "";
$sql_fb = "select facebook_app_id from app where shop='".mysql_real_escape_string($shop)."' and ifnull(facebook_app_id, '') !=''";
$result_sql = mysql_query($sql_fb);
if (mysql_num_rows($result_sql) > 0) {
	$sql_fb_r = mysql_fetch_array($result_sql);
	$app_id = $sql_fb_r["facebook_app_id"];
} else {
	echo "You must Enter your facebook APP ID.";
	exit;
}
	

    $bTitle= $_REQUEST['bTitle'];
	$bDesciption= $_REQUEST['bDesciption'];
    $b_target_pro =$_REQUEST['b_target_pro'];
    $b_target_pro_handle = $_REQUEST['b_target_pro_h'];
	$bHeading = $_REQUEST['bHeading'];
	$bSubHeading = $_REQUEST['bSubHeading'];
	$bURL = $_REQUEST['bURL'];
	$page_id = $_REQUEST['page_id'];
	$bselection_target = $_REQUEST['bselection_target'];

	if(strtolower($bselection_target == "product")) {
		$page_id = "";
	}else {
		$b_target_pro_handle = "";
		$b_target_pro= "";
	}
	
	if($b_target_pro != "") { 
		$api_call_str = "/admin/products/".$b_target_pro.".json";
		$b_content = "https://$shop/products";
	} else {
		$api_call_str = "/admin/pages/".$page_id.".json";
		$b_content = "https://$shop/pages";
	}

	
	try{
		$product_meta_list = $sc->call('GET', $api_call_str);
		$b_content = "<a target='_blank' href='" . $b_content ."/" . $product_meta_list["handle"] . "'>".$product_meta_list["title"]."</a>";
	} Catch (Exception $e){
	}
	
	

    $sql_insert = "Insert into share_buttons(`shop`,b_content,b_share_image_url,b_share_description,b_heading,b_subheading,b_title,page_id,created_date,updated_time,b_target_id, b_target_handle) ".
	"VALUES('".mysql_real_escape_string($shop)."','".mysql_real_escape_string($b_content)."','".mysql_real_escape_string($bURL)."','".mysql_real_escape_string($b_description)."','".mysql_real_escape_string($bHeading)."','".mysql_real_escape_string($bSubHeading)."','".mysql_real_escape_string($bTitle)."','".mysql_real_escape_string($page_id)."','".$current_date."','".$current_date."','".$b_target_pro."', '".mysql_real_escape_string($b_target_pro_handle)."')";
    #echo $sql_insert;
    $result_sql = mysql_query($sql_insert);
    if (!$result_sql) {
        echo 'Invalid query: '.mysql_error();
    } else {
        $banner_id = mysql_insert_id();
        $product_id = $b_target_pro;
        $page_id = $page_id;

        $api_call_str = "";
        $cu_type_value_str = $app_id."###".$bTitle."###".$banner_id."###".$bHeading."###".$bSubHeading;
        $MetaData = array("metafield" => array("namespace" => "b_share_viewer", "key" => "b_share_viewer_value", "value" => $cu_type_value_str, "value_type" => "string"));        
		if($product_id !== "" && strtolower($bselection_target) == "product") { 
            $api_call_str = "/admin/products/".$product_id."/metafields.json";
        } else {
            $api_call_str = "/admin/pages/".$page_id."/metafields.json";
        }

        try{
            $add_meta = $sc->call('POST', $api_call_str, $MetaData);
        } catch (exception $e) {

        }

		$api_call_str = "";
        $cu_type_value_str = $bDesciption."#######".$bURL;
        $MetaData = array("metafield" => array("namespace" => "b_share_viewer", "key" => "b_description_u", "value" => $cu_type_value_str, "value_type" => "string"));        
        try{
            $add_meta = $sc->call('POST', $api_call_str, $MetaData);
        } catch (exception $e) {

        }        
        echo "success";
    }
}

if($type == 'b_update') {
$app_id = "";
$sql_fb = "select facebook_app_id from app where shop='".mysql_real_escape_string($shop)."' and ifnull(facebook_app_id, '') !=''";
$result_sql = mysql_query($sql_fb);
if (mysql_num_rows($result_sql) > 0) {
	$sql_fb_r = mysql_fetch_array($result_sql);
	$app_id = $sql_fb_r["facebook_app_id"];
} else {
	echo "You must Enter your facebook APP ID.";
	exit;
}

	$bTitle= $_REQUEST['bTitle'];
	$bDesciption= $_REQUEST['bDesciption'];
    $b_target_pro =$_REQUEST['b_target_pro'];
    $b_target_pro_handle = $_REQUEST['b_target_pro_h'];
	$bHeading = $_REQUEST['bHeading'];
	$bSubHeading = $_REQUEST['bSubHeading'];
	$bURL = $_REQUEST['bURL'];
	$page_id = $_REQUEST['page_id'];
	$bselection_target = $_REQUEST['bselection_target'];
	if(strtolower($bselection_target == "product")) {
		$page_id = "";
	}else {
		$b_target_pro_handle = "";
		$b_target_pro= "";
	}
	
	if($b_target_pro != "") { 
		$api_call_str = "/admin/products/".$b_target_pro.".json";
		$b_content = "https://$shop/products";
	} else {
		$api_call_str = "/admin/pages/".$page_id.".json";
		$b_content = "https://$shop/pages";
	}

	
	try{
		$product_meta_list = $sc->call('GET', $api_call_str);
		$b_content = "<a target='_blank' href='" . $b_content ."/" . $product_meta_list["handle"] . "'>".$product_meta_list["title"]."</a>";
	} Catch (Exception $e){
	}
	
    $bid = base64_decode($_REQUEST['id']);
    $old_product = trim($_REQUEST['b_pro_list_old']);
	$page_id_old = trim($_REQUEST['b_page_old']);
    $product_id_old = str_replace("p_","",$old_product);
    $sql_update = "Update share_buttons set b_content='".mysql_real_escape_string($b_content)."', page_id = '$page_id',b_subheading ='".$bSubHeading."', b_heading = '".$bHeading."', b_share_image_url = '".$bURL."',b_share_description='".$bDescription."', b_title='".$bTitle."',b_target_id='".$b_target_pro."', b_target_handle='".mysql_real_escape_string($b_target_pro_handle)."',updated_time='".$current_date."' where bid='".$bid."' and shop='".mysql_real_escape_string($shop)."'";
    $result_sql = mysql_query($sql_update);
    if (!$result_sql) {
        echo 'Invalid query: '.mysql_error();
    } else {
        $banner_id = $bid;
        
		/* For Find Metafield */
		$api_call_str = "";
		 
		if($product_id_old != "") { 
			$api_call_str = "/admin/products/".$product_id_old."/metafields.json";
		} else {
			$api_call_str = "/admin/pages/".$page_id_old."/metafields.json";
		}

		
		
		try{
			$product_meta_list = $sc->call('GET', $api_call_str);

			/* Delete meta for upsell list */
			$upsell_meta_id = "";                
			$upsell_meta = loopAndFind($product_meta_list, 'namespace', 'b_share_viewer');
			foreach ($upsell_meta as $k => $v) {
				if ($v['key'] == 'b_share_viewer_value') {
					$upsell_meta_id = $v['id'];
				}
			}
			
			if($upsell_meta_id != ""){
				$d_api_call_str = "";
				if($product_id_old != "") { 
					$d_api_call_str = "/admin/products/".$product_id_old."/metafields/".$upsell_meta_id.".json";
				} else {
					$d_api_call_str = "/admin/pages/".$page_id_old."/metafields/".$upsell_meta_id.".json";
				}
				
				if($d_api_call_str != ""){
					try{
						$delete_meta = $sc->call('DELETE', $d_api_call_str);                        
					} catch (exception $e) {

					}
				}
			}

			/* Delete meta for upsell type */
			$upsell_meta_id_for_type = "";
			$upsell_meta = loopAndFind($product_meta_list, 'namespace', 'b_share_viewer');
			foreach ($upsell_meta as $k => $v) {
				if ($v['key'] == 'b_description_u') {
					$upsell_meta_id_for_type = $v['id'];
				}
			}
			
			if($upsell_meta_id_for_type != ""){
				$d_api_call_str = "";
				if($product_id_old != "") { 
					$d_api_call_str = "/admin/products/".$product_id_old."/metafields/".$upsell_meta_id_for_type.".json";
				} else {
					$d_api_call_str = "/admin/pages/".$page_id_old."/metafields/".$upsell_meta_id_for_type.".json";
				}
				
				if($d_api_call_str != ""){
					try{
						$delete_meta = $sc->call('DELETE', $d_api_call_str);                        
					} catch (exception $e) {

					}
				}
			}
		} catch (exception $e) {

		}
        

		
		$api_call_str = "";
        $cu_type_value_str = $app_id."###".$bTitle."###".$banner_id."###".$bHeading."###".$bSubHeading;
        $MetaData = array("metafield" => array("namespace" => "b_share_viewer", "key" => "b_share_viewer_value", "value" => $cu_type_value_str, "value_type" => "string"));        
		if($b_target_pro != "" && strtolower($bselection_target) == "product") { 
            $api_call_str = "/admin/products/".$b_target_pro."/metafields.json";
        } else {
            $api_call_str = "/admin/pages/".$page_id."/metafields.json";
        }

        try{
            $add_meta = $sc->call('POST', $api_call_str, $MetaData);
        } catch (exception $e) {

        }

		$api_call_str = "";
        $cu_type_value_str = $bDesciption."#######".$bURL;
        $MetaData = array("metafield" => array("namespace" => "b_share_viewer", "key" => "b_description_u", "value" => $cu_type_value_str, "value_type" => "string"));        
        try{
            $add_meta = $sc->call('POST', $api_call_str, $MetaData);
        } catch (exception $e) {

        }
        echo "success";
    }
}

if($type == 'b_delete') {
    $bid = base64_decode($_REQUEST['id']);
    $old_product_s = trim($_REQUEST['b_pro_list']);
    $product_id_old = str_replace("p_","",$old_product_s);
	
	$old_page_s = trim($_REQUEST['b_page']);
    $page_id_old = str_replace("p_","",$old_page_s);


    $sql_sel = "select bid from share_buttons where bid='".$bid."' and shop='".mysql_real_escape_string($shop)."' limit 1";
    #echo $sql_sel;
    $sel_result_sql = mysql_query($sql_sel);
    if(mysql_num_rows($sel_result_sql) > 0){
        
		/* For Find Metafield */
		$api_call_str = "";
		if($product_id_old != "") { 
			$api_call_str = "/admin/products/".$product_id_old."/metafields.json";
		} else {
			$api_call_str = "/admin/pages/".$page_id_old."/metafields.json";
		}

		try{
			$product_meta_list = $sc->call('GET', $api_call_str);

			/* Delete meta for upsell list */
			$upsell_meta_id = "";                
			$upsell_meta = loopAndFind($product_meta_list, 'namespace', 'b_share_viewer');
			foreach ($upsell_meta as $k => $v) {
				if ($v['key'] == 'b_share_viewer_value') {
					$upsell_meta_id = $v['id'];
				}
			}
			
			if($upsell_meta_id != ""){
				$d_api_call_str = "";
				if($product_id_old != "") { 
					$d_api_call_str = "/admin/products/".$product_id_old."/metafields/".$upsell_meta_id.".json";
				} else {
					$d_api_call_str = "/admin/pages/".$page_id_old."/metafields/".$upsell_meta_id.".json";
				}
				
				if($d_api_call_str != ""){
					try{
						$delete_meta = $sc->call('DELETE', $d_api_call_str);                        
					} catch (exception $e) {

					}
				}
			}

			/* Delete meta for upsell type */
			$upsell_meta_id_for_type = "";
			$upsell_meta = loopAndFind($product_meta_list, 'namespace', 'b_share_viewer');
			foreach ($upsell_meta as $k => $v) {
				if ($v['key'] == 'b_description_u') {
					$upsell_meta_id_for_type = $v['id'];
				}
			}
			
			if($upsell_meta_id_for_type != ""){
				$d_api_call_str = "";
				if($product_id_old != "") { 
					$d_api_call_str = "/admin/products/".$product_id_old."/metafields/".$upsell_meta_id_for_type.".json";
				} else {
					$d_api_call_str = "/admin/pages/".$page_id_old."/metafields/".$upsell_meta_id_for_type.".json";
				}
				
				if($d_api_call_str != ""){
					try{
						$delete_meta = $sc->call('DELETE', $d_api_call_str);                        
					} catch (exception $e) {

					}
				}
			}
		} catch (exception $e) {

		}

        $sql_delete = "delete from share_buttons where bid='".$bid."' and shop='".mysql_real_escape_string($shop)."'";
        #echo $sql_update;            
        $result_sql = mysql_query($sql_delete);
        if (!$result_sql) {
            echo 'Invalid query: '.mysql_error();
        } else {
            echo "success";
        }        
    } else {
        echo 'Invalid query: '.mysql_error();
    }
}

if($type == 'get_button_list') {
    $table = 'share_buttons';    
    $primaryKey = 'bid';
    
    $columns = array(        
        array('db' => 'b_title', 'dt' => 'b_title'),
        array('db' => 'b_target_id', 'dt' => 'b_target_id'),        
		array('db' => 'b_content', 'dt' => 'b_content'),        
		array('db' => 'page_id', 'dt' => 'page_id'),        
        array('db' => 'bid', 'dt' => 'bid')
    );

    $extraWhere = " shop='".mysql_real_escape_string($shop)."' ";
    require('ssp.class.php');

    $arr = SSP::complex($_POST, $sql_details, $table, $primaryKey, $columns, null, $extraWhere);
    $json = $arr;
    
    print_r(json_encode($json));
}

if($type == 'search_p') {
    $search_text= trim($_REQUEST['ss']);  
    $search_collection_id = trim($_REQUEST['sc']);  
    $search_type= trim($_REQUEST['st']);  
    $page = trim($_REQUEST['page']);
    $limit_str = 10;

    if($search_type == "all_coll"){
        $search_peram = "?limit=".$limit_str."&page=".$page;
        $search_products_data = $sc->call('GET', '/admin/products.json?collection_id='.$search_collection_id.$search_peram);  

        $total_prod_count = $sc->call('GET', '/admin/products.json?collection_id='.$search_collection_id); 
        $total_prod_count = intval($total_prod_count); 
    } else {
        if($search_text == ""){
            $search_peram = "?limit=".$limit_str."&page=".$page;
        } else {
            $search_peram = "?page=".$page;
        }
        if($search_text != ""){
            $search_peram .= "&title=".$search_text;
        }
        $search_products_data = $sc->call('GET', '/admin/products.json'.$search_peram);

        if($search_text != ""){
            $total_prod_count = count($search_products_data);
        } else {
            $total_prod_count = $sc->call('GET', '/admin/products/count.json');
        }
        $total_prod_count = intval($total_prod_count);
    }

    #echo "<pre>"; print_r($search_products_data);
    #echo $total_prod_count;

    if($total_prod_count > 0){        
        $p_str = "";
        for($i=0;$i<$limit_str;$i++){
            if($search_products_data[$i]["id"] != ""){
                $p_str_temp = '<div class="p_box" id="'.$search_products_data[$i]["id"].'"><span class="p_name">'.$search_products_data[$i]["title"].'</span><input type="button" class="btn primary p_add" value="Add" p_id="'.$search_products_data[$i]["id"].'" handle="'.$search_products_data[$i]["handle"].'" /></div>';
                $p_str .= $p_str_temp;
            }
        }

        $pagignation_str = "";        
        $total_page = $total_prod_count / $limit_str;
        if($total_page > 1){
            $pagignation_str = "Page: ";
            for($i=0;$i<$total_page;$i++){            
                $active_class="";
                if(($i+1) == $page){
                    $active_class=" active_page";
                }
                $pagignation_str .= "<a class='page_no".$active_class."' st='".$search_type."' sc='".$search_collection_id."' ss='".$search_text."'>".($i+1)."</a>";
            }
        }

        if($p_str != ""){
            echo $pagignation_str."|||".$p_str;
        } else {
            echo "no_products";
        }
    }
}

?>