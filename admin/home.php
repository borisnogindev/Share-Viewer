<?php
header("Access-Control-Allow-Origin: *");
include("../config.php");
include("../shopify_api.php");

$date_filter_condition = "";
if (!empty($_GET["start"]) && !empty($_GET["end"])){
	$date_filter_condition = " and bts.created_date between '".date("Y-n-d", strtotime($_GET["start"]))." 00:00:00 ' and '".date("Y-n-d", strtotime($_GET["end"]))." 23:59:59' ";
}

$sql_total_shares = "SELECT bt.bid FROM share_buttons bt WHERE bt.shop = '$shop' ";
$res_total_shares = mysql_query($sql_total_shares);

?>    
<html>
<head>
<?php include 'header.php'; ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
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
						<div style="display:inline-block;width:100%">
							<div class="section-row">   
								<div class="section-cell"  style="box-shadow: none;">
									<div class="cell-container" style="border-bottom: 1px solid #ebeef0;">
										<div class="cell-column">
											<label class="title_heading">Dashboard</label> 
										</div>
									</div>
								</div>
							</div>
							<div class="section-row">   
								<div class="section-cell"  style="box-shadow: none;">
									<div class="cell-container" style="border-bottom: 1px solid #ebeef0;">
										<div class="cell-column">
			<form method="get" id="stats_filter">				
 <input id="e2" name="e2">
 <input type="hidden" id="start" name="start">
 <input type="hidden" id="end" name="end">
 <input type="hidden" id="shop" name="shop" value="<?php echo $shop; ?>">
 
 
 <?php if (!empty($_GET["start"]) && !empty($_GET["end"])){ ?>
	Date From: <?php echo $_GET["start"]; ?> To: <?php echo $_GET["end"]; ?>	
<?php } ?>
 </form>
 	</div>
									</div>
								</div>
							</div>
						<hr class="hr_bottom">
						<?php if (mysql_num_rows($res_total_shares) > 0) { ?>
						<div id="button_count_chart_div"></div>
						<?php } else { ?>
						There are no share Buttons to show on Graph.
						<?php } ?>
						
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<div id="loader" style="display:none"></div> 
<script>
    	$(document).ready(function () {
		ShopifyApp.Bar.loadingOff();
		var shop = "<?php echo $shop; ?>";
		// for success message ShopifyApp.flashNotice("Settings have been saved successfully!");
		var ranger = $("#e2").daterangepicker({
         datepickerOptions : {
             numberOfMonths : 2
        }});
    	});
		$(document).on('change', '#e2', function () {
			$("#start").val();
			$("#end").val();
			var date_value=$.trim($(this).val());
			if (date_value != "") {
				date_selected = JSON.parse(date_value);
				$("#start").val(date_selected.start);
				$("#end").val(date_selected.end);
			}
			$("#stats_filter").submit();
		});
		
		<?php if (mysql_num_rows($res_total_shares) > 0) { ?>
		google.load('visualization', '1', {packages: ['corechart', 'bar']});
		google.setOnLoadCallback(drawMultSeries);

		function drawMultSeries() {
			
		  var graph_array = [['Buttons', 'Shares', 'Bounces']];
			<?php 
		while ($data_total_shares = mysql_fetch_assoc($res_total_shares)) {
		$sql_total_shares = "SELECT sum( bts.shared ) AS total_shares, sum( bts.bounce ) AS total_bounces
				FROM button_stats bts where 1 = 1 and bts.bid = ".$data_total_shares["bid"]." $date_filter_condition 
				GROUP BY bts.bid limit 1";
				$total_shares = 0;
		$total_bounces = 0;
		$button = $data_total_shares["bid"];
				$sql_total_shares_rs = mysql_query($sql_total_shares);
				if ($sql_total_shares = mysql_fetch_array($sql_total_shares_rs))  {
		$total_shares = $sql_total_shares["total_shares"];
		$total_bounces = $sql_total_shares["total_bounces"];
		
		}
		?>
			graph_array.push(["<?php echo "Button " . $button; ?>", <?php echo (int)$total_shares; ?>, <?php echo (int)$total_bounces; ?>]);
		<?php } ?>
		
		

		
		  var data = google.visualization.arrayToDataTable(graph_array);

		  var options = {
			title: 'Stats for Share Buttons',
			chartArea: {width: '50%'},
			hAxis: {
			  title: 'Count Share/Bounces',
			  minValue: 0
			},
			vAxis: {
			  title: 'Buttons'
			}
		  };

		  var chart = new google.visualization.BarChart(document.getElementById('button_count_chart_div'));
		  chart.draw(data, options);
		}
		<?php } ?>
</script>
</body>
</html>               