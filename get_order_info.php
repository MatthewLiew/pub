<?php
include 'config/function.php';
date_default_timezone_set('asia/singapore');
session_start();
$current_date = date("Y-m-d");
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    
    $case = 'new';
    if (isset($_POST['status'])) {
        if ($_POST['status'] == 'delete') {
             $case = 'delete';
            $order_number = $_POST['order_number'];
            $result = delete_order($order_id, $order_number);
        }
    } elseif ($order_id == 0) {
        $case = 'new';
		//print_r($_POST);
		if($_POST['customer_id']==""){
			//echo "create customer";
			/***** Create New Customer **********/
			$result = save_user_order($_POST['user_id'], $_POST);
		}else{
			//echo "no create customer";
			$result = save_order($user_login, $_POST);
		}
		//exit;
		
		
		
        //$result = save_order($user_login, $_POST);
    } else {
        $case = 'edit';
        $order_number = $_POST['order_number'];
        $result = update_order($order_id, $order_number, $_POST);
    }
    echo json_encode(array("case" => $case, "condition" => $result[0], "error" => $result[1]));
} elseif (isset($_GET['check'])) {
	echo check_existing_user($_GET['check']);
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
    $order_id = $_GET['order_id'];   
    if($action == 'show'){
        $invoice_number = $_GET['invoice_number']; 
        $get_water_pumped_history = get_water_pumped_history($order_id);
        ?>
        <div>Order Number - <?php echo $invoice_number; ?></div> <br/>
        <table class="table table-bordered" id="showOrder" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Pumped Quantity (m<sup>3</sup>)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    /*
                    echo "<tr>";
                    echo "<td>AAAAA</td>";
                    echo "<td>BBB</td>";
                    echo "</tr>";
                     * 
                     */
                    if($get_water_pumped_history->num_rows==0){
                        echo "<tr>";
                        echo "<td colspan='2' style='text-align:center'>There is No Order History";
                        //$dt = strtotime('2018-02-22 17:52:29');
                        //echo "<td>" . date("d-m-Y", $dt) . "</td>";myparents
                        
                        echo "</td>";
                        echo "</tr>";
                    }  else {
                        foreach ($get_water_pumped_history as $row){
                            echo "<tr>";
                            echo "<td>" . $row['action_value'] . "</td>";
                            $dt = strtotime($row['action_time']);
                            echo "<td>" . date("d-m-Y", $dt) . "</td>";
                            echo "</tr>";
                        } 
                    }
                    
                ?>
            </tbody>
        </table>
        <?php
    }
    elseif ($action == 'delete') {
        $result = get_order_by_id($order_id);
        ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-12">
                    <input class="form-control" id="status" name="status" type="hidden" value="delete">
                    <input class="form-control" id="order_id" name="order_id" type="hidden" value="<?php echo $order_id; ?>">
                    <input class="form-control" id="order_number" name="order_number" type="hidden" value="<?php echo $result['invoice_number']; ?>">
                    Are you sure to Delete Following Order ? <br/>
                    Order Number - <b>'<?php echo $result['invoice_number']; ?>' ?</b> <br/>
                </div>
            </div>
        </div>
        <?php
    } else if ($action == 'edit') {
        //echo "<form action='user_manage.php' method='post' id='myForm'>";
        $result = get_order_by_id($order_id);
        ?>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Order Number</label>
                </div>
                <div class="col-md-8">                    
                    <input class="form-control" id="order_id" name="order_id" type="hidden" value="<?php echo $result['order_id']; ?>">
                    <input class="form-control" id="order_number" name="order_number" type="hidden" value="<?php echo $result['invoice_number']; ?>">
                    <input class="form-control" value="<?php echo $result['invoice_number']; ?>" id="order_number" name="order_number" type="text" data-error="Ener Order Number" aria-describedby="Order Number" placeholder="Ener Order Number" value="" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Choose Customer</label>
                </div>
                <div class="col-md-8" class="form-control" id="Customer">
                    <!-- <select class="form-control" data-size="5" id="customer_id" name="customer_id" required> -->
					<select style="width:100%; height: 60px; " data-live-search="true" data-live-search-style="startsWith" class="selectpicker form-control"  id="customer_id" name="customer_id" required>
                        <option value="">Select Customer </option>
                        <?php
                        $user_list = get_user_list_by_customer();
                        foreach ($user_list as $user_row) {                            
                            ?>
                            <option value="<?php echo $user_row['ID']; ?>" <?php if ($result['customer_id'] == $user_row['ID']) echo "selected"; ?>> <?php echo $user_row['user_id'] . " - " . $user_row['user_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Quantity (m<sup>3</sup>)</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="order_qty" name="order_qty" data-error="this.setCustomValidity('Please Enter Valid Qty')" type="number" aria-describedby="Water Qty" placeholder="Water Qty" value="<?php echo $result['qty']; ?>" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Order Date</label>
                </div>
                <div class="col-md-8">
                    <?php
                        $datetime = $result['create_date']; 
                        $dt = strtotime($datetime); //make timestamp with datetime string 
                        //echo date("Y-m-d", $dt); //echo the year of the datestamp just created
                    ?>
                    <input class="form-control" id="order_date" name="order_date" data-error="this.setCustomValidity('Please Enter Valid Qty')" type="date" aria-describedby="Water Qty" placeholder="Water Qty" value="<?php echo date("Y-m-d", $dt); ?>" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Remark</label>
                </div>
                <div class="col-md-8">
                    <textarea class="form-control" id="order_remark" name="order_remark" placeholder="Remark" ><?php echo $result['remark']; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Order Status</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" data-size="5" id="order_status" name="order_status" >
                        <option value="1" <?php if ($result['status'] == 1) echo 'selected' ?>>Completed</option>
                        <option value="0" <?php if ($result['status'] == 0) echo 'selected' ?>>Pending</option>
                    </select>
                </div>
            </div>
        </div>
        <?php
    } elseif ($action == "new") {
        ?>
		<!-- <form action='order_manage.php' method='post' id='OrderForm'> -->
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="UserIDLabel">Order Number</label>
                </div>
				<div class="col-md-1" style="text-align:right; padding-top:5px;">
                    <label style="color:red">*</label>
                </div>
                <div class="col-md-8">                    
                    <input class="form-control" id="order_id" name="order_id" type="hidden" value="0">
                    <input class="form-control" id="order_number" name="order_number" type="text" data-error="Order Number" aria-describedby="OrderNumber" placeholder="Ener Order Number" value="" required>
                </div>
            </div>
        </div>
		
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="UserIDLabel">Choose Customer</label>
                </div>
				<div class="col-md-1" style="text-align:right; padding-top:5px;">
                    <label style="color:red">*</label>
                </div>
                <div class="col-md-8" class="form-control" id="Customer">
					<!-- 
					<select class="form-control" data-size="5" id="customer_id" name="customer_id" required>
					-->
					<select style="width:100%; height: 60px; " data-live-search="true" data-live-search-style="startsWith" class="selectpicker form-control"  id="customer_id" name="customer_id" placeholder="Select Customer"  required>
						<option value="">Select Customer</option>
                        <?php
                        $user_list = get_user_list_by_customer();
                        foreach ($user_list as $user_row) {
                            echo "<option value='" . $user_row['ID'] . "'>" . $user_row['user_id'] . " - " . $user_row['user_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>	
	
		<div class="form-group">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="UserIDLabel"></label>
                </div>
				<div class="col-md-1" style="text-align:right; padding-top:5px;">
                </div>
                <div class="col-md-8" class="form-control" style="font-size:smaller;"> 
					<a data-toggle="collapse" onclick="create_user_show()" href="#collapseCreateCustomer" id="create_order" role="button" aria-expanded="false" aria-controls="collapseCreateCustomer">
						<i class="fa fa-fw fa-plus "></i> Create New Customer
					</a>
                </div>
            </div>
        </div>
		<!-- Create Customer  -->
		<div class="collapse" id="collapseCreateCustomer" style="padding-bottom:10px;">
			<div class="card card-body">
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-3">
							<label for="UserIDLabel">User Name</label>
						</div>
						<div class="col-md-1" style="text-align:right; padding-top:5px;">
							<label style="color:red">*</label>
						</div>
						<div class="col-md-8">
							<input class="form-control" id="table_id" name="table_id" type="hidden" value="0">
							<input onkeydown="create_user_verify()" class="form-control" id="user_id" name="user_id" type="number" data-error="Please Ener User Name" aria-describedby="UserID" placeholder="Enter User Phone Number" value="">
							<input class="form-control" id="new_user_pass" name="new_user_pass" type="hidden" data-error="Please Ener User Password" aria-describedby="UserID" placeholder="Enter Password" value="<?php echo randomPassword(); ?>" required>
							
							<div role="alert" id="user_name_error" style="font-style: italic; color: red; font-size: small; margin-top:15px; padding: .75rem 1.25rem;  border-radius: .25rem; display:none;"></div>
						</div>
					</div>
				</div>
				
				<!--
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-3">
							<label for="UserIDLabel">Password</label>
						</div>
						<div class="col-md-1" style="text-align:right; padding-top:5px;">
							<label style="color:red">*</label>
						</div>
						<div class="col-md-8">
							<input onkeydown="create_user_verify()" class="form-control" id="new_user_pass" name="new_user_pass" type="password" data-error="Please Ener User Password" aria-describedby="UserID" placeholder="Enter Password" value="">
						</div>
					</div>
				</div>
				-->
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-3">
							<label for="UserIDLabel">Company Name</label>
						</div>
						<div class="col-md-1" style="text-align:right; padding-top:5px;">
							<label style="color:red">*</label>
						</div>
						<div class="col-md-8">
							<input onkeydown="create_user_verify()" class="form-control" id="user_name" name="user_name" data-error="Please Ener Company Name" type="text" aria-describedby="Company Name" placeholder="Enter Name" value="">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-3">
							<label for="UserIDLabel">Contact Phone</label>
						</div>
						<div class="col-md-1" style="text-align:right; padding-top:5px;">
							<label style="color:red">*</label>
						</div>
						<div class="col-md-8">
							<input onkeydown="create_user_verify()" class="form-control" id="user_phone" name="user_phone" data-error="this.setCustomValidity('Please Enter Valid Phone')" type="number" aria-describedby="User Phone" placeholder="Enter Phone" value="">
						</div>
					</div>
				</div>
				<!--
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-3">
							<label for="UserAddressLabel">Address</label>
						</div>
						<div class="col-md-1" style="text-align:right; padding-top:5px;">
						</div>
						<div class="col-md-8">
							<input class="form-control" id="user_address" name="user_address" type="text" aria-describedby="UserAddress" placeholder="Enter Address" value="" >
						</div>
					</div>
				</div>
				-->
				<div class="form-group">
					<div class="form-row">
						<div class="col-md-3">
							<label for="UserEmailLabel">Email</label>
						</div>
						<div class="col-md-1" style="text-align:right; padding-top:5px;">
						</div>
						<div class="col-md-8">
							<input class="form-control" id="user_email" name="user_email" type="email" data-error="this.setCustomValidity('Please Enter Valid Email')" aria-describedby="emailHelp" placeholder="Enter Email" value="" >
							<input class="form-control" id="user_role" name="user_role" type="hidden" value="2" >
							<input class="form-control" id="user_status" name="user_status" type="hidden" value="1" >
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Create Customer  -->
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="UserIDLabel">Quantity (m<sup>3</sup>)</label>
                </div>
				<div class="col-md-1" style="text-align:right; padding-top:5px;">
                    <label style="color:red">*</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="order_qty" name="order_qty" data-error="this.setCustomValidity('Please Enter Valid Qty')" type="number" aria-describedby="Water Qty" placeholder="Water Qty" value="" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-3">
                    <label for="UserIDLabel">Order Date</label>
                </div>
				<div class="col-md-1" style="text-align:right; padding-top:5px;">
                    <label style="color:red">*</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="order_date" name="order_date" data-error="this.setCustomValidity('Please Enter Valid Qty')" type="date" aria-describedby="Water Qty" placeholder="Water Qty" value="<?php echo $current_date; ?>" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Remark</label>
                </div>
                <div class="col-md-8">
                    <textarea class="form-control" id="order_remark" name="order_remark" placeholder="Remark" ></textarea>
                </div>
            </div>
        </div>		
		<!-- </form> -->
		
        <?php
    } elseif ($action == "delete") {
        
    }
}
?>