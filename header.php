<?php
session_start();
ob_start();
if (defined('RESTRICTED')) {
    if (!isset($_SESSION['login_user_id'])) {
        header('Location: login.php');
        exit();
    }
} else {
    if (isset($_SESSION['login_user_id'])) {
        header('Location: index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="miemieko" content="">
        <title>PUB</title>
        <!-- Bootstrap core CSS-->
		
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="icon" href="images/vicon.png">
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin.min.js"></script>
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
        <script src="js/sb-admin-datatables.min.js"></script>
		
		<!--
		<script src="vendor/bootstrap/js/bootstrap-select.min.js"></script>
		<link href="vendor/bootstrap/css/bootstrap-select.min.css" rel="stylesheet"> 
		
		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>
		--> 
		<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<!-- Latest compiled and minified CSS
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css"> -->

		<!-- Latest compiled and minified JavaScript
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script> -->
		
		
		<script src="vendor/bootstrap/js/bootstrap-select.min.js"></script>
		<link href="vendor/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		
		<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
		
		<!--
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.js"></script>-->



    </head>
    <?php require("config/function.php"); ?>
    <?php
        if (isset($_SESSION['login_user_id'])) {
            $login_id = $_SESSION['login_id'];
            $login_user_id = $_SESSION['login_user_id'];
            $login_result = check_user_login_session($login_user_id);
            if($login_result[0] == false){
                //print_r($login_result); 
                session_destroy();
                //header('Location: login.php');
                //exit();
            }else {
                //header('Location: index.php');
                //exit();
            }
        }
            
            
    ?>
    <?php date_default_timezone_set('asia/singapore'); ?>
	
    <!-- Menu Start -->
    <body class="fixed-nav sticky-footer bg-dark" id="page-top" onload="startTime()">
        <!-- Navigation-->
        <?php
			if (basename($_SERVER['PHP_SELF']) != 'login.php') {
        ?>
			<audio id="xyz" src="css/alert_tones.mp3" preload="auto"></audio>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
                <a class="navbar-brand" href="index.php">Welcome To PUB Water Station</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php include 'menu.php'; ?>
                    <ul class="navbar-nav ml-auto" style="margin: -2.5px -1px;">
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="modal">
                                <i class="fa fa-fw fa-clock-o"></i>
                                <span id="ShowTime"></span>
                                <?php //echo date("h:i A");  ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-fw fa-user"></i>
                                <?php
                                $user_info = get_user_by_user_id($_SESSION['login_user_id']);
                                echo $user_info['user_name'];
                                ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="alertsDropdown" style="width: 250px;">
                                <a href="user_profile.php"><h6 class="dropdown-header">Edit Profile</h6></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <span class="text-success">
                                        <strong>
                                            <i class="fa fa-phone fa-fw"></i><?php echo $user_info['user_phone']; ?></strong>
                                    </span>
                                    <span class="small float-right text-muted"></span>
                                    <div class="dropdown-message small"></div>
                                </a>
                                <!-- 
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <span class="text-danger">
                                        <strong>
                                            <i class="fa fa-map-marker  fa-fw"></i><?php echo $user_info['user_address']; ?></strong>
                                    </span>
                                    <span class="small float-right text-muted"></span>
                                    <div class="dropdown-message small"></div>
                                </a>
                                -->
                                <div class="dropdown-divider"></div>
                                <a onclick="doActionTop('change', <?php echo $user_info['ID']; ?>);" style="cursor:pointer"><h6 class="dropdown-header">Change Password</h6></a>    
                                <!-- <a class="dropdown-item small"><?php echo date("h:i A"); ?></a> -->
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="modal" data-target="#LogoutModal">
                                <i class="fa fa-fw fa-sign-out"></i>Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="modal clickable" id="ChangePasswordUserTop" tabindex="-1" role="dialog" aria-labelledby="ChangePasswordUserTop" aria-hidden="true">
                <form action='user_profile.php' method='post' id='ChangePasswordFormTop'>
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
			
			<div class="modal clickable" id="ShowErrorAlert" tabindex="-1" role="dialog" aria-labelledby="ShowErrorAlert" aria-hidden="true">
				<div class="vertical-alignment-helper">
					<div class="modal-dialog  modal-md vertical-align-center">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title"></h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body">

							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" onclick="StopSound()">Close</button>
								<!-- <button type="submit" class="btn btn-primary">Change Password</button> -->
							</div>
						</div>
					</div>
				</div>
            </div>
            <?php
        }else{
			echo '<span id="ShowTime" style="display:none"></span>';
		}
        ?>

        <script type="text/javascript">
		
			function CheckEvery15Minute() {
				//Check Error or Not
				$.post("check_error.php", { type : 'alert' },  
					function(result){  
						//if the result is 1
						// 1 = "Open Door for 30 minutes. Close the Door"
						// 2 = "Pump Done for 15 minutes. Close the Door!";
						// 3 = "Pump Done for 15 minutes. Close the Door!";
						if(result == 1){  							
							document.getElementById("xyz").loop = true;
							document.getElementById('xyz').play();
							$('#ShowErrorAlert .modal-title').text("Alert");
							//$('#ShowErrorAlert .modal-body').text("Open Door for 30 minutes. Close the Door!");
							$('#ShowErrorAlert .modal-body').text("Station 1 Door Open. Please Check");
							$('#ShowErrorAlert').modal({show: true});
						}else if(result == 2) {  
							document.getElementById("xyz").loop = true;
							document.getElementById('xyz').play();
							$('#ShowErrorAlert .modal-title').text("Alert");
							//$('#ShowErrorAlert .modal-body').text("Pump Done for 15 minutes. Close the Door!");
							$('#ShowErrorAlert .modal-body').text("Station 1 Door Open. Please Check");
							$('#ShowErrorAlert').modal({show: true});
						}else if(result == 3){
							document.getElementById("xyz").loop = true;
							document.getElementById('xyz').play();
							$('#ShowErrorAlert .modal-title').text("Alert");
							//$('#ShowErrorAlert .modal-body').text("Pump Done for 15 minutes. Close the Door!");
							$('#ShowErrorAlert .modal-body').text("Station 1 Door Open. Please Check");
							$('#ShowErrorAlert').modal({show: true});
						}
				});
			}
			
			CheckEvery15Minute();
			setInterval(CheckEvery15Minute, 15*60*1000);
		
			// Check Every 15 Minutes
			/*
			var myVar = setInterval(function(){
				//Check Error or Not
				
				//Show Alert
				document.getElementById("xyz").loop = true;
				document.getElementById('xyz').play();
				$('#ShowErrorAlert .modal-title').text("Alert");
				$('#ShowErrorAlert .modal-body').text("Please Door Close");
				$('#ShowErrorAlert').modal({show: true});
			}, 2000);

			//Cancel after 5 min
			//SetTimeout(myStopFunction, 5 * 60 * 1000);
			//setTimeout(myStopFunction, 10000);

			//Clears the interval
			function myStopFunction() {
				clearInterval(myVar);
				document.getElementById('xyz').pause();
				//$('#ShowErrorAlert').modal({show: false});
				//$('#ShowErrorAlert').modal('toggle');
			}*/
			
			function StopSound(){
				document.getElementById('xyz').pause();
				//clearInterval(myVar);
			}			
			
            $(document).ready(function ($) {
                $(".modal").keyup("input[name='user_pass']", function () {
                    updateErrorTop(checkSameTop());
                });

                $(".modal").keyup("input[name='user_pass_confirm']", function () {
                    updateErrorTop(checkSameTop());
                });

                jQuery('#ChangePasswordFormTop').on('submit', function (e) {
                    if (checkSameTop() == true) {
                        e.preventDefault();
                        jQuery.post('get_user_info.php',
                                $('#ChangePasswordFormTop').serialize(),
                                function (data, status, xhr) {
                                    $('.modal-error').text(data.error);
                                    if (data.condition == true) {
                                        $('#ChangePasswordUserTop .modal-body').empty();
                                        location.reload();
                                    }
                                }, "json");
                    } else
                    {
                        updateErrorTop(checkSameTop());
                        e.preventDefault();
                        $('#ChangePasswordUserTop').modal({show: true});
                    }
                });
            });

            function updateErrorTop(error) {
                if (error == true) {
                    //$(".error").show(500);
                    $('.modal-error').text("");
                } else {
                    //$(".error").hide(500);
                    $('.modal-error').text("Password Not Match!");
                }
            }
            ;

            function checkSameTop() {
                var passwordVal = $("input[name=user_pass]").val();
                var checkVal = $("input[name=user_pass_confirm]").val();
                if (passwordVal == checkVal) {
                    return true;
                }
                return false;
            }
            ;

            function doActionTop(action, id) {
                if (action == 'change') {
                    $('#ChangePasswordUserTop .modal-body').empty();
                    //$('#ChangePasswordUser .modal-title').text("Create User");
                    $('#ChangePasswordUserTop .modal-body').load('get_user_info.php?table_id=' + id + '&action=change', function () {
                        $('#ChangePasswordUserTop').modal({show: true});
                    });
                }
            }

            function startTime() {
                var today = new Date();
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                m = checkTime(m);
                s = checkTime(s);
                document.getElementById('ShowTime').innerHTML =
                        h + ":" + m + ":" + s;
                var t = setTimeout(startTime, 500);
            }
            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i
                }
                ;  // add zero in front of numbers < 10
                return i;
            }
        </script>