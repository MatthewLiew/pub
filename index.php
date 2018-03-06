<?php define( 'RESTRICTED', true ); ?>
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
    }
    else{
?>
		<script>
			var customLabel = {
				service: {
				  label: 'S'
				},
				office: {
				  label: 'O'
				}
			};
			function initMap() {
			var map = new google.maps.Map(document.getElementById('map'), {
				center: new google.maps.LatLng(1.349246, 103.784330),
				zoom: 12
			});
			var infoWindow = new google.maps.InfoWindow;

				// Change this depending on the name of your PHP or XML file 
				downloadUrl('connect.php', function(data) {
				var xml = data.responseXML;
				var markers = xml.documentElement.getElementsByTagName('marker');
				
				var image1 = 'images/image1.png';
				var image2 = 'images/image2.png';
				var image3 = 'images/image3.png';

				Array.prototype.forEach.call(markers, function(markerElem) {
						var id = markerElem.getAttribute('id');
						var name = markerElem.getAttribute('name');
						var address = markerElem.getAttribute('address');
						var water_level = markerElem.getAttribute('water_level');
						var door_status = markerElem.getAttribute('door_status');
						var percentage = markerElem.getAttribute('percentage');
						var type = markerElem.getAttribute('type');
						var point = new google.maps.LatLng(
						parseFloat(markerElem.getAttribute('lat')),
						parseFloat(markerElem.getAttribute('lng')));

						var infowincontent = document.createElement('div');
						var strong = document.createElement('strong');
						strong.textContent = name
						infowincontent.appendChild(strong);
						infowincontent.appendChild(document.createElement('br'));

						var strong = document.createElement('strong');
						//strong.textContent = 'Water Level : ' + water_level + '(m3)' + percentage ;
						strong.textContent = 'Water Level : ';
						//strong.textContent = water_level + '(m<sup>3</sup>)';
						infowincontent.appendChild(strong);
						
						var w_level = document.createElement('span');
						w_level.textContent = water_level + '(m3)' + percentage ;
						infowincontent.appendChild(w_level);
						
						infowincontent.appendChild(document.createElement('br'));

						var strong = document.createElement('strong');
						//strong.textContent = 'Door Status : ' + door_status ;
						strong.textContent = 'Door Status : ' ;
						infowincontent.appendChild(strong);
						
						var w_level = document.createElement('span');
						w_level.textContent = door_status ;
						infowincontent.appendChild(w_level);
						infowincontent.appendChild(document.createElement('br'));
						
						var w_level = document.createElement('img');
						w_level.textContent = image1 ;
						infowincontent.appendChild(w_level);
						infowincontent.appendChild(document.createElement('br'));
						

						var text = document.createElement('text');
						text.textContent = address
						infowincontent.appendChild(text);

						//var icon = customLabel[type] || {};
						//var icon = customLabel[type] || {};
						var marker = new google.maps.Marker({
							map: map,
							position: point,
							//icon: image1,
							//label: icon.label
							label: type
						});
						marker.addListener('mouseover', function() {
							infoWindow.setContent(infowincontent);
							infoWindow.open(map, marker);
						});
					});
				});
			}

			function downloadUrl(url, callback) {
				var request = window.ActiveXObject ?
					new ActiveXObject('Microsoft.XMLHTTP') :
					new XMLHttpRequest;

				request.onreadystatechange = function() {
					if (request.readyState == 4) {
						request.onreadystatechange = doNothing;
						callback(request, request.status);
					}
				};

				request.open('GET', url, true);
				request.send(null);
			}

			function doNothing() {}
		</script>
		<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCkwNIwrz6muqQMBbi2zaI_WmAvZUNtTsU&callback=initMap"></script> -->
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBytQ0EAQX3yPH-DdxM9LD6WKrxmD00I1A&callback=initMap"></script>
		<style>
			/* Always set the map height explicitly to define the size of the div
			* element that contains the map. */
			#map {
				height: 65%;
			}
			/* Optional: Makes the sample page fill the window. */
			html, body {
				/* height: 100%; */
				margin: 0;
				padding: 0;
			}
		</style>
		
        <div class="content-wrapper">
			<div id="map" style="height:500px; padding-top:-20px; overflow: visible; margin-top: -12px;"></div>
            <div class="container-fluid">
                 <div class="row">
                    <div class="col-12">
                        <h1>Dashboard</h1>
                        <p></p>
                    </div>
                </div>
                <!-- Icon Cards-->				
                <div class="row">
                    <div class="col-xl-12 col-sm-12 mb-12">
						<div id="map"></div>
					</div>
				</div>
                 <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white bg-warning o-hidden h-100">
                            <a href="" style="color: #fff;">
                            <div class="card-body">
								<div class="mr-5">Water Status</div>
                                <div class="card-body-icon">
                                    <i class="fa fa-fw fa-tint"></i>
                                </div>
                                
                            </div>
                            </a>
                            <a class="card-footer text-white clearfix small z-1" href="">
                                <span class="float-left">
									<strong>
										<?php 
											$water_level = get_water_level();
											if($water_level[0]==1){
												echo "Station 1 " . $water_level[1] . " m<sup>3</sup>";
											}else{
												foreach($water_level[1] as $row){
													echo "Station " . $row['id'] . " - " . $row['water_level'] . " m<sup>3</sup><br/>";
												}
											}										
										?> 
										
									</strong>
								</span>
                                <!--
								<span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                </span>
								-->
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white bg-danger o-hidden h-100">
                            <a href="" style="color: #fff;">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fa fa-fw fa-power-off"></i>
                                </div>
                                <div class="mr-5">
									System Status
								</div>
                            </div>
                            </a>
                            <a class="card-footer text-white clearfix small z-1" href="">
                                <span class="float-left">
									<strong>
									<?php 
										//echo get_error_code_array(); 
										$check_result = check_current_error();
										echo $check_result[2];
									?></strong>
								</span>
                                <!--
								<span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                </span>
								-->
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white bg-success o-hidden h-100">
                            <a href="order_manage.php?status=pending" style="color: #fff;">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fa fa-fw fa-cart-plus"></i>
                                </div>
                                <div class="mr-5">
									Pending Orders
                                </div>
                            </div>
                             </a>
                            <a class="card-footer text-white clearfix small z-1" href="order_manage.php?status=pending">
                                <span class="float-left">
									<strong>
                                    <?php 
                                        echo get_order_count(0);
                                    ?>
									</strong>
								</span>
                                <!--
								<span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                </span>
								-->
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white bg-primary o-hidden h-100">
                            <a href="order_manage.php?status=completed" style="color: #fff;">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fa fa-fw fa-check-square"></i>
                                </div>
                                <div class="mr-5">
									Completed Orders
                                </div>
                            </div>
                            </a>
                            <a class="card-footer text-white clearfix small z-1" href="order_manage.php?status=completed">
                                <span class="float-left">
									<strong>
                                    <?php 
                                        echo get_order_count(1);
                                    ?>
									</strong>
								</span>
                                <!--
								<span class="float-right">
                                    <i class="fa fa-angle-right"></i>
                                </span>
								-->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    <?php } ?>
<?php include 'footer.php'; ?>