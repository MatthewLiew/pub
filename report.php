<?php define('RESTRICTED', true); ?>
<?php include 'header.php'; ?>

<?php
if($_SESSION['login_user_role']=='2'){
    ?>
    <div class="content-wrapper">
        <div class="container-fluid">
            Sorry, you are not allowed to access this page. Please browse Correct Page ! 
        </div>
    </div>
    <?php
    include 'footer.php';
}else{
	$status = 'order';
	if (isset($_GET['status'])) {
		$status = $_GET['status'];
	}
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d");
    if(isset($_POST['search'])){
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date']; 
		if($status=="order"){
			$customer_id = $_POST['customer_id'];
			$order_status = $_POST['order_status'];
			//$order_report = get_order_report($start_date, $end_date);
			$order_report = get_order_report_by_all_filter($start_date, $end_date, $customer_id, $order_status);
		}elseif($status=="sms"){
			$order_report = get_sms_report($start_date, $end_date);
		}elseif($status=="error"){
			$order_report = get_error_report($start_date, $end_date);
		}
       
    }
    ?>
    <div class="content-wrapper">
        <div class="container-fluid">
			
			<ul class="nav nav-tabs" role="tablist" style="padding-left: 10px;">
				<li class="nav-item">
					<a class="nav-link <?php if ($status == 'order') echo 'active'; ?>" href="report.php?status=order">Order Report</a>
				</li>
				<li class="nav-item">
					<a class="nav-link  <?php if ($status == 'sms') echo 'active'; ?>" href="report.php?status=sms">SMS Report</a>
				</li>
				<li class="nav-item">
					<a class="nav-link  <?php if ($status == 'error') echo 'active'; ?>" href="report.php?status=error">Facility Report</a>
				</li>
			</ul>
			
			<br/>
			
            <form action='report.php?status=<?php echo $status; ?>' method='post' id='ReportForm'>
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            Start Date
                        </div>
                        <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            <input class="form-control" type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
                        </div>
                    </div>
                    <br/>
                    <div class="form-row">
                         <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            End Date
                        </div>
                        <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            <input class="form-control" type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
                        </div>
                    </div>
                    <br/>
					<?php if($status=="order"){ ?>
                    <div class="form-row">
                         <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            Choose Customer
                        </div>
                        <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            <select class="form-control" data-size="5" id="customer_id" name="customer_id">
								<option value="">Select Customer </option>
								<?php
								$user_list = get_user_list_by_customer();
								foreach ($user_list as $user_row) {                            
									?>
									<option value="<?php echo $user_row['ID']; ?>" <?php if ($customer_id == $user_row['ID']) echo "selected"; ?>> <?php echo $user_row['user_id'] . " - " . $user_row['user_name']; ?></option>
									<?php
								}
								?>
							</select>
                        </div>
                    </div>
                    <br/>
                    <div class="form-row">
                         <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            Choose Order Status
                        </div>
                        <div class="col-2 col-md-3 col-sm-6 .col-xl-2">
                            <select class="form-control" data-size="5" id="order_status" name="order_status" >
								<option value="">Choose Order Status</option>
								<option value="1" <?php if ($order_status == '1') echo 'selected'; ?>>Completed</option>
								<option value="0" <?php if ($order_status == '0') echo 'selected'; ?>>Pending</option>
							</select>
                        </div>
                    </div>
                    <br/>
					<?php } ?>
                    <div class="form-row">
                        <div class="col-md-3 col-sm-6 .col-xl-2"> 
                        </div>
                        <div class="col-md-3 col-sm-6 .col-xl-2">
                            <input class="btn btn-primary" type="submit" id="search" name="search" value="Search" style="margin-right:15px">
							<?php
								if(($order_report->num_rows!=0)){ 
                                $link = "http://$_SERVER[HTTP_HOST]/get_csv_report.php";
                            ?>
                            <a href="export.php?start_date=<?php echo $start_date; ?>&end_date=<?php echo $end_date; ?>&customer_id=<?php echo $customer_id; ?>&order_status=<?php echo $order_status; ?>"> <input class="btn btn-primary" type="button" id="export" name="export" value="Export CSV"></a>
							
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form> 
            <?php if($status=="order"){ ?>
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-lg-12">
						<?php 
							if(isset($order_report)){
						?>
						<table id="" class="display dataTable no-footer" cellspacing="0" cellpadding="10px" width="100%" border="1"  style="border:1px solid #ccc">
							<thead>
								<tr>
									<th>Invoice Number</th>
									<th>Contact Person Name</th>
									<th style="text-align:right;">Qty (m<sup>3</sup>)</th>
									<th style="text-align:right;">Status</th>
									<th style="text-align:right;">Date</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								if(isset($order_report)){
								foreach ($order_report as $row){
										?>
										<tr>
											<td><?php echo $row['invoice_number']; ?></td>
											<td><?php echo $row['user_id'] . " - " . $row['user_name'] ; ?></td>
											<td style="text-align:right;"><?php echo $row['qty']; ?></td>
											<td style="text-align:right;">
												<?php 
													if($row['status']==0)
														echo "Pending";
													else
														echo "Completed";
												?>
											</td>
											<?php
												$datetime = $row['create_date']; 
												$dt = strtotime($datetime); 
											?>
											<td style="text-align:right;"><?php echo date("d-m-Y", $dt); ?></td>
										</tr>
										<?php
									}
								}
								else{
									?>
										<tr>
											<td colspan="5">There's no Transaction</td>
										</tr>
										<?php
								}
							?>
							</tbody>
						</table>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
        </div>
    </div>
	<style>
		th, td{
			padding:10px;
		}
	</style>
    <script type="text/javascript">		
        function Export_CSV_Report(link) {
            //alert(link);
            jQuery.ajax({
                    type: 'GET',
                    url: 'get_csv_report.php',
                    data: {action: 'order_report', start_date: <?php echo json_encode($start_date); ?>, end_date: <?php echo  json_encode($end_date); ?>, customer_id: <?php echo  json_encode($customer_id); ?>, order_status: <?php echo  json_encode($order_status); ?>},
                    dataType: 'JSON',
                    success: function (filelink) {
							alert(filelink);
                            window.location.href = filelink;
                    }, error: function () {
                            console.log("error");
                    }
            });
        }
    </script>
    

<?php } ?>
<?php include 'footer.php'; ?>