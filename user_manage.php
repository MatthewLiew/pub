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
?>
<!-- Page level plugin CSS-->
<link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8 col-md-4 .col-xl-4">
                <h1>User Management</h1>
            </div>
            <div class="col-sm-4 col-md-6 .col-xl-8" style="vertical-align: center; padding-top:12px">
                <a onclick="doAction('new', 0)"><button class="btn btn-primary">Create User</button></a>
            </div>
        </div>
        
		<div class="alert alert-success"  id="show_error" role="alert" style="display:none;"></div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Contact Person Name</th>
                                <th>Phone</th>
                                <!-- <th>Address</th>-->
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>User Name</th>
                                <th>Contact Person Name</th>
                                <th>Phone</th>
                                <!-- <th>Address</th>-->
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Registered Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            $user_list = get_user_list();
                            foreach ($user_list as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['user_id'] . "</td>";
                                echo "<td>" . $row['user_name'] . "</td>";
                                echo "<td>" . $row['user_phone'] . "</td>";
                                //echo "<td>" . $row['user_address'] . "</td>";
                                echo "<td>" . $row['user_email'] . "</td>";
                                if ($row['user_role'] == '1')
                                    echo "<td>Administrator</td>";
                                else
                                    echo "<td>User</td>";
                                echo "<td>" . $row['user_status'] . "</td>";
                                echo "<td>" . $row['user_registered'] . "</td>";
                                echo "<td>";
                                ?>
                            <a onclick="doAction('edit', <?php echo $row['ID']; ?>);"><i class='fa fa-pencil-square fa-5' style='font-size:30px; color:#367fa9' title='Edit User'></i></a>
                            &nbsp;&nbsp;
                            <a onclick="doAction('change', <?php echo $row['ID']; ?>);"><i class='fa fa-key fa-5' style='font-size:30px; color:red' title='Change Password'></i></a>
                            &nbsp;&nbsp;
                            <a onclick="doAction('delete', <?php echo $row['ID']; ?>);"><i class='fa fa-trash-o fa-5' style='font-size:30px; color:#367fa9' title='Delete User'></i></a>
                            <?php
                            //echo "<a onclick=\'doAction('edit')\'><i class='fa fa-pencil-square fa-5' style='font-size:30px; color:#367fa9' title='Edit User'></i></a>";
                            //echo "&nbsp;&nbsp;";
                            //echo "<a onclick='doAction(delete)'><i class='fa fa-trash-o fa-5' style='font-size:30px; color:#367fa9' title='Delete User'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
        </div>
		
		<div class="modal clickable" id="SaveSuccessful" tabindex="-1" role="dialog" aria-labelledby="SaveSuccessful" aria-hidden="true">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog  modal-lg vertical-align-center">
					<!-- Modal content-->
					<div class="modal-content">
						<!--
						<div class="modal-header">
							<h4 class="modal-title"></h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						-->
						<div class="modal-body"></div>
					</div>
				</div>
			</div>
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
            //ChangePasswordUser
            $(".modal").on("hidden.bs.modal", function(){
                $('#PreviewUser .modal-body').empty();
            });                     
            
            //$("input[name='user_pass']").keyup(function(){alert("here");updateError(checkSame());});
            //$("input[name='user_pass_confirm']").keyup(function(){updateError(checkSame());});
            
            //$("input[name=user_pass]").keyup(function(){updateError(checkSame());});
            //$("input[name=user_pass_confirm]").keyup(function(){updateError(checkSame());});
            
            jQuery('#ChangePasswordForm').on('submit', function (e) {
                if(checkSame()==true){
                    e.preventDefault();
                    jQuery.post('get_user_info.php',
                        $('#ChangePasswordForm').serialize(),
                        function (data, status, xhr) {
                            $('.modal-error').text(data.error);
                            if(data.condition==true){
                                $('#ChangePasswordUser .modal-body').empty();
                                location.reload();
                            }
                        }, "json");
                    }
                else
                    {
                        updateError(checkSame());
                        e.preventDefault();
                        $('#ChangePasswordForm').modal({show: true});
                    }
            });
            
            jQuery('#UserForm').on('submit', function (e) {
                e.preventDefault();
                jQuery.post('get_user_info.php',
                    $('#UserForm').serialize(),
                    function (data, status, xhr) {
						$("#modal_error").show();
                        $('#modal_error').text(data.error);
                        if(data.condition==true){
                            $('#PreviewUser .modal-body').empty();
                            $('#PreviewUser').modal('toggle');
							
							//$("#show_error").show();
							//$('#show_error').text(data.error);
							
							$('#SaveSuccessful .modal-body').text(data.error);
							$('#SaveSuccessful').modal({show: true});
							setTimeout(function(){ location.reload(); }, 1000);
                        }
                        //location.reload();
                        // do something here with response;
                    }, "json");
            });
            
            jQuery('#DeleteForm').on('submit', function (e) {   
                e.preventDefault();
                jQuery.post('get_user_info.php',
                    $('#DeleteForm').serialize(),
                    function (data, status, xhr) {
                        $('.modal-error').text(data.error);
                        if(data.condition==true){
                            $('#PreviewDelete .modal-body').empty();
							$('#PreviewDelete').modal('toggle');
							$("#show_error").show();
							$('#show_error').text(data.error);
							setTimeout(function(){ location.reload(); }, 1000);
                            //location.reload();
                        }else{
							$('#PreviewDelete').modal('toggle');
							$("#show_error").show();
							$('#show_error').text(data.error);
							setTimeout(function(){ location.reload(); }, 1000);
						}
                        //location.reload();
                        // do something here with response;
                    }, "json");
            });
        });

    </script>
    <style>
        form label {font-weight:bold;}
    </style>

<?php } ?>
<?php include 'footer.php'; ?>