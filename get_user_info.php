<?php
include 'config/function.php';
session_start();
//print_r($_POST);
if (isset($_POST['table_id'])) {
    $table_id = $_POST['table_id'];
    $user_id = $_POST['user_id'];
    $case = 'new';
    if (isset($_POST['status'])) {        
        if ($_POST['status'] == 'change') {
            $case = 'change';
            $user_pass = $_POST['user_pass'];
            $result = update_password($table_id, $user_id, $user_pass);
            $_SESSION['message'] = "Update Password Successful!";
        } else {
            $case = 'delete';
            $result = delete_user($table_id, $user_id);
        }
    } elseif ($table_id == 0) {
        $case = 'new';
        $result = save_user($user_id, $_POST);
    } else {
        $case = 'edit';
        $result = update_user($table_id, $user_id, $_POST);
    }
    echo json_encode(array("case" => $case, "condition" => $result[0], "error" => $result[1]));
} elseif (isset($_GET['action'])) {
    $action = $_GET['action'];
    $table_id = $_GET['table_id'];
    if ($action == 'delete') {
        $result = get_user_by_id($table_id);
        ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-12">
                    <input class="form-control" id="status" name="status" type="hidden" value="delete">
                    <input class="form-control" id="table_id" name="table_id" type="hidden" value="<?php echo $table_id; ?>">
                    <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $result['user_id']; ?>">
                    Are you sure to Delete Following User ? <br/>
                    User Name - <b>'<?php echo $result['user_id']; ?>' ?</b> <br/>
                    Company Name - <b> '<?php echo $result['user_name']; ?>' </b>
                </div>
            </div>
        </div>
        <?php
    } else if ($action == 'change') {
        $result = get_user_by_id($table_id);
        ?>
        <input class="form-control" id="status" name="status" type="hidden" value="change">
        <input class="form-control" id="table_id" name="table_id" type="hidden" value="<?php echo $table_id; ?>">
        <input class="form-control" id="user_id" name="user_id" type="hidden" value="<?php echo $result['user_id']; ?>">
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Password</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_pass" name="user_pass" type="password" data-error="Ener Password" placeholder="Enter Password" value="<?php echo $result['user_pass']; ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Confirm Password</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_pass_confirm" name="user_pass_confirm" type="password" data-error="Enter Confirm Password" placeholder="Enter Password" value="<?php echo $result['user_pass']; ?>">
                </div>
            </div>
        </div>
        <?php
    } else if ($action == 'edit') {
        //echo "<form action='user_manage.php' method='post' id='myForm'>";
        $result = get_user_by_id($table_id);
        ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">User Name</label>
                </div>
                <div class="col-md-8">
                    <input disabled class="form-control" id="UserID" name="UserID_Disabled" type="number" aria-describedby="UserID_Disabled" placeholder="Enter ID" value="<?php echo $result['user_id']; ?>">
                    <input class="form-control" id="table_id" name="table_id" type="hidden" value="<?php echo $table_id; ?>">
                    <input class="form-control" id="user_id" name="user_id" type="hidden" data-error="Please Ener User ID" aria-describedby="UserID" placeholder="Enter ID" value="<?php echo $result['user_id']; ?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Company Name</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_name" name="user_name" data-error="Please Ener User Name" type="text" aria-describedby="UserName" placeholder="Enter Name" value="<?php echo $result['user_name']; ?>" required>
                    <!--  oninvalid="this.setCustomValidity('Please Enter User Name')" -->
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Contact Phone</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_phone" name="user_phone" data-error="this.setCustomValidity('Please Enter Valid Phone')" type="number" aria-describedby="UserPhone" placeholder="Enter Phone" value="<?php echo $result['user_phone']; ?>" required>
                </div>
            </div>
        </div>
		<!-- 
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserAddressLabel">Address</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_address" name="user_address" type="text" aria-describedby="UserAddress" placeholder="Enter Address" value="<?php echo $result['user_address']; ?>">
                </div>
            </div>
        </div>
		-->
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserEmailLabel">Email</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_email" name="user_email" type="email" data-error="this.setCustomValidity('Please Enter Valid Email')" aria-describedby="emailHelp" placeholder="Enter Email" value="<?php echo $result['user_email']; ?>">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserRoleLabel">Role</label>
                </div>
                <div class="col-md-8" class="form-control" id="UserRole">
                    <select class="form-control" data-size="5" id="user_role" name="user_role" >
                        <option value="1" <?php if ($result['user_role'] == 1) echo 'selected' ?>>Administrator</option>
                        <option value="2" <?php if ($result['user_role'] == 2) echo 'selected' ?>>Customer</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserStatusLabel">Status</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" data-size="5" id="user_status" name="user_status" >
                        <option value="1" <?php if ($result['user_status'] == 1) echo 'selected' ?>>Active</option>
                        <option value="0" <?php if ($result['user_status'] == 0) echo 'selected' ?>>Disable</option>
                    </select>
                </div>
            </div>
        </div>        
        <?php
    } elseif ($action == "new") {
        ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">User Name</label>
                </div>
                <div class="col-md-8">                    
                    <!-- <input disabled class="form-control" id="UserID" name="UserID_Disabled" type="number" aria-describedby="UserID_Disabled" placeholder="Enter ID" value="<?php echo $result['user_id']; ?>"> -->
                    <input class="form-control" id="table_id" name="table_id" type="hidden" value="0">
                    <input class="form-control" id="user_id" name="user_id" type="number" data-error="Please Ener User Name" aria-describedby="UserID" placeholder="Enter User Phone Number" value="" required>
					<input class="form-control" id="new_user_pass" name="new_user_pass" type="hidden" data-error="Please Ener User Password" aria-describedby="UserID" placeholder="Enter Password" value="<?php echo randomPassword(); ?>" required>
                </div>
            </div>
        </div>
		<!-- 
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Password</label> 
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="new_user_pass" name="new_user_pass" type="hidden" data-error="Please Ener User Password" aria-describedby="UserID" placeholder="Enter Password" value="<?php echo randomPassword(); ?>" required>
                </div>
            </div>
        </div>
		-->
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Company Name</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_name" name="user_name" data-error="Please Ener Company Name" type="text" aria-describedby="Company Name" placeholder="Enter Name" value="" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserIDLabel">Contact Phone</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_phone" name="user_phone" data-error="this.setCustomValidity('Please Enter Valid Phone')" type="number" aria-describedby="User Phone" placeholder="Enter Phone" value="" required>
                </div>
            </div>
        </div>
		<!--
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserAddressLabel">Address</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_address" name="user_address" type="text" aria-describedby="UserAddress" placeholder="Enter Address" value="">
                </div>
            </div>
        </div>
		-->
        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserEmailLabel">Email</label>
                </div>
                <div class="col-md-8">
                    <input class="form-control" id="user_email" name="user_email" type="email" data-error="this.setCustomValidity('Please Enter Valid Email')" aria-describedby="emailHelp" placeholder="Enter Email" value="">
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserRoleLabel">Role</label>
                </div>
                <div class="col-md-8" class="form-control" id="UserRole">
                    <select class="form-control" data-size="5" id="user_role" name="user_role" >
                        <option value="2">Customer</option>
                        <option value="1">Administrator</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-row">
                <div class="col-md-4">
                    <label for="UserStatusLabel">Status</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" data-size="5" id="user_status" name="user_status" >
                        <option value="1">Active</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
            </div>
        </div>
        <?php
    } elseif ($action == "delete") {
        
    }
}
?>