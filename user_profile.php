<?php define('RESTRICTED', true); ?>
<?php include 'header.php'; ?>
<?php 
	$submit_result = array();
    if(isset($_POST['submit'])){
        $table_id = $_POST['table_id'];
        $user_id = $_POST['user_id'];
        $submit_result = update_user($table_id, $user_id, $_POST);
    }
?>
<?php $result = get_user_by_user_id($_SESSION['login_user_id']); ?>
<?php //print_r($result); ?>

<form method="post" href="user_profile.php">
    <div class="content-wrapper">
        <div class="container-fluid">
			<?php 
			if(!empty($submit_result)){
				?>
					<div class="alert alert-success"  id="show_error" role="alert" style=""><?php echo $submit_result[1]; ?></div>
				<?php
			}
			?>
			
           <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserIDLabel">User Name</label>
                    </div>
                    <div class="col-md-10">
                        <input disabled class="form-control" id="UserID" name="UserID_Disabled" type="number" aria-describedby="UserID_Disabled" placeholder="Enter ID" value="<?php echo $result['user_id']; ?>">
                        <input class="form-control" id="table_id" name="table_id" type="hidden" value="<?php echo $result['ID']; ?>">
                        <input class="form-control" id="user_id" name="user_id" type="hidden" data-error="Please Ener User Phone" aria-describedby="UserID" placeholder="Enter ID" value="<?php echo $result['user_id']; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserIDLabel">Contact Person Name</label>
                    </div>
                    <div class="col-md-10">
                        <input class="form-control" id="user_name" name="user_name" data-error="Please Ener User Name" type="text" aria-describedby="UserName" placeholder="Enter Name" value="<?php echo $result['user_name']; ?>" required>
                        <!--  oninvalid="this.setCustomValidity('Please Enter User Name')" -->
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserIDLabel">Contact Phone</label>
                    </div>
                    <div class="col-md-10">
                        <input class="form-control" id="user_phone" name="user_phone" data-error="this.setCustomValidity('Please Enter Valid Phone')" type="number" aria-describedby="UserPhone" placeholder="EnterPhone" value="<?php echo $result['user_phone']; ?>" required>
                    </div>
                </div>
            </div>
            <!-- 
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserAddressLabel">Address</label>
                    </div>
                    <div class="col-md-10">
                        <input class="form-control" id="user_address" name="user_address" type="text" aria-describedby="User Address" placeholder="Enter Address" value="<?php echo $result['user_address']; ?>">
                    </div>
                </div>
            </div>
            -->
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserEmailLabel">Email</label>
                    </div>
                    <div class="col-md-10">
                        <input class="form-control" id="user_email" name="user_email" type="email" data-error="this.setCustomValidity('Please Enter Valid Email')" aria-describedby="emailHelp" placeholder="Enter Email" value="<?php echo $result['user_email']; ?>">
                    </div>
                </div>
            </div>
            <?php
                if($_SESSION['login_user_role']=='1'){
            ?>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserRoleLabel">Role</label>
                    </div>
                    <div class="col-md-10" class="form-control" id="UserRole">
                        <select class="form-control" data-size="5" id="user_role" name="user_role" >
                            <option value="1" <?php if ($result['user_role'] == 1) echo 'selected' ?>>Administrator</option>
                            <option value="2" <?php if ($result['user_role'] == 2) echo 'selected' ?>>User</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <div class="col-md-2">
                        <label for="UserStatusLabel">Status</label>
                    </div>
                    <div class="col-md-10">
                        <select class="form-control" data-size="5" id="user_status" name="user_status" >
                            <option value="1" <?php if ($result['user_status'] == 1) echo 'selected' ?>>Active</option>
                            <option value="0" <?php if ($result['user_status'] == 0) echo 'selected' ?>>Disable</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php 
                }
            ?>
            <button type="submit" name="submit" class="btn btn-primary">Save Profile</button>
        </div>
    </div>
</form>
<?php include 'footer.php'; ?>