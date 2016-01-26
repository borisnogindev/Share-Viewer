<?php
header("Access-Control-Allow-Origin: *");
include("../config.php");
include("../shopify_api.php");

$sql_fb = "select facebook_app_id from app where shop='".mysql_real_escape_string($shop)."'";
$result_sql = mysql_query($sql_fb);
$app_id = "";
if (mysql_num_rows($result_sql) > 0) {
$sql_fb_r = mysql_fetch_array($result_sql);
$app_id = $sql_fb_r["facebook_app_id"];
}
?>    
<html>
<head>
<?php include 'header.php'; ?>
<script type="text/javascript">
            ShopifyApp.init({
                apiKey: '<?= SHOPIFY_API_KEY ?>',
                shopOrigin: 'https://<?= $shop ?>'
            });
        </script>
<title><?php echo APP_NAME; ?></title>
</head>
<body>
<div class="section">
<div class="section-content">   
<?php if ($install_status == '0') { ?>
    <div class="section-row install_status">
        <div class="section-cell" style="box-shadow: none;text-align: center;">
            <label>Please wait... the app is completing its setup.</label>
        </div>
    </div>
<?php } ?>
<div class="section-row">

    <div class="section-listing">
        <div class="section-options">
            <?php include 'menu.php'; ?>
            <div class="section-content tab-content" >
            <label style="font-size:17px;">Instructions</label> 
			<div class="alert alert-success" style="display: none;"><b>Success!</b> Your APP ID has saved successfully.</div>
            <div id="tab_1">
                <div class="section-row">   
                    <div class="section-cell"  style="box-shadow: none;">
                        <div class="cell-container" style="border-bottom: 1px solid #ebeef0;">
                            <div class="cell-column">
                                <label style="font-size:17px;">Add this code to your theme.liquid :</label> 
                            </div>
                        </div>
						
						<div class="cell-column alert alert-info">
							<ol>
							<li> If dont have Facebook meta tags present in head section, Please Copy and Paste following Code immediate after <pre><head></pre> tag:- <br> 
							<input class="code" type="text" readonly="readonly" value="{% include 'share_viewer_button' with 'meta_tags' %} " >   
							</li>
							<li> If dont have Facebook SKD present in body section, Please Copy and Paste following Code immediate after <pre><body></pre> tag:- <br> 
							<input class="code" type="text" readonly="readonly" value="{% include 'share_viewer_button' with 'facebook_script' %} " >   
							</li>
							<li> AT the End, Please Copy and Paste following Code before <pre></body></pre> tag:- <br> 
							<input class="code" type="text" readonly="readonly" value="{% include 'share_viewer_button' with 'share_tracker' %} " >
							</li>
							</ol>
						</div>                       
                        
						<div class="cell-column">
							<input type="text" id="facebook_app_id" placeholder="Enter Facebook APP Id" value="<?php echo $app_id; ?>">   
                        </div>
						
						<div class="cell-column">
                            <input type="submit" value="Save" id="facebook_app_id_save">   
                        </div>
                           
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<div id="loader" style="display:none"></div> 
<link rel="stylesheet" href="assets/css/jquery.minicolors.css">
<style>
    .code{text-align: center;background-color: #ccc;font-size: 15px;height: 45px;}
</style>
<script>
    $(document).ready(function(){
        ShopifyApp.Bar.loadingOff();
    });
	
	$(document).on('click','#facebook_app_id_save',function(){
		var facebook_app_id = $.trim($("#facebook_app_id").val());
        $(".loader_popup").show();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                type: "save_app_id", 
                shop: "<?= $shop ?>", 
                facebook_app_id: facebook_app_id
            },
            success: function (data) {
                $(".loader_popup").hide();
				$(".alert-success").show();
				$('html,body').animate({ scrollTop: $(".tab-content").offset().top}, 1000);
				setTimeout(function () {
					$(".alert-success").hide();
				}, 3000);
            }
        });
	});
</script>
</body>
</html>               