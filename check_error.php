<?php
	include 'config/function.php';
	date_default_timezone_set('asia/singapore');
	$current_date = date("Y-m-d H:i:s");
	session_start();
	$type = $_POST['type'];
	if($type == 'alert'){
            if($_SESSION['login_user_role']=='1'){
                $result = check_gate_status();
		//print_r($result);
		foreach($result as $row){
			$action = $row['action'];
			$action_time =  $row['action_time'];
		}
		//echo $current_date . "<br/>";
		//echo $action_time . "<br/>";
		
		// $current_date = '2018-02-06 16:25:36';
		// $action_time = '2018-02-06 16:25:36';
		
		$dateTimestamp1 = strtotime($current_date);
		$dateTimestamp2 = strtotime($action_time);
		
		//Calculate the difference.
		$difference = $dateTimestamp1 - $dateTimestamp2;	
		if($difference >= 900){
			// 1 = "Open Door for 30 minutes. Close the Door"
			// 2 = "Pump Done for 15 minutes. Close the Door!";
			// 3 = "Pump Done for 15 minutes. Close the Door!";
			if($action=="open_gate_status"){
				if($difference >= 1800){
					echo 1;
				}
			}elseif($action=="water_level_after"){
				echo 2;
			}elseif($action=="water_pumped"){
				echo 3;
			}
			/*elseif($action=="close_gate_status"){
			}*/
		}
            }else{
                echo 4;
            }
	}else{	
		require("phpMQTT.php");
		$server = "ec2-54-169-232-207.ap-southeast-1.compute.amazonaws.com"; 
		$port = 1883;   
		$username = "hydrax";  
		$password = "mqtt123";  
		$client_id = "ClientID".rand();
		
		$topic = "";
		if($type === 'pump_water'){
			$water_qty = $_GET['qty'];
			$topic = '{"action"="'.$type.'", "value"='.$water_qty.'}';
		}
		else{
			$topic = '{"action"="'.$type.'"}';
		}
		
		if($topic !== ""){
			$mqtt = new bluerhinos\phpMQTT($server, $port, $client_id);
			if ($mqtt->connect(true, NULL, $username, $password)) {		
				$mqtt->publish("/watergate", $topic, 0);
				$mqtt->close();
				
				/* Add Water Pump History */
				save_open_close_gate($type);
				/* Add Water Pump History */
				
				echo 1;
				exit();
			} else {
				echo 0;
				exit();
			}
		}
	}	
?>