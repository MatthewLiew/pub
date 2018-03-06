<?php 
	
	//include('config/function.php');
	date_default_timezone_set('asia/singapore');	
	
	class mobile_model {
		
		public function __construct(){
			
		}
		
		public function insert_history($oid, $status, $value){
			$configs = include('config/config.php');
			
			$result = $configs->query("INSERT INTO vogomo_pump_water_history (oid, action, action_value, action_time) VALUES ($oid, '$status', $value, '".date('Y-m-d H:i:s')."')");
			
			return $result;
		}
		
		public function get_last_wl_before(){
			$configs = include('config/config.php');
			
			$result = $configs->query("SELECT * FROM vogomo_pump_water_history WHERE action='water_level_before' ORDER BY id DESC LIMIT 0,1");
			
			return $result;
		}
		
		public function get_last_wl_after(){
			$configs = include('config/config.php');
			
			$result = $configs->query("SELECT * FROM vogomo_pump_water_history WHERE action='water_level_after' ORDER BY id DESC LIMIT 0,1");
			
			return $result;
		}
		
		public function get_current_water_level(){
			$configs = include('config/config.php');
			
			$result = $configs->query("SELECT water_meter FROM vogomo_currentstatus");
			$currentwater = mysqli_fetch_assoc($result);
			
			return $currentwater['water_meter'];
		}
		
		public function get_current_water_level_percentage(){
			$configs = include('config/config.php');
			
			$result = $configs->query("SELECT water_level FROM vogomo_currentstatus");
			$currentwater = mysqli_fetch_assoc($result);
			
			return $currentwater['water_level'];
		}
		
		public function update_order_status($oid){
			$configs = include('config/config.php');
			$date = date("Y-m-d H:i:s");
			
			$result = $configs->query("UPDATE vogomo_orders SET status=1, complete_date='$date' WHERE order_id=$oid");
			
			return $result;
		}
		
		public function get_water_available($oid){
			$configs = include('config/config.php');
			// echo "here";
			$result = $configs->query("SELECT sum(action_value) as total FROM vogomo_pump_water_history WHERE action='water_pumped' AND oid=$oid");
			$availablewater = mysqli_fetch_assoc($result);
			// echo "<pre>"; print_r($availablewater); echo "</pre>";
			
			return $availablewater['total'];
			//return $result;
		}
		
		public function check_pump_finish($orderid){
			$configs = include('config/config.php');
			
			$water_pumped = $this->get_water_available($orderid);
			$water_order = $configs->query("SELECT qty FROM vogomo_orders WHERE order_id=$orderid");
			$water_order = mysqli_fetch_assoc($water_order);
			// echo $water_pumped."-".$water_order['qty'];
			if($water_pumped == $water_order['qty']){
				$result = "finished";
			}else{
				$result = "not";
			}
			
			return $result;
		}
		
		public function get_user_id($username){
			$configs = include('config/config.php');
			
			$result = $configs->query("SELECT ID FROM vogomo_users WHERE user_id='$username'");
			// print_r("SELECT ID FROM vogomo_users WHERE userid='$username'");
			return $result;
		}
		
		public function get_withdrawal_history($orderid){
			$configs = include('config/config.php');
		
			$query = "SELECT * FROM vogomo_pump_water_history WHERE action='water_pumped' AND oid=".$orderid;
			$result = $configs->query($query);
			//$results = mysqli_fetch_assoc($result);
			// print_r($result);
			return $result;		
		}
		
		public function get_order_history($status, $page, $customer){
			$configs = include('config/config.php');
			$offset = 20;
			$start = $page * $offset;
		
			if ($status == 'pending') {
				$status = 0;
			} else {
				$status = 1;
			}
			
			$query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE ";
			if($customer == 0){
				$query .= "orders.status='" . $status . "' ORDER BY create_date DESC LIMIT $start, $offset";
			}else{
				$query .= "orders.status='" . $status . "' AND orders.customer_id = '" . $customer . "' ORDER BY order_id DESC LIMIT $start, $offset";
			}
			// print_r($query);
			/*$query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE orders.status='" . $status . "' AND orders.customer_id = '" . $customer_id . "' ORDER BY order_id DESC";*/
    
			//order_id DESC
			$result = $configs->query($query);
			return $result;
		}
		
		public function change_door_status($door_status, $id){
			$configs = include('config/config.php');
			
			$query = "UPDATE vogomo_currentstatus SET door_status = '$door_status' WHERE id = $id";
			
			$result = $configs->query($query);
			return $result;
		}
	}			