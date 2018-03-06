<?php

$action = $_GET['action'];
date_default_timezone_set('asia/singapore');
include('config/function.php');

if ($action === 'check_login') {	
    $configs = include('config/config.php');
		
	$username = $_GET['username'];
	$password = $_GET['password'];
	$result = check_user_login($username, $password);
	if($result[0]==true){		
		$result = array(
			'login_id' => $_SESSION['login_id'],
			'user_role' => $_SESSION['login_user_role']
		);		
	}else {
		$result = "not_found";
	}
	echo json_encode($result);
	exit();
}else if($action === 'open_gate'){
    $configs = include('config/config.php');
    
    $userid = $_GET['userid'];
    $oid = $_GET['oid'];
    $date = date('Y-m-d H:i:s');
    
	$sql = $configs->query("SELECT id FROM vogomo_pump_water_history WHERE oid=$oid");
	// echo $sql->num_rows;
	if($sql->num_rows < 1){
		// $query = "INSERT INTO vogomo_pump_water_history (user_id, oid, og_status, og_time) VALUES ($userid, $oid, 1, '$date')";  
		$query = "INSERT INTO vogomo_pump_water_history (user_id, oid, og_status, og_time, cg_status, cg_time, wl_before, wl_after, pump_time) VALUES ($userid, $oid, 1, '$date', 0, '".date('Y-m-d')." 00:00:00', 0, 0, '".date('Y-m-d')." 00:00:00')";
		$result = $configs->query($query);
		$query = "SELECT id FROM vogomo_pump_water_history ORDER BY id DESC LIMIT 0,1";
		$result = $configs->query($query);
		$result = mysqli_fetch_assoc($result);
	}else {
		$result = mysqli_fetch_assoc($sql);
	}
    
    
    echo json_encode($result);
    exit();
}else if($action === 'pump_water'){
    $configs = include('config/config.php');
    
    $pumpid = $_GET['pumpid'];
    $date = date('Y-m-d H:i:s');
	$order_id = $_GET['order_id'];
	
	$query = "SELECT water_level FROM vogomo_currentstatus";
	$result = $configs->query($query);
	$currentwater = mysqli_fetch_assoc($result);
        
    $query = "UPDATE vogomo_pump_water_history SET wl_before=".$currentwater['water_level'].", wl_after=10, pump_time='$date' WHERE id=$pumpid";	
    $result = $configs->query($query);
	
	$query = "UPDATE vogomo_orders SET status=1 WHERE order_id=$order_id";
	$result = $configs->query($query);
}else if($action === 'close_gate'){
    $configs = include('config/config.php');
    
    $pumpid = $_GET['pumpid'];
    $date = date('Y-m-d H:i:s');
    
    $query = "UPDATE vogomo_pump_water_history SET cg_status=1, cg_time='$date' WHERE id=$pumpid";
    
    $result = $configs->query($query);
}else if($action === 'get_history'){
	$customer_id = $_GET['userid'];
	$user_role = $_GET['userrole'];
	$result = array();
	$pending_array = array();
	$completed_array = array();
	
	if($user_role == 1){
		$pending = get_order_list('pending');
	}else{
		$pending = get_order_list_by_customer($customer_id, 'pending');
	}
	foreach($pending as $row){
		array_push($pending_array, $row);
	}
	$result['pending'] = $pending_array;
	
	if($user_role == 1){
		$completed = get_order_list('completed');
	}else{
		$completed = get_order_list_by_customer($customer_id, 'completed');
	}
	foreach($completed as $row){
		array_push($completed_array, $row);
	}
	$result['completed'] = $completed_array;
	
	echo json_encode($result);
	exit();
}else if($action === 'check_water_level'){
	$configs = include('config/config.php');
	$qty = $_GET['qty'];
	
	$query = "SELECT water_level FROM vogomo_currentstatus";
	$result = $configs->query($query);
	$result = mysqli_fetch_assoc($result);
	if($result['water_level'] > $qty){
		$result = "fine";
		}else{
		$result = "water_insufficient";
	}
	
	echo json_encode($result);
	exit();
}else if($action === 'check_plc_status'){
	$configs = include('config/config.php');

	$type = $_GET['type'];
	
	$query = "SELECT * FROM vogomo_currentstatus";
	$result = $configs->query($query);
	$result = mysqli_fetch_assoc($result);
	if($result['door_status'] === 'DOOR AT CLOSE POSITION'){
		$door = 'close';
	}else{
		$door = 'open';
	}
	
	if($result['pump_status'] === 'SUPPLY PUMP RUN'){
		$pump = 'run';
	}else{
		$pump = 'stop';
	}
	
	$status = array(
		'door' => $door,
		'pump' => $pump
	);
	
	/*if($type === 'door_status'){
		// echo 'door';
		$query = "SELECT door_status FROM vogomo_currentstatus";
		$result = $configs->query($query);
		$result = mysqli_fetch_assoc($result);
		if($result['door_status'] === 'DOOR AT CLOSE POSITION'){
			$status = "close";
		}else{
			$status = "open";
		}
	}else if($type === 'water_pump'){
		// echo 'water';
		$query = "SELECT pump_status FROM vogomo_currentstatus";
		$result = $configs->query($query);
		$result = mysqli_fetch_assoc($result);
		if($result['pump_status'] === 'SUPPLY PUMP RUN'){
			$status = "run";
		}else{
			$status = "stop";
		}
	}*/
	
	echo json_encode($status);
	exit();
}
?>