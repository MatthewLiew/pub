<?php define('RESTRICTED', true); ?>
<?php include 'header.php'; ?>
<?php
if ($_SESSION['login_user_role'] == '2') {
    ?>
    <div class="content-wrapper">
        <div class="container-fluid">
            Sorry, you are not allowed to access this page. Please browse Correct Page ! 
        </div>
    </div>
    <?php
    include 'footer.php';
} else {
    ?>
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
	<link href="css/lightbox.min.css" rel="stylesheet">

    <audio id="xyz" src="css/alert_tones.mp3" preload="auto"></audio>

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8 col-md-4 .col-xl-4">
                    <h1>CCTV View</h1>
                </div>
            </div>
            <div class="alert alert-success"  id="show_error" role="alert" style="display:none;"></div>
			
			<!-- non ie browser, using motion jpeg -->
			<div class="card mb-3" id="non-ie-browser" style="display:none;">
				<div class="frame" style="font-size: 0; margin-left: auto; margin-right: auto;">
					<a class="example-image-link" href="http://208.72.70.171/mjpg/video.mjpg?resolution=1280x960" data-lightbox="example-set" data-title="Cam 1">
						<img class="example-image" style="border: 2px solid white;" width="300px" height="250px" src="http://208.72.70.171/mjpg/video.mjpg?resolution=640x480" alt="">
					</a>
					<a class="example-image-link" href="http://camera1.mairie-brest.fr/mjpg/video.mjpg?resolution=1280x960" data-lightbox="example-set" data-title="Cam 2">
						<img class="example-image" style="border: 2px solid white;" width="300px" height="250px" src="http://camera1.mairie-brest.fr/mjpg/video.mjpg?resolution=640x480" alt="">
					</a>
				</div>
				<div class="frame" style="font-size: 0; margin-left: auto; margin-right: auto;">
					<a class="example-image-link" href="http://82.89.169.171/axis-cgi/mjpg/video.cgi?camera=&resolution==1280x960" data-lightbox="example-set" data-title="Cam 3">
						<img class="example-image" style="border: 2px solid white;" width="300px" height="250px" src="http://82.89.169.171/axis-cgi/mjpg/video.cgi?camera=&resolution=640x480" alt="">
					</a>
					<a class="example-image-link" href="http://85.93.105.195:8084/mjpg/video.mjpg?resolution=1280x960" data-lightbox="example-set" data-title="Cam 4">
						<img class="example-image" style="border: 2px solid white;" width="300px" height="250px" src="http://85.93.105.195:8084/mjpg/video.mjpg?resolution=640x480" alt="">
					</a>
				</div>
			</div>
			
			<!-- ie browser, using vlc player to load rtsp -->
			<div class="card mb-3" id="ie-browser" style="display:none;">
				<div>
					<div class="col-md-3"></div>
					<div class="col-md-6" style="font-size: 0; margin-left: auto; margin-right: auto;">
						<object
							classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921"
							codebase="http://download.videolan.org/pub/videolan/vlc/last/win32/axvlc.cab"
							id="vlc1"
							name="vlc1"
							class="vlcPlayer"
							width="300" 
							height="250"
							events="True">
							 
							<!-- ie -->
							<param name="Src" value="rtsp://admin:@192.168.0.202/" /> 
							<param name="ShowDisplay" value="True" />
							<param name="AutoLoop" value="True" />
							<param name="AutoPlay" value="True" />
						</object>
						<object
							classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921"
							codebase="http://download.videolan.org/pub/videolan/vlc/last/win32/axvlc.cab"
							id="vlc2"
							name="vlc2"
							class="vlcPlayer"
							width="300" 
							height="250"
							events="True">
							 
							<!-- ie -->
							<param name="Src" value="rtsp://admin:@192.168.0.202/" /> 
							<param name="ShowDisplay" value="True" />
							<param name="AutoLoop" value="True" />
							<param name="AutoPlay" value="True" />
						</object>
					</div>
					<div class="col-md-3"></div>
				</div>
				<div>
					<div class="col-md-3"></div>
					<div class="col-md-6" style="font-size: 0; margin-left: auto; margin-right: auto;">
						<object
							classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921"
							codebase="http://download.videolan.org/pub/videolan/vlc/last/win32/axvlc.cab"
							id="vlc3"
							name="vlc3"
							class="vlcPlayer"
							width="300" 
							height="250"
							events="True">
							 
							<!-- ie -->
							<param name="Src" value="rtsp://admin:@192.168.0.202/" /> 
							<param name="ShowDisplay" value="True" />
							<param name="AutoLoop" value="True" />
							<param name="AutoPlay" value="True" />
						</object>
						<object
							classid="clsid:9BE31822-FDAD-461B-AD51-BE1D1C159921"
							codebase="http://download.videolan.org/pub/videolan/vlc/last/win32/axvlc.cab"
							id="vlc4"
							name="vlc4"
							class="vlcPlayer"
							width="300" 
							height="250"
							events="True">
							 
							<!-- ie -->
							<param name="Src" value="rtsp://admin:@192.168.0.202/" /> 
							<param name="ShowDisplay" value="True" />
							<param name="AutoLoop" value="True" />
							<param name="AutoPlay" value="True" />
						</object>
					</div>
					<div class="col-md-3"></div>
				</div>
			</div>
			
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="WaterStationLabel">Station</label>
                            </div>
                            <div class="col-md-8" class="form-control" id="WaterStation">
                                <select class="form-control" data-size="5" id="water_station" name="water_station" >
                                    <option value="">Choose Water Station</option>
                                    <option value="Water Station 1">Water Station 1</option>
                                    <option value="Water Station 2">Water Station 2</option>
                                    <option value="Water Station 3">Water Station 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" id="show_cctv_div" style="display:none;">
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div id="show_cctv_info"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body" id="show_button_div" style="display:none;">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-3" style="text-align:center">
                        </div>
                        <div class="col-md-2" style="text-align:center">
                            <button class="btn btn-primary" onclick="Action_Door('open_gate')">Open Gate</button>
                        </div>
                        <div class="col-md-2" style="text-align:center">
                            <button class="btn btn-warning" onclick="Action_Door('close_gate')">Close Gate</button>
                        </div>
                         <div class="col-md-2" style="text-align:center">
                            <button class="btn btn-warning" onclick="Action_refill('water_refill')">Water Refill</button>
                        </div>
                        <div class="col-md-3" style="text-align:center">
                        </div>
                        <!-- <div>&nbsp;</div>-->
                    </div>
                </div>
            </div>
        </div>
		
        <script type="text/javascript">
            function Action_Door(action) {
                var station = $('#water_station').val();
                if (station == 'Water Station 1') {
                    $.post("check_error.php", {type: action},
                            function (result) {
                                //if the result is 1
                                // 0 = Not Success in Gate Action
                                // 1 =  Success in Gate Action						
                                if (result == 0) {
                                    // $('#ShowErrorAlert .modal-title').text("Alert");
                                    // $('#ShowErrorAlert .modal-body').text("Open Door for 30 minutes. Close the Door!");
                                    // $('#ShowErrorAlert').modal({show: true});
                                } else if (result == 1) {
                                    // $('#ShowErrorAlert .modal-title').text("Alert");
                                    // $('#ShowErrorAlert .modal-body').text("Pump Done for 15 minutes. Close the Door!");
                                    // $('#ShowErrorAlert').modal({show: true});
                                }
                            });
                } else if (station == 'Water Station 2') {
                    $.post("check_error.php", {type: action},
                            function (result) {
                                //if the result is 1
                                // 0 = Not Success in Gate Action
                                // 1 =  Success in Gate Action						
                                if (result == 0) {
                                    // $('#ShowErrorAlert .modal-title').text("Alert");
                                    // $('#ShowErrorAlert .modal-body').text("Open Door for 30 minutes. Close the Door!");
                                    // $('#ShowErrorAlert').modal({show: true});
                                } else if (result == 1) {
                                    // $('#ShowErrorAlert .modal-title').text("Alert");
                                    // $('#ShowErrorAlert .modal-body').text("Pump Done for 15 minutes. Close the Door!");
                                    // $('#ShowErrorAlert').modal({show: true});
                                }
                            });
                } else {
                    $.post("check_error.php", {type: action},
                            function (result) {
                                //if the result is 1
                                // 0 = Not Success in Gate Action
                                // 1 =  Success in Gate Action						
                                if (result == 0) {
                                    // $('#ShowErrorAlert .modal-title').text("Alert");
                                    // $('#ShowErrorAlert .modal-body').text("Open Door for 30 minutes. Close the Door!");
                                    // $('#ShowErrorAlert').modal({show: true});
                                } else if (result == 1) {
                                    // $('#ShowErrorAlert .modal-title').text("Alert");
                                    // $('#ShowErrorAlert .modal-body').text("Pump Done for 15 minutes. Close the Door!");
                                    // $('#ShowErrorAlert').modal({show: true});
                                }
                            });
                }
                //alert(station);
            }
            $(document).ready(function ($) {
                $("#water_station").change(function () {

                    var station = $('#water_station').val();
                    $('#show_cctv_div').show();
                    $('#show_button_div').show();
                    $('#show_cctv_info').html(station);

                });
            });

        </script>
		<script type="text/javascript">
			$(document).ready(function(){

				/* Detect Chrome */
				if($.browser.chrome){
					/* Do something for Chrome at this point */
					$("#non-ie-browser").show();
					/* Finally, if it is Chrome then jQuery thinks it's 
					   Safari so we have to tell it isn't */
					$.browser.safari = false;
				}

				/* Detect Safari */
				if($.browser.safari){
					/* Do something for Safari */
					$("#non-ie-browser").show();
				}
				
				/* Detect Firefox */
				if($.browser.firefox){
					/* Do something for Firefox */
					$("#non-ie-browser").show();
				}	
				
				/* Detect IE */
				if ($.browser.msie){
					/* Do something for IE */
					$("#ie-browser").show();
				}
			});
		</script>
		<script src="http://code.jquery.com/jquery-1.7.2.js"></script> 
		<script src="js/getBrowser.js"></script>
		<script src="js/lightbox.js"></script>
        <style>
            form label {font-weight:bold;}
        </style>

    <?php } ?>
    <?php include 'footer.php'; ?>