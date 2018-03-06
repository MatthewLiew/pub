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
}else{
	$start_date = date("Y-m-d");
    $end_date = date("Y-m-d");
	//echo "mmmmmmmm mmmmm mmmmmmm mmmmmmmmm mmmmmmmmm" . $start_date; 
	//exit; 
	if(isset($_POST['start_date'])){
		$start_date = $_POST['start_date']; 
		// $datetime =   $_POST['start_date']; 
        // $start_dt = strtotime($datetime);
		
		$end_date = $_POST['end_date'];
		// $datetime =   $_POST['end_date']; 
        // $end_dt = strtotime($datetime);
		
		$system_list = get_system_list_by_date($start_date, $end_date);
	}else{
		$system_list = get_system_list();
	}
?>
<!-- Page level plugin CSS-->
<link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8 col-md-4 .col-xl-4">
                <h1>System Log</h1>
            </div>
        </div>
        
		<div class="alert alert-success"  id="show_error" role="alert" style="display:none;"></div>
		
		<div class="row">
			&nbsp;
        </div>
		<form action='system_log.php' method='post'>
			<div class="row">
				<div class="col-sm-2 col-md-2 .col-xl-2">
					Start Date
				</div>
				<div class="col-sm-3 col-md-3 .col-xl-3" style="margin-top: -8px;">
					<input class="form-control" type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
				</div>
				<div class="col-sm-2 col-md-2 .col-xl-2">
					End Date
				</div>
				<div class="col-sm-3 col-md-3 .col-xl-3" style="margin-top: -8px;">
					<input class="form-control" type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
				</div>
				<div class="col-sm-2 col-md-2 .col-xl-2" style="margin-top: -8px;">
					<input class="btn btn-primary" type="submit" id="search" name="search" value="Search" style="margin-right:15px">
				</div>
			</div>
		</form>
		
		<div class="row">
            <div class="col-sm-8 col-md-4 .col-xl-4">
			&nbsp;
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="System_Log" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Action</th>
								<th>Action By</th>                                
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Action</th>
								<th>Action By</th>                                
                                <th>Date</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
							foreach ($system_list as $row) {
								echo "<tr>";
									echo "<td>" . $row['action_text']. "</td>";
									echo "<td>" . $row['user_id'] . " - " . $row['user_name'] . "</td>";
									$datetime =  $row['action_date']; 
                                    $dt = strtotime($datetime); 
                                    echo "<td>" . date("d-m-Y H:i:s", $dt) . "</td>"; 
								echo "</tr>";
							}
							?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
        </div>

        <div class="modal clickable" id="ChangePasswordUser" tabindex="-1" role="dialog" aria-labelledby="ChangePasswordUser" aria-hidden="true">
            <form action='user_manage.php' method='post' id='ChangePasswordForm'>
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog  modal-lg vertical-align-center">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Change Password</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            
                            <div class="modal-header">
                                <span class="modal-error" style="color:red; font-size: smaller;"></span>
                            </div>

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Change Password</button>                                
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal clickable" id="PreviewUser" tabindex="-1" role="dialog" aria-labelledby="PreviewUser" aria-hidden="true">
            <form action='user_manage.php' method='post' id='UserForm'>
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog  modal-lg vertical-align-center">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Edit User</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            
                            <div class="alert alert-danger" role="alert" id="modal_error" style="display:none;"></div>

                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                                <!-- <button type="submit" class="btn btn-primary" data-dismiss="modal" name="submit">Save</button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="modal clickable" id="PreviewDelete" tabindex="-1" role="dialog" aria-labelledby="PreviewDelete" aria-hidden="true">
            <form action='user_manage.php' method='post' id='DeleteForm'>
                <div class="vertical-alignment-helper">
                    <div class="modal-dialog vertical-align-center">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Delete User</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            
                            <div class="modal-body">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </div>

    <script type="text/javascript">
        function doAction(action, id) {
            if (action == 'edit') {
                $('#PreviewUser .modal-body').empty();
                $('#PreviewUser .modal-title').text("Edit User");
                $('#PreviewUser .modal-body').load('get_user_info.php?table_id=' + id + '&action=edit', function () {
                    $('#PreviewUser').modal({show: true});
                });
                //$('#PreviewUser').modal({show:true});
            } else if (action == 'change') {
                $('#ChangePasswordUser .modal-body').empty();
                //$('#ChangePasswordUser .modal-title').text("Create User");
                $('#ChangePasswordUser .modal-body').load('get_user_info.php?table_id=' + id + '&action=change', function () {
                    $('#ChangePasswordUser').modal({show: true});
                });
            } else if (action == 'new') {
                $('#PreviewUser .modal-body').empty();
                $('#PreviewUser .modal-title').text("Create User");
                $('#PreviewUser .modal-body').load('get_user_info.php?table_id=0&action=new', function () {
                    $('#PreviewUser').modal({show: true});
                });
            }
            else if (action == 'delete') {
                $('#PreviewDelete .modal-body').empty();
                //$('#PreviewDelete .modal-title').text("Delete User Confirm");
                //$('#PreviewUser .modal-body').text("Are you sure to Delete? Click 'Delete' To Confrim");
                $('#PreviewDelete .modal-body').load('get_user_info.php?table_id=' + id + '&action=delete', function () {
                    $('#PreviewDelete').modal({show: true});
                });
            }
        }
        
        function updateError (error) {
            if (error == true) {
                //$(".error").show(500);
                $('.modal-error').text("");
            }else{
                //$(".error").hide(500);
                $('.modal-error').text("Password Not Match!");
             }
        };

        function checkSame() {
            var passwordVal = $("input[name=user_pass]").val();
            var checkVal = $("input[name=user_pass_confirm]").val();
            if (passwordVal == checkVal) {
                return true;
            }
            return false;
        };

        $(document).ready(function ($) {
           $('#System_Log').dataTable( {
				aaSorting: [[2, 'desc']]
			  // "columnDefs": [
				// { "orderable": false, "targets": 0 }
			  // ]
			});
        });

    </script>
    <style>
        form label {font-weight:bold;}
    </style>

<?php } ?>
<?php include 'footer.php'; ?>