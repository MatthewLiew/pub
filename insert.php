<?php

$action = $_GET['action'];
date_default_timezone_set('asia/singapore');
include('config/function.php');
include('mobile_model.php');
$model = new mobile_model();

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
}else if($action === 'get_withdrawal_details'){	
	$order_id = $_GET['oid'];	
	$result = $model->get_withdrawal_history($order_id);
	
	$results = array();
	foreach($result as $key){
		array_push($results, $key);
	}
	
	echo json_encode($results);
	exit();
		
}else if($action === 'open_gate'){
    $configs = include('config/config.php');
    
	//$model->change_door_status('DOOR AT OPEN POSITION', 1);
	
    $oid = $_GET['oid'];   
    $result = $model->insert_history($oid, 'open_gate_status', 1);
    
    echo json_encode($result);
    exit();
	
}else if($action === 'start_pump'){
    $configs = include('config/config.php');
    
	$order_id = $_GET['order_id'];
	$qty = $_GET['qty'];
	$currentwater = $model->get_current_water_level();	
	$model->insert_history($order_id, 'water_level_before', $currentwater); 
	// $model->insert_history($order_id, 'water_pumped', $qty); 	
	
	// temporary
	/*$query = $configs->query("SELECT water_meter FROM vogomo_currentstatus WHERE id=1");
	$query = mysqli_fetch_assoc($query);
	$final = $query['water_meter'] + $qty;
	$query = $configs->query("UPDATE vogomo_currentstatus SET water_meter=$final WHERE id=1");*/
	// temporary

/*}else if($action === 'stop_pump'){	
	$configs = include('config/config.php');
	
	$order_id = $_GET['oid'];
	$currentwater = $model->get_current_water_level();
	
	$model->insert_history($order_id, 'water_level_after', $currentwater);
	$result = $model->check_pump_finish($order_id);
	if($result === 'finished'){
		$model->update_order_status($order_id);
		$result = 1;
	}else{
		$result = 0;
	}
	
	echo json_encode($result);
	exit();*/
}else if($action === 'close_gate'){
    $configs = include('config/config.php');
	
	//$model->change_door_status('DOOR AT CLOSE POSITION', 1);
    
    $order_id = $_GET['oid'];
	$result = $model->insert_history($order_id, 'close_gate_status', 1);
	
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
		$available = $model->get_water_available($row['order_id']);		
		// print_r($model->get_withdrawal_history($row['order_id']));
		array_push($pending_array, array('order_info' => $row, 'available' => $available));
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
}else if($action === 'history'){
	$customer_id = $_GET['userid'];
	$user_role = $_GET['userrole'];
	$type = $_GET['type'];
	$page = $_GET['page'];
	
	$result = array();
	// $pending_array = array();
	// $completed_array = array();
	
	/*if($user_role == 1){
		$pending = get_order_list($type);
	}else{
		$pending = get_order_list_by_customer($customer_id, $type);
	}*/
	
	if($user_role == 1){
		$customer = 0;
	}else{
		$customer = $customer_id;
	}
	
	$query = $model->get_order_history($type, $page, $customer);
	foreach($query as $row){
		$available = $model->get_water_available($row['order_id']);		
		// print_r($model->get_withdrawal_history($row['order_id']));
		array_push($result, array('order_info' => $row, 'available' => $available, 'offset' => 20));
	}
	/*$result['pending'] = $pending_array;
	
	if($user_role == 1){
		$completed = get_order_list('completed');
	}else{
		$completed = get_order_list_by_customer($customer_id, 'completed');
	}
	foreach($completed as $row){
		array_push($completed_array, $row);
	}
	$result['completed'] = $completed_array;*/
	
	echo json_encode($result);
	exit();
	
}else if($action === 'check_water_level'){
	$configs = include('config/config.php');
	
	$qty = $_GET['qty'];	
	$currentwater = $model->get_current_water_level_percentage();
	$water_level = $currentwater/100 * 1500;	
	
	if($water_level > $qty){
		$result = "fine";
	}else{
		$result = "water_insufficient";
	}
	
	echo json_encode($result);
	exit();
	
}else if($action === 'check_plc_status'){
	$configs = include('config/config.php');

	$type = $_GET['type'];
	
	$query = "SELECT door_status, pump_status FROM vogomo_currentstatus";
	$result = $configs->query($query);
	$result = mysqli_fetch_assoc($result);
	
	if($type === 'door'){	
		$status = 'open';
		if($result['door_status'] === 'DOOR AT CLOSE POSITION'){
			$status = 'close';
		}		
	}else{
		$status = 'stop';
		if($result['pump_status'] === 'DIS. PUMP RUN SUPPLY P. STOP' || $result['pump_status'] === 'SUPPLY & DISCHARGE PUMP RUN'){
			$status = 'run';
		}
	}
	
	echo json_encode($status);
	exit();
	
/*}else if($action === 'check_open_gate_able'){
	$configs = include('config/config.php');
	
	$userid = $_GET['userid'];
	
	$query = "SELECT * FROM vogomo_pump_water_history his INNER JOIN vogomo_orders ord ON his.oid = ord.order_id WHERE ord.customer_id=$userid AND his.action='water_level_after' ORDER BY his.id DESC LIMIT 0,1";

	$result = $configs->query($query);
	$result = mysqli_fetch_assoc($result);
	
	$time = strtotime(date('Y-m-d H:i:s')) - strtotime($result['action_time']);
	if($time < 3600){
		$status = "yes";
	}else{
		$status = "no";
	}
	
	echo json_encode($status);
	exit();
	*/
}else if($action === 'check_pump_button'){
	$configs = include('config/config.php');
	
	$order_id = $_GET['oid'];
	
	$query = "SELECT * FROM vogomo_pump_water_history WHERE oid=$order_id AND action='water_level_before' ORDER BY id DESC LIMIT 0,1";	
	$result = $configs->query($query);	
	
	if($result->num_rows > 0){
		$status = "no";
	}else{
		$status = "yes";
	}
	
	echo json_encode($status);
	exit();
	
}else if($action === 'pump_stop'){
	$configs = include('config/config.php');
	
	$before = $model->get_last_wl_before();
		
	if($before->num_rows == 1){
		// $after = $model->get_last_wl_after();
		
		$before = mysqli_fetch_assoc($before);
		// $after = mysqli_fetch_assoc($after);		
		
		// if($before['oid'] != $after['oid']){
			$currentwater = $model->get_current_water_level();
				
			$model->insert_history($before['oid'], 'water_level_after', $currentwater);
			// $model->update_order_status($before['oid']);
			$after = $model->get_last_wl_after();
			$after = mysqli_fetch_assoc($after);
			$total_pumped = $after['action_value'] - $before['action_value'];
			
			$model->insert_history($before['oid'], 'water_pumped', $total_pumped);
			
			$result = $model->check_pump_finish($before['oid']);
			if($result === 'finished'){
				$model->update_order_status($before['oid']);				
			}
		// }
	}
	
	echo json_encode($result);
	exit();
}else if($action === 'start_mqtt'){
	require("phpMQTT.php");
	
	$server = "ec2-54-169-232-207.ap-southeast-1.compute.amazonaws.com"; 
	$port = 1883;   
	$username = "hydrax";  
	$password = "mqtt123";  
	$client_id = "ClientID".rand(); 
	
	$type = $_GET['type'];
	$topic = "";
	if($type === 'pump_water'){
		$water_qty = $_GET['qty'];
		$topic = '{"action"="'.$type.'", "value"='.$water_qty.'}';
		}else{
		$topic = '{"action"="'.$type.'"}';
	}
	
	if($topic !== ""){
		$mqtt = new bluerhinos\phpMQTT($server, $port, $client_id);
		if ($mqtt->connect(true, NULL, $username, $password)) {		
			$mqtt->publish("/watergate", $topic, 0);
			$mqtt->close();
			echo json_encode("success");
			exit();
		} else {
			echo json_encode("Connection Time Out");
			exit();
		}
	}
	
}else if($action === 'change_password'){
	$username = $_GET['username'];
	$userid = $_GET['userid'];
	$password = $_GET['password'];
	
	$result = update_password($userid, $username, $password);
	// print_r($result);
	
	echo json_encode($result);
	exit();

}else if($action === 'reset_password'){
	$configs = include('config/config.php');
	
	$username = $_GET['username'];
	
	$userid = $model->get_user_id($username);
	
	if($userid->num_rows == 1){
		$temp = mysqli_fetch_assoc($userid);		
		$result = reset_password($temp['ID'], $username);		
	}else{
		$result = "user_not_found";
	}
	
	echo json_encode($result);
	exit();
}else if($action === 'make_appointment'){
	$configs = include('config/config.php');
	
	$booking = $_GET['booking_date']." ".$_GET['booking_timeslot'].":00:00";
	$expired = $_GET['booking_date']." ".$_GET['expired_timeslot'].":00:00";
	$order_id = $_GET['oid'];
	
	$result = $configs->query("UPDATE vogomo_orders SET booking_datetime='$booking', expired_datetime='$expired' WHERE order_id=$order_id");
	
	echo json_encode($result);
	exit();
}else if($action === 'get_details'){
	$configs = include('config/config.php');
	
	$order_id = $_GET['order_id'];
	
	$query = $configs->query("SELECT * FROM vogomo_orders WHERE order_id=$order_id");	
	$result = mysqli_fetch_assoc($query);
	// echo "<pre>"; print_r($result); echo "</pre>";
	$available = $model->get_water_available($order_id);
	$last_open_gate = $configs->query("SELECT * FROM vogomo_pump_water_history WHERE oid=$order_id AND action='open_gate_status' ORDER BY id DESC LIMIT 0,1");
	// echo "<pre>"; print_r($last_open_gate); echo "</pre>";
	$last = "";
	if($last_open_gate->num_rows > 0){
		$temp = mysqli_fetch_assoc($last_open_gate);
		$last = $temp['action_time'];
	}
	
	$history = array();
	
	if($result['status'] == 1){
		$query = $model->get_withdrawal_history($order_id);
		foreach($query as $key){
			array_push($history, $key);
		}
	}
	
	$final_result = array(
		'inv_num' => $result['invoice_number'],
		'qty' => $result['qty'],
		'available' => $available,
		'date' => $result['create_date'],
		'completed_date' => $result['complete_date'],
		'booking_date' => $result['booking_datetime'],
		'expired_date' => $result['expired_datetime'],
		'last_open_gate' => $last,
		'history' => $history
	);
	
	echo json_encode($final_result);
	exit();
}else if($action === 'read_water'){
	$configs = include('config/config.php');
    
	$order_id = $_GET['order_id'];
	
	$currentwater = $model->get_current_water_level();	
	$model->insert_history($order_id, 'water_level_before', $currentwater); 
	// $model->insert_history($order_id, 'water_pumped', $qty); 	
	
	// temporary
	$qty = 2;
	$query = $configs->query("SELECT water_meter FROM vogomo_currentstatus WHERE id=1");
	$query = mysqli_fetch_assoc($query);
	$final = $query['water_meter'] + $qty;
	$query = $configs->query("UPDATE vogomo_currentstatus SET water_meter=$final WHERE id=1");
}else if($action === 'check_timeslot'){	
	$configs = include('config/config.php');
	$date = $_GET['date'];
	
	$query = "SELECT * FROM vogomo_orders WHERE booking_datetime BETWEEN '$date 09:00:00' AND '$date 18:00:00'";
	// print_r($query);
	$result = $configs->query($query);
	// echo "<pre>"; print_r($result); echo "</pre>";
	$results = array();
	foreach($result as $key){
		array_push($results, $key);
	}
	
	echo json_encode($results);
	exit();
}else if($action === 'cancel_booking'){
	$configs = include('config/config.php');
	$order_id = $_GET['order_id'];
	
	$query = "UPDATE vogomo_orders SET booking_datetime='2018-01-01 00:00:00', expired_datetime='2018-01-01 00:00:00' WHERE order_id=$order_id";
	 // print_r($query);
	$result = $configs->query($query);
}
?>