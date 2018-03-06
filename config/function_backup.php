<?php
/************ User Functions ***************/
function get_user_list() {
    $configs = include('config/config.php');
    
    $query = "SELECT * FROM " . $vogomo_users_table . " ORDER BY user_name ASC";
    $result = $configs->query($query);
    return $result;
}

function get_user_list_by_customer() {
    $configs = include('config/config.php');
    
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_role = '2' ORDER BY user_name ASC";
    $result = $configs->query($query);
    return $result;
}

function get_user_by_user_id($user_id){
    $configs = include('config/config.php');
    
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);
    if($result->num_rows===0){
        return false;
    }else{
        return $user = $result->fetch_array();
    }
}

function get_user_by_id($table_id){
    $configs = include('config/config.php');
    
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE ID ='" . $table_id . "'";
    $result = $configs->query($query);
    if($result->num_rows===0){
        return false;
    }else{
        return $user = $result->fetch_array();
    }
}

function check_user_login($user_id, $user_pass) {  
    $configs = include('config/config.php');
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    
    $result = $configs->query($query);    
    if($result->num_rows===0){
        return array(false, "User Name - " . $user_id . " Not Found! Please Type Correct ID!");
    }else{
        $user = $result->fetch_array();
        
        if(password_verify($user_pass, $user['user_pass'])) {
            $_SESSION['login_id'] = $user['ID'];
            $_SESSION['login_user_id'] = $user['user_id'];
            $_SESSION['login_user_role'] = $user['user_role'];
            return array(true, "Successful Login");
        } else {
            return array(false, "Please Type Correct Password!");
        }
    }
}

function check_user_login_session($user_id) {    
    $configs = include('config/config.php');
    
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);    
    if($result->num_rows===0){
        return false;
    }else{        
        $user = $result->fetch_array();
        return $user['user_id'];
    }
}

function save_user($user_id, $POST){
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $user_registered = date("Y-m-d H:i:s");
    
    // Check User ID already Exist Or Not
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);
    
    if($result->num_rows===0){       
        /*
        $options = [
            'cost' => 11,
        ];
        $hash = password_hash($passwordFromPost, PASSWORD_BCRYPT, $options);
         *          */
        $passwordFromPost = $POST['new_user_pass'];
        $user_pass = password_hash($passwordFromPost, PASSWORD_BCRYPT);
        
        $query = "INSERT INTO " . $vogomo_users_table . " (user_id, user_pass, user_name, user_phone, user_address, user_email, user_registered, user_role, user_status) VALUES ('" . $POST['user_id'] . "', '" . $user_pass . "', '" . $POST['user_name'] . "', '" . $POST['user_phone'] . "', '" . $POST['user_address'] . "', '" . $POST['user_email'] . "', '" . $user_registered . "', '" . $POST['user_role'] . "', '" . $POST['user_status'] . "') ";
        $result = $configs->query($query); 
        $_SESSION['message'] = "User Name - " . $user_id . " Save Successful!";
        return array(true, "User Name- " . $user_id . " Save Successful!");
    }else{  
        return array(false, "User Name - " . $user_id . " Already Exist!");
    }
}

function update_password($table_id, $user_id, $password){
    $configs = include('config/config.php');
    $user_pass = password_hash($password, PASSWORD_BCRYPT);    
    $query = "UPDATE " . $vogomo_users_table . " SET user_pass = '" . $user_pass . "' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    $result = $configs->query($query);
    $_SESSION['message'] = "User Name - " . $user_id . " Password Update Successful!";
    return array(true, "User Name - " . $user_id . " Password Update Successful!");
}

function update_user($table_id, $user_id, $POST) {    
    $configs = include('config/config.php');    
    
    if (isset($POST['user_role']) && isset($POST['user_status'])) {
        $query = "UPDATE " . $vogomo_users_table . " SET user_name = '" . $POST['user_name'] . "', user_phone = '" . $POST['user_phone'] . "', user_address = '" . $POST['user_address'] . "', user_email = '" . $POST['user_email'] . "', user_role = '" . $POST['user_role'] . "', user_status = '" . $POST['user_status'] . "' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    } else {
        $query = "UPDATE " . $vogomo_users_table . " SET user_name = '" . $POST['user_name'] . "', user_phone = '" . $POST['user_phone'] . "', user_address = '" . $POST['user_address'] . "', user_email = '" . $POST['user_email'] . "' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    }
    $result = $configs->query($query);
    $_SESSION['message'] = "User Name - " . $user_id . " Update Successful!";
    return array(true, "User Name - " . $user_id . " Update Successful!");
}

function delete_user($table_id, $user_id){
    $configs = include('config/config.php');
    
    $check_query = "SELECT * FROM " . $vogomo_orders_table . " WHERE customer_id='" . $table_id . "' ";
    $check_result = $configs->query($check_query);
    if($check_result->num_rows===0){ 
        $admin_query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "' AND user_role='1' ";
        $admin_result = $configs->query($admin_query);
        if($admin_result->num_rows===0){
            $query = "DELETE FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "' AND ID='" . $table_id . "'";
            $result = $configs->query($query);
            $_SESSION['message'] = "User Name - " . $user_id . " Already Deleted!";
            return array(true, "User Name - " . $user_id . " Already Deleted!");
        }else{
            return array(true, "Admin - " . $user_id . " Can't Delete!");
        }
    }else{
        return array(false, "User Name - " . $user_id . " Has Order History! Can't Delete!");
    }
}
/************ User Functions ***************/

/************ Order Functions ***************/
function get_order_list($status){
    $configs = include('config/config.php');
    if ($status == 'pending') {
        $status = 0;
    } else {
        $status = 1;
    }
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE orders.status='" . $status . "' ORDER BY order_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_order_count($status){
    $configs = include('config/config.php');
    $query = "SELECT count(*) as count FROM " .  $vogomo_orders_table . " WHERE status='" . $status . "'";
    $result = $configs->query($query);
    $count = $result->fetch_array();    
    return $count['count'];
}

function get_order_report($start_date, $end_date){
    $configs = include('config/config.php');
   
    //$query = "SELECT *FROM " .  $vogomo_orders_table . " WHERE (date BETWEEN '" . $start_date . "' AND '" . $end_date . "') Order By date DESC";
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE orders.status='1' AND (orders.date BETWEEN '" . $start_date . "' AND '" . $end_date . "') ORDER BY orders.order_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_order_report_by_all_filter($start_date, $end_date, $customer_id, $status){
    $configs = include('config/config.php');
	$where_clause = "";	
	if($customer_id!="")
		$where_clause .= " AND orders.customer_id='" . $customer_id . "'";
	if($status!="")
		$where_clause .= " AND orders.status='" . $status . "'";
   
    //$query = "SELECT *FROM " .  $vogomo_orders_table . " WHERE (date BETWEEN '" . $start_date . "' AND '" . $end_date . "') Order By date DESC";
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE (orders.date BETWEEN '" . $start_date . "' AND '" . $end_date . "') " . $where_clause . " ORDER BY orders.order_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_order_list_by_customer($customer_id, $status){
    $configs = include('config/config.php');
    if ($status == 'pending') {
        $status = 0;
    } else {
        $status = 1;
    }
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE orders.status='" . $status . "' AND orders.customer_id = '" . $customer_id . "' ORDER BY order_id DESC";	
    $result = $configs->query($query);
    return $result;
}

function get_order_by_id($order_id){
    $configs = include('config/config.php');
    
    $query = "SELECT * FROM " . $vogomo_orders_table . " WHERE order_id ='" . $order_id . "'";
    $result = $configs->query($query);    
    if($result->num_rows===0){
        return false;
    }else{
        return $user = $result->fetch_array();
    }
}

function save_order($login_id, $POST){
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $user_registered = date("Y-m-d H:i:s");    
    
    // Check User ID already Exist Or Not
    $query = "SELECT * FROM " . $vogomo_orders_table . " WHERE invoice_number ='" . $POST['order_number'] . "'";
    $result = $configs->query($query);
    
    if($result->num_rows===0){        
        //$query = "INSERT INTO " . $vogomo_orders_table . " (invoice_number, customer_id, qty, date, status, remark, created_by) VALUES ('" . $POST['order_number'] . "', '" . $POST['customer_id'] . "', '" . $POST['order_qty'] . "', '" . $POST['order_date'] . "', '0', '" . $POST['order_remark'] . "',  '" . $_SESSION['login_id'] . "', ) ";
		if($POST['customer_id']=="" || $POST['customer_id']=="0"){
			return array(false, "Please Choose Customer!");
		}else{
			$query = "INSERT INTO " . $vogomo_orders_table . " (invoice_number, customer_id, qty, date, status, remark, created_by) VALUES ('" . $POST['order_number'] . "', '" . $POST['customer_id'] . "', '" . $POST['order_qty'] . "', '" . $POST['order_date'] . "', '0', '" . $POST['order_remark'] . "',  '" . $_SESSION['login_id'] . "' ) ";
			//echo $query; exit;
			$result = $configs->query($query); 
			$_SESSION['message'] = "Invoice Number - " . $POST['order_number'] . " Save Successful!";
			return array(true, "Invoice Number - " . $POST['order_number']. " Save Successful!");
		}
		
    }else{  
        return array(false, "Invoice Number - " . $POST['order_number'] . " Already Exist!");
    }
}

function update_order($order_id, $order_number, $POST){
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    
    $query = "UPDATE " . $vogomo_orders_table . " SET customer_id = '" . $POST['customer_id'] . "', qty = '" . $POST['order_qty'] . "', date = '" . $POST['order_date'] . "', status = '" . $POST['order_status'] . "', remark = '" . $POST['order_remark'] . "' WHERE order_id='" . $order_id . "' AND invoice_number='" . $order_number . "'";
    $result = $configs->query($query);
    return array(true, "Order Number - " . $order_number . " Update Successful!");
}

function delete_order($order_id, $order_number){
    $configs = include('config/config.php');
    
    $query = "DELETE FROM " . $vogomo_orders_table . " WHERE order_id='" . $order_id . "'";
    $result = $configs->query($query);
    $_SESSION['message'] = "Order Number - " . $order_number . " Already Deleted!";
    return array(true, "Order Number - " . $order_number . " Already Deleted!");
    //exit;
}
/************ Order Functions ***************/
?> 