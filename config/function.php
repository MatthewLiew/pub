<?php

/* * ********** Check Gate Status ************** */

function save_booking_time($booking_date, $booking_time, $order_id, $invoice_number, $customer_id) {
    $configs = include('config/config.php');
    $query = "UPDATE " . $vogomo_orders_table . " SET booking_datetime = '" . $booking_date . " " . $booking_time . ":00:00' WHERE order_id = '" . $order_id . "' AND customer_id = '" . $customer_id . "'";
    $result = $configs->query($query);
    if($result){
        save_system_log($order_id, "Booking TimeSlot Save Successful", "Booking TimeSlot");
        return array(true, "Booking TimeSlot Save Successful for Order - " . $invoice_number, $order_id);
    }else{
        return array(false, "Booking TimeSlot Can't Save for Order - " . $invoice_number, $order_id);
    }
}

function check_booking_expired(){
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $current_date_time = date("Y-m-d H:i:s");
    $query = "SELECT * FROM " . $vogomo_orders_table . " WHERE booking_datetime < '" . $current_date_time . "' AND booking_datetime != 'NULL' ";
    //echo $query;
    $result = $configs->query($query);
    foreach ($result as $row) {
        //expired_datetime
        $update =  "UPDATE " . $vogomo_orders_table . " SET booking_datetime = NULL, expired_datetime = '" . $row['booking_datetime'] . "' WHERE order_id";
        save_system_log($row['order_id'], 'Booking Expired', 'Booking Expired');
        $result = $configs->query($update);
        //echo $update;
        
    }
}

function check_booking_or_not($order_id){
    $configs = include('config/config.php');
    $query = "SELECT booking_datetime, expired_datetime FROM " . $vogomo_orders_table . " WHERE order_id = '" . $order_id . "' ";
    $result = $configs->query($query);
    $count = $result->fetch_array();
    if ($count['booking_datetime'] == NULL){
        if($count['expired_datetime'] == NULL){
            return array(false, "Booking Now");
        }
        return array(false, "Booking Expired");
    }   
    else{
        return array(true, $count['booking_datetime']);
        //return $count['booking_datetime'];
    }
}

function check_gate_status() {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $action_date = date("Y-m-d H:i:s");
    $query = "SELECT * FROM  " . $vogomo_pump_water_history_table . "  ORDER BY id DESC LIMIT 1";
    $result = $configs->query($query);
    return $result;
}

function save_open_close_gate($type) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $action_date = date("Y-m-d H:i:s");
    if ($type != "") {
        $type = $type . '_status';
        $query = "INSERT INTO  " . $vogomo_pump_water_history_table . " (oid, action, action_value, action_time) VALUES ('0', '$type', '3', '$action_date')";
        $result = $configs->query($query);
        if ($result) {
            $get_id_query = "SELECT id FROM " . $vogomo_pump_water_history_table . " ORDER BY ID DESC LIMIT 1";
            $get_customer_id_result = $configs->query($get_id_query);
            foreach ($get_customer_id_result as $row) {
                $table_id = $row['id'];
            }
            if ($type == 'open_gate_status')
                $action_text = "Gate Open - Manual";
            else
                $action_text = "Gate Close - Manual";
            save_system_log($table_id, $action_text, $type);
        }
        return $result;
    }
}

/* * ********** Check Gate Status ************** */

function get_water_pumped_history($order_id){
    $configs = include('config/config.php');
    $query = "SELECT * FROM  " . $vogomo_pump_water_history_table . "  WHERE oid='" . $order_id . "' AND action='water_pumped'";
    //echo $query;
    $result = $configs->query($query);
    return $result;
}

function get_water_pumped($order_id) {
    $configs = include('config/config.php');
    $query = "SELECT SUM(action_value) as count FROM  " . $vogomo_pump_water_history_table . "  WHERE oid='" . $order_id . "' AND action='water_pumped'";
    //echo $query;
    $result = $configs->query($query);
    $count = $result->fetch_array();
    if ($count['count'] == NULL)
        return 0;
    else
        return $count['count'];
}

/* * ********** Transaction/System Functions ************** */

function save_system_log($foreign_id, $action_text, $action) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $action_date = date("Y-m-d H:i:s");
    $query = "INSERT INTO " . $vogomo_system_log_table . " (foreign_id, action_text, action, action_by, action_date) VALUES ('" . $foreign_id . "', '" . $action_text . "', '" . $action . "', '" . $_SESSION['login_id'] . "', '" . $action_date . "')";
    //echo $query;
    $result = $configs->query($query);
    return $result;
}

function get_system_list() {
    $configs = include('config/config.php');
    $query = "SELECT * FROM " . $vogomo_system_log_table . " as history LEFT JOIN " . $vogomo_users_table . " as users ON users.ID = history.action_by ORDER BY history.system_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_system_list_by_date($start_date, $end_date) {
    $configs = include('config/config.php');
    $query = "SELECT * FROM " . $vogomo_system_log_table . " as history LEFT JOIN " . $vogomo_users_table . " as users ON users.ID = history.action_by WHERE (history.action_date BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59') ORDER BY history.system_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_transaction_list() {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_pump_water_history_table . " as history LEFT JOIN " . $vogomo_orders_table . " as orders ON orders.order_id = history.oid LEFT JOIN " . $vogomo_users_table . " as users ON users.ID = orders.customer_id WHERE history.oid!='0' AND action!='water_level_before' AND action != 'water_level_after' ORDER BY history.id DESC";

    $result = $configs->query($query);
    return $result;
}

function get_transaction_list_by_date($start_date, $end_date) {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_pump_water_history_table . " as history LEFT JOIN " . $vogomo_orders_table . " as orders ON orders.order_id = history.oid LEFT JOIN " . $vogomo_users_table . " as users ON users.ID = orders.customer_id WHERE history.oid!='0' AND (history.action_time BETWEEN '" . $start_date . "' AND '" . $end_date . "') ORDER BY history.id DESC";

    $result = $configs->query($query);
    return $result;
}

/* * ********** Transaction/System Functions ************** */
/* * ********** Error Functions ************** */

function get_error_list() {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_error_log_table . " ORDER BY id DESC";

    //$query = "SELECT * FROM " . $vogomo_pump_water_history_table . " as history LEFT JOIN " . $vogomo_orders_table . " as orders ON orders.order_id = history.oid LEFT JOIN " . $vogomo_users_table . " as users ON users.ID = orders.customer_id ORDER BY history.id DESC";

    $result = $configs->query($query);
    return $result;
}

function get_error_list_by_date($start_date, $end_date) {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_error_log_table . " WHERE (history.action_time BETWEEN '" . $start_date . "' AND '" . $end_date . "')  ORDER BY id DESC";

    $result = $configs->query($query);
    return $result;
}

/* * ********** Error Functions ************** */
/* * ********** User Functions ************** */

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

function get_user_by_user_id($user_id) {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);
    if ($result->num_rows === 0) {
        return false;
    } else {
        return $user = $result->fetch_array();
    }
}

function get_user_by_id($table_id) {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE ID ='" . $table_id . "'";
    $result = $configs->query($query);
    if ($result->num_rows === 0) {
        return false;
    } else {
        return $user = $result->fetch_array();
    }
}

function check_user_login($user_id, $user_pass) {
    $configs = include('config/config.php');
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";

    $result = $configs->query($query);
    if ($result->num_rows === 0) {
        return array(false, "User Name - " . $user_id . " Not Found! Please Type Correct ID!");
    } else {
        $user = $result->fetch_array();

        if (password_verify($user_pass, $user['user_pass'])) {
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
    if ($result->num_rows === 0) {
        return false;
    } else {
        $user = $result->fetch_array();
        return $user['user_id'];
    }
}

function save_user_order($user_id, $POST) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $user_registered = date("Y-m-d H:i:s");

    // Check User ID already Exist Or Not
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);

    if ($result->num_rows === 0) {
        $passwordFromPost = $POST['new_user_pass'];
        $user_pass = password_hash($passwordFromPost, PASSWORD_BCRYPT);

        /*         * ******** Save Order *********** */
        // Check Invoice Number already Exist Or Not
        $query = "SELECT * FROM " . $vogomo_orders_table . " WHERE invoice_number ='" . $POST['order_number'] . "'";
        $result = $configs->query($query);

        if ($result->num_rows === 0) {
            $user_query = "INSERT INTO " . $vogomo_users_table . " (user_id, user_pass, user_name, user_phone, user_address, user_email, user_registered, user_role, user_status, check_first_time) VALUES ('" . $POST['user_id'] . "', '" . $user_pass . "', '" . $POST['user_name'] . "', '" . $POST['user_phone'] . "', ' ', '" . $POST['user_email'] . "', '" . $user_registered . "', '" . $POST['user_role'] . "', '" . $POST['user_status'] . "', '0') ";
            $result = $configs->query($user_query);
            if ($result) {
                /*                 * *** Sent SMS To User Phone ********* */
                sent_sms_to_user(1, $user_id, $POST['new_user_pass']);
                /*                 * *** Sent SMS To User Phone ********* */

                /*                 * ***** Save System Log ******** */
                $get_customer_id = "SELECT ID, user_name FROM " . $vogomo_users_table . " ORDER BY ID DESC LIMIT 1";
                $get_customer_id_result = $configs->query($get_customer_id);
                foreach ($get_customer_id_result as $row) {
                    $table_id = $row['ID'];
                    $customer_id = $row['user_id'];
                    $customer_name = $row['user_name'];
                }
                $action_text = "Add New User : " . $POST['user_id'] . " - " . $POST['user_name'];
                $action = "New User";
                save_system_log($table_id, $action_text, $action);
                /*                 * ***** Save System Log ******** */

                $order_query = "INSERT INTO " . $vogomo_orders_table . " (invoice_number, customer_id, qty, create_date, complete_date, status, remark, action_by) VALUES ('" . $POST['order_number'] . "', '" . $table_id . "', '" . $POST['order_qty'] . "', '" . $POST['order_date'] . "', NULL, '0', '" . $POST['order_remark'] . "',  '" . $_SESSION['login_id'] . "' ) ";
                //echo $order_query;
                $result = $configs->query($order_query);
                /*                 * ***** Save System Log ******** */
                $get_order_id = "SELECT order_id, invoice_number FROM " . $vogomo_orders_table . " ORDER BY order_id DESC LIMIT 1";
                $get_order_id_result = $configs->query($get_order_id);
                foreach ($get_order_id_result as $row) {
                    $order_id = $row['order_id'];
                }
                $action_text = "Add New Order : " . $POST['order_number'] . " For Customer " . $customer_id;
                $action = "New Order";
                save_system_log($order_id, $action_text, $action);
                /*                 * ***** Save System Log ******** */
                return array(true, "Invoice Number - " . $POST['order_number'] . " Save Successful!");
            } else {
                return array(true, "Invoice Number - " . $POST['order_number'] . " Can't Save!");
            }
        } else {
            return array(false, "Invoice Number - " . $POST['order_number'] . " Already Exist!");
        }
        /*         * ******** Save Order *********** */
    } else {
        return array(false, "User Name - " . $user_id . " Already Exist! Please choose in Customer ...");
    }
}

function check_first_time($user_id){
    $configs = include('config/config.php');
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);
    foreach ($result as $row){
        //echo $row['check_first_time'] . " - ";
        if($row['check_first_time'] == 0)
            return 0;
        else
            return 1;
    }
    /*
    if ($result->num_rows === 0) {
        return 0;
    } else {
        return 1;
    }*/
}

function check_existing_user($user_id) {
    $configs = include('config/config.php');
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);
    if ($result->num_rows === 0) {
        return 0;
    } else {
        return 1;
    }
}

function save_user($user_id, $POST) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $user_registered = date("Y-m-d H:i:s");

    // Check User ID already Exist Or Not
    $query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "'";
    $result = $configs->query($query);

    if ($result->num_rows === 0) {
        $passwordFromPost = $POST['new_user_pass'];
        $user_pass = password_hash($passwordFromPost, PASSWORD_BCRYPT);

        $query = "INSERT INTO " . $vogomo_users_table . " (user_id, user_pass, user_name, user_phone, user_address, user_email, user_registered, user_role, user_status, check_first_time) VALUES ('" . $POST['user_id'] . "', '" . $user_pass . "', '" . $POST['user_name'] . "', '" . $POST['user_phone'] . "', ' ', '" . $POST['user_email'] . "', '" . $user_registered . "', '" . $POST['user_role'] . "', '" . $POST['user_status'] . "', '0') ";
        $result = $configs->query($query);

        if ($result) {
            /*             * *** Sent SMS To User Phone ********* */
            sent_sms_to_user(1, $user_id, $POST['new_user_pass']);
            /*             * *** Sent SMS To User Phone ********* */

            /*             * *** GET LAST User Info ********* */
            $get_customer_id = "SELECT ID, user_id, user_name FROM " . $vogomo_users_table . " ORDER BY ID DESC LIMIT 1";
            $get_customer_id_result = $configs->query($get_customer_id);
            foreach ($get_customer_id_result as $row) {
                $table_id = $row['ID'];
                $customer_id = $row['user_id'];
                $customer_name = $row['user_name'];
            }
            /*             * *** GET LAST User Info ********* */
            /*             * *** Add System Log ********* */
            $action_text = "Add New User : " . $customer_id . " - " . $customer_name;
            $action = "New User";
            save_system_log($table_id, $action_text, $action);
            /*             * *** Add System Log ********* */
            return array(true, "User Name - " . $user_id . " Save Successful!");
        } else {
            return array(true, "User Name - " . $user_id . " Can't Save!");
        }
    } else {
        return array(false, "User Name - " . $user_id . " Already Exist!");
    }
}

function sent_sms_to_user($status, $user_id, $password) {
    // Make Post Fields Array
    $data1 = array(
        'recipient' => '65' . $user_id,
        //'recipient' => '6593399595',
        'originator' => 'W.Station',
        'message' => "User ID - " . $user_id . "  Password - " . $password
    );

    //print_r(json_encode($data1));
    //exit;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.geniqtech.com/rest/v1/sms",
        CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => "",
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 30000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic VzVhNjAzNzk1ODllMGY6R05ZcnZiS00="
        ),
        CURLOPT_POSTFIELDS => http_build_query($data1),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        //echo "cURL Error #:" . $err;
    } else {
        if ($status == 1) {
            save_system_log($user_id, "SMS to New User " . $user_id, "SMS");
        } else {
            save_system_log($user_id, "Reset Password to New User " . $user_id, "SMS");
        }
        //print_r($response);
    }
}

function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function reset_password($table_id, $user_id) {
    $configs = include('config/config.php');
    $password = randomPassword();
    $user_pass = password_hash($password, PASSWORD_BCRYPT);
    $query = "UPDATE " . $vogomo_users_table . " SET user_pass = '" . $user_pass . "' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    $result = $configs->query($query);
    // print_r($query);
    if ($result) {
        /*         * *** Sent SMS To User Phone ********* */
        sent_sms_to_user(2, $user_id, $password);
        /*         * *** Sent SMS To User Phone ********* */

        /*         * *** Add System Log ********* */
        $action_text = "Update User Password : " . $user_id;
        $action = "Update User Password";
        save_system_log($table_id, $action_text, $action);
        /*         * *** Add System Log ********* */
        return array(true, "User Name - " . $user_id . " Password Update Successful!", $password);
    } else {
        return array(true, "User Name - " . $user_id . " Password Can't Reset!");
    }
}

function update_password($table_id, $user_id, $password) {
    $configs = include('config/config.php');
    $user_pass = password_hash($password, PASSWORD_BCRYPT);
    $query = "UPDATE " . $vogomo_users_table . " SET user_pass = '" . $user_pass . "' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    $result = $configs->query($query);
    if ($result) {
        /*         * *** Add System Log ********* */
        $action_text = "Update User Password : " . $user_id;
        $action = "Update User Password";
        save_system_log($table_id, $action_text, $action);
        /*         * *** Add System Log ********* */
        return array(true, "User Name - " . $user_id . " Password Update Successful!");
    } else {
        return array(true, "User Name - " . $user_id . " Password Can't Update!");
    }
}

function update_user($table_id, $user_id, $POST) {
    $configs = include('config/config.php');

    if (isset($POST['user_role']) && isset($POST['user_status'])) {
        $query = "UPDATE " . $vogomo_users_table . " SET user_name = '" . $POST['user_name'] . "', user_phone = '" . $POST['user_phone'] . "', user_address = ' ', user_email = '" . $POST['user_email'] . "', user_role = '" . $POST['user_role'] . "', user_status = '" . $POST['user_status'] . "' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    } else {
        $query = "UPDATE " . $vogomo_users_table . " SET user_name = '" . $POST['user_name'] . "', user_phone = '" . $POST['user_phone'] . "', user_address = ' ', user_email = '" . $POST['user_email'] . "' , check_first_time = '1' WHERE ID='" . $table_id . "' AND user_id='" . $user_id . "'";
    }

    /*     * *** Add System Log ********* */
    $action_text = "Update User : " . $user_id . " " . $POST['user_name'];
    $action = "Update User";
    save_system_log($table_id, $action_text, $action);
    /*     * *** Add System Log ********* */

    $result = $configs->query($query);
    return array(true, "User Name - " . $user_id . " Update Successful!");
}

function delete_user($table_id, $user_id) {
    $configs = include('config/config.php');

    $check_query = "SELECT * FROM " . $vogomo_orders_table . " WHERE customer_id='" . $table_id . "' ";
    $check_result = $configs->query($check_query);
    if ($check_result->num_rows === 0) {
        $admin_query = "SELECT * FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "' AND user_role='1' ";
        $admin_result = $configs->query($admin_query);
        if ($admin_result->num_rows === 0) {
            $query = "DELETE FROM " . $vogomo_users_table . " WHERE user_id='" . $user_id . "' AND ID='" . $table_id . "'";
            $result = $configs->query($query);

            /*             * *** Add System Log ********* */
            $action_text = "Delete User : " . $user_id;
            $action = "Delete User";
            save_system_log($table_id, $action_text, $action);
            /*             * *** Add System Log ********* */

            return array(true, "User Name - " . $user_id . " Already Deleted!");
        } else {
            return array(true, "Admin - " . $user_id . " Can't Delete!");
        }
    } else {
        return array(false, "User Name - " . $user_id . " Has Order History! Can't Delete!");
    }
}

/* * ********** User Functions ************** */

/* * ********** Water Level Functions ************** */

function get_water_level() {
    $configs = include('config/config.php');
    $query = "SELECT * FROM vogomo_currentstatus";
    $result = $configs->query($query);
    if ($result->num_rows > 1) {
        return array(2, $result);
    } else {
        $water_level = $result->fetch_array();
        return array(1, $water_level['water_level']);
    }
}

/* * ********** Water Level Functions ************** */
/* * ********** Get Error Code Array ************** */

function get_error_code($error_code) {
    if ($error_code == 1)
        return "WATER LEVEL FAULT";
    elseif ($error_code == 2)
        return "SUPPLY PUMP MOTOR OVERLOAD";
    elseif ($error_code == 3)
        return "WATER LEVEL FAULT AND SUPPLY PUMP MOTOR OVERLOAD";
    elseif ($error_code == 4)
        return "DISCHARGE PUMP OVERLOAD";
    elseif ($error_code == 5)
        return "WATER LEVEL FAULT AND DISCHARGE PUMP OVERLOAD";
    elseif ($error_code == 6)
        return "SUPPLY PUMP MOTOR OVERLOAD AND DISCHARGE PUMP OVERLOAD";
    elseif ($error_code == 7)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD AND DISCHARGE PUMP OVERLOAD";
    elseif ($error_code == 8)
        return "DOOR FAULT";
    elseif ($error_code == 9)
        return "WATER LEVEL FAULT AND DOOR FAULT";
    elseif ($error_code == 10)
        return "SUPPLY PUMP MOTOR OVERLOAD AND DOOR FAULT";
    elseif ($error_code == 11)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD AND DOOR FAULT";
    elseif ($error_code == 12)
        return "DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
    elseif ($error_code == 13)
        return "WATER LEVEL FAULT, DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
    elseif ($error_code == 14)
        return "SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
    elseif ($error_code == 15)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
    elseif ($error_code == 16)
        return "GANSET FAULT";
    elseif ($error_code == 17)
        return "WATER LEVEL FAULT AND GANSET FAULT";
    elseif ($error_code == 18)
        return "SUPPLY PUMP MOTOR OVERLOAD AND GANSET FAULT";
    elseif ($error_code == 19)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD AND GANSET FAULT";
    elseif ($error_code == 20)
        return "DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
    elseif ($error_code == 21)
        return "WATER LEVEL FAULT, DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
    elseif ($error_code == 22)
        return "SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
    elseif ($error_code == 23)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
    elseif ($error_code == 24)
        return "DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 25)
        return "WATER LEVEL FAULT, DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 26)
        return "SUPPLY PUMP MOTOR OVERLOAD, DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 27)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 28)
        return "DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 29)
        return "WATER LEVEL FAULT, DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 30)
        return "SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
    elseif ($error_code == 31)
        return "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
}

function get_error_code_with_message() {
    $array = array();
    for ($count = 1; $count <= 31; $count++) {
        if ($count == 1)
            $array[$count] = "WATER LEVEL FAULT";
        elseif ($count == 2)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD";
        elseif ($count == 3)
            $array[$count] = "WATER LEVEL FAULT AND SUPPLY PUMP MOTOR OVERLOAD";
        elseif ($count == 4)
            $array[$count] = "DISCHARGE PUMP OVERLOAD";
        elseif ($count == 5)
            $array[$count] = "WATER LEVEL FAULT AND DISCHARGE PUMP OVERLOAD";
        elseif ($count == 6)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD AND DISCHARGE PUMP OVERLOAD";
        elseif ($count == 7)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD AND DISCHARGE PUMP OVERLOAD";
        elseif ($count == 8)
            $array[$count] = "DOOR FAULT";
        elseif ($count == 9)
            $array[$count] = "WATER LEVEL FAULT AND DOOR FAULT";
        elseif ($count == 10)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD AND DOOR FAULT";
        elseif ($count == 11)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD AND DOOR FAULT";
        elseif ($count == 12)
            $array[$count] = "DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
        elseif ($count == 13)
            $array[$count] = "WATER LEVEL FAULT, DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
        elseif ($count == 14)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
        elseif ($count == 15)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND DOOR FAULT";
        elseif ($count == 16)
            $array[$count] = "GANSET FAULT";
        elseif ($count == 17)
            $array[$count] = "WATER LEVEL FAULT AND GANSET FAULT";
        elseif ($count == 18)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD AND GANSET FAULT";
        elseif ($count == 19)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD AND GANSET FAULT";
        elseif ($count == 20)
            $array[$count] = "DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
        elseif ($count == 21)
            $array[$count] = "WATER LEVEL FAULT, DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
        elseif ($count == 22)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
        elseif ($count == 23)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD AND GANSET FAULT";
        elseif ($count == 24)
            $array[$count] = "DOOR FAULT AND GANSET FAULT";
        elseif ($count == 25)
            $array[$count] = "WATER LEVEL FAULT, DOOR FAULT AND GANSET FAULT";
        elseif ($count == 26)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD, DOOR FAULT AND GANSET FAULT";
        elseif ($count == 27)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DOOR FAULT AND GANSET FAULT";
        elseif ($count == 28)
            $array[$count] = "DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
        elseif ($count == 29)
            $array[$count] = "WATER LEVEL FAULT, DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
        elseif ($count == 30)
            $array[$count] = "SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
        elseif ($count == 31)
            $array[$count] = "WATER LEVEL FAULT, SUPPLY PUMP MOTOR OVERLOAD, DISCHARGE PUMP OVERLOAD, DOOR FAULT AND GANSET FAULT";
    }
    return $array;
}

function get_error_code_array() {
    $array = array();
    for ($count = 1; $count <= 31; $count++) {
        $array[] = $count;
    }
    return $array;
}

/* * **** Check Latest Error Log ******* */

function check_error_code($error_code) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $current_date_time = date("Y-m-d H:i:s");

    $error_code_array = get_error_code_array();
    $get_error_code_with_message = get_error_code_with_message();
    $query = "SELECT * FROM " . $vogomo_error_log_table . " ORDER BY id DESC LIMIT 0, 1";

    $result = $configs->query($query);
    if ($result->num_rows === 0) {
        if (in_array($error_code, $error_code_array)) {
            /*             * ** New Error Log (Return to Send SMS) **** */
            $new_error_log = "INSERT INTO " . $vogomo_error_log_table . " (error_code, error_time, sms_time, solve_time) VALUES ('" . $error_code . "', '" . $current_date_time . "', '" . $current_date_time . "', NULL)";
            $new_error_log_result = $configs->query($new_error_log);
            if ($new_error_log_result == 1)
                return array(false, $get_error_code_with_message[$error_code], 'newsent');
        }else {
            return array(true, "NO ERROR", 'nosms');
        }
    } else {
        $error_log = $result->fetch_array();
        if ($error_log['error_code'] == $error_code) {
            /*             * *** Check Latest Error Solved Or Not ****** */
            if ($error_log['solve_time'] == NULL || $error_log['solve_time'] == '0000-00-00 00:00:00' || $error_log['solve_time'] == '' || $error_log['solve_time'] == 'NULL') {
                /*                 * ** Not Resolved Yet ***** */
                /*                 * ** Check Latest SMS Send Time **** */
                $sms_time = $error_log['sms_time'];
                $dbdate = strtotime($sms_time);
                if (time() - $dbdate > 15 * 60) {
                    //15 mins has passed (Return to Send SMS (Error Again)) *****/
                    //echo "15 min passed";
                    $update_error_log = "UPDATE " . $vogomo_error_log_table . " SET sms_time = '" . $current_date_time . "' WHERE id='" . $error_log['id'] . "' ";
                    $configs->query($update_error_log);
                    return array(false, $get_error_code_with_message[$error_code], 'resent');
                } else {
                    return array(true, "NO ERROR", 'nosms');
                }
            } else {
                if (in_array($error_code, $error_code_array)) {
                    /*                     * ** New Error Log (Return to Send SMS) **** */
                    $new_error_log = "INSERT INTO " . $vogomo_error_log_table . " (error_code, error_time, sms_time, solve_time) VALUES ('" . $error_code . "', '" . $current_date_time . "', '" . $current_date_time . "', NULL)";
                    $configs->query($new_error_log);
                    return array(false, $get_error_code_with_message[$error_code], 'newsent');
                } else {
                    /*                     * *** Fixed the Latest Error Log (Return to Send SMS (Error Resolved)) ***** */
                    if ($error_log['solve_time'] == NULL || $error_log['solve_time'] == '0000-00-00 00:00:00' || $error_log['solve_time'] == '' || $error_log['solve_time'] == 'NULL') {
                        $update_error_log = "UPDATE " . $vogomo_error_log_table . " SET solve_time = '" . $current_date_time . "' WHERE id='" . $error_log['id'] . "' ";
                        $configs->query($update_error_log);
                        return array(false, "ERROR RESOLVED", 'resolved');
                    }
                }
                //return array(true, "NO ERROR", 'nosms');
            }
        } else {
            if (in_array($error_code, $error_code_array)) {
                $update_error_log = "UPDATE " . $vogomo_error_log_table . " SET solve_time = '" . $current_date_time . "' WHERE id='" . $error_log['id'] . "' ";
                $configs->query($update_error_log);
                /*                 * ** New Error Log (Return to Send SMS) **** */
                $new_error_log = "INSERT INTO " . $vogomo_error_log_table . " (error_code, error_time, sms_time, solve_time) VALUES ('" . $error_code . "', '" . $current_date_time . "', '" . $current_date_time . "', NULL)";
                $configs->query($new_error_log);
                return array(false, $get_error_code_with_message[$error_code], 'newsent');
            } else {
                /*                 * *** Fixed the Latest Error Log (Return to Send SMS (Error Resolved)) ***** */
                if ($error_log['solve_time'] == NULL || $error_log['solve_time'] == '0000-00-00 00:00:00' || $error_log['solve_time'] == '' || $error_log['solve_time'] == 'NULL') {
                    $update_error_log = "UPDATE " . $vogomo_error_log_table . " SET solve_time = '" . $current_date_time . "' WHERE id='" . $error_log['id'] . "' ";
                    $configs->query($update_error_log);
                    return array(false, "ERROR RESOLVED", 'resolved');
                }
            }
        }
        return array(true, "NO ERROR", 'nosms');
    }
}

/* * **** Check Latest Error Log ******* */

function check_current_error() {
    $configs = include('config/config.php');
    $query = "SELECT error_code FROM vogomo_currentstatus";
    $result = $configs->query($query);
    $error_code = $result->fetch_array();
    $error_code_array = get_error_code_array();
    $get_error_code_with_message = get_error_code_with_message();
    if (in_array($error_code['error_code'], $error_code_array)) {
        return array(false, $error_code['error_code'], $get_error_code_with_message[$error_code['error_code']]);
    }
    return array(true, $error_code['error_code'], 'NO ERROR');
}

/* * ********** Get Error Code Array ************** */

/* * ********** Order Functions ************** */

function get_order_list($status) {
    $configs = include('config/config.php');
    if ($status == 'pending') {
        $status = 0;
    } else {
        $status = 1;
    }
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE orders.status='" . $status . "' ORDER BY create_date DESC";
    //order_id DESC
    $result = $configs->query($query);
    return $result;
}

function get_order_count($status) {
    $configs = include('config/config.php');
    $query = "SELECT count(*) as count FROM " . $vogomo_orders_table . " WHERE status='" . $status . "'";
    $result = $configs->query($query);
    $count = $result->fetch_array();
    return $count['count'];
}

function get_order_report($start_date, $end_date) {
    $configs = include('config/config.php');

    //$query = "SELECT *FROM " .  $vogomo_orders_table . " WHERE (date BETWEEN '" . $start_date . "' AND '" . $end_date . "') Order By date DESC";
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE orders.status='1' AND (orders.date BETWEEN '" . $start_date . "' AND '" . $end_date . "') ORDER BY orders.order_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_sms_report($start_date, $end_date) {
    $configs = include('config/config.php');
    $where_clause = "";

    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE (orders.create_date BETWEEN '" . $start_date . "' AND '" . $end_date . "') " . $where_clause . " ORDER BY orders.order_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_order_report_by_all_filter($start_date, $end_date, $customer_id, $status) {
    $configs = include('config/config.php');
    $where_clause = "";
    if ($customer_id != "")
        $where_clause .= " AND orders.customer_id='" . $customer_id . "'";
    if ($status != "")
        $where_clause .= " AND orders.status='" . $status . "'";

    //$query = "SELECT *FROM " .  $vogomo_orders_table . " WHERE (date BETWEEN '" . $start_date . "' AND '" . $end_date . "') Order By date DESC";
    $query = "SELECT orders.*, users.user_id as user_id, users.user_name as user_name FROM " . $vogomo_orders_table . " as orders LEFT JOIN " . $vogomo_users_table . " as users ON orders.customer_id = users.ID  WHERE (orders.create_date BETWEEN '" . $start_date . "' AND '" . $end_date . "') " . $where_clause . " ORDER BY orders.order_id DESC";
    $result = $configs->query($query);
    return $result;
}

function get_order_list_by_customer($customer_id, $status) {
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

function get_order_by_id($order_id) {
    $configs = include('config/config.php');

    $query = "SELECT * FROM " . $vogomo_orders_table . " WHERE order_id ='" . $order_id . "'";
    $result = $configs->query($query);
    if ($result->num_rows === 0) {
        return false;
    } else {
        return $user = $result->fetch_array();
    }
}

function save_order($login_id, $POST) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $user_registered = date("Y-m-d H:i:s");

    // Check Invoice Number already Exist Or Not
    $query = "SELECT * FROM " . $vogomo_orders_table . " WHERE invoice_number ='" . $POST['order_number'] . "'";
    $result = $configs->query($query);

    if ($result->num_rows === 0) {
        //$query = "INSERT INTO " . $vogomo_orders_table . " (invoice_number, customer_id, qty, date, status, remark, created_by) VALUES ('" . $POST['order_number'] . "', '" . $POST['customer_id'] . "', '" . $POST['order_qty'] . "', '" . $POST['order_date'] . "', '0', '" . $POST['order_remark'] . "',  '" . $_SESSION['login_id'] . "', ) ";
        if ($POST['customer_id'] == "" || $POST['customer_id'] == "0") {
            return array(false, "Please Choose Customer!");
        } else {
            $query = "INSERT INTO " . $vogomo_orders_table . " (invoice_number, customer_id, qty, create_date, complete_date, status, remark, action_by) VALUES ('" . $POST['order_number'] . "', '" . $POST['customer_id'] . "', '" . $POST['order_qty'] . "', '" . $user_registered . "', NULL, '0', '" . $POST['order_remark'] . "',  '" . $_SESSION['login_id'] . "' ) ";

            $result = $configs->query($query);

            /*             * ***** Save System Log ******** */
            $get_order_id = "SELECT order_id, invoice_number FROM " . $vogomo_orders_table . " ORDER BY order_id DESC LIMIT 1";
            $get_order_id_result = $configs->query($get_order_id);
            foreach ($get_order_id_result as $row) {
                $order_id = $row['order_id'];
            }
            $action_text = "Add New Order - " . $POST['order_number'] . " For Customer " . $POST['customer_id'];
            $action = "New Order";
            save_system_log($order_id, $action_text, $action);
            /*             * ***** Save System Log ******** */

            //$_SESSION['message'] = "Invoice Number - " . $POST['order_number'] . " Save Successful!";
            return array(true, "Invoice Number - " . $POST['order_number'] . " Save Successful!");
        }
    } else {
        return array(false, "Invoice Number - " . $POST['order_number'] . " Already Exist!");
    }
}

function update_order($order_id, $order_number, $POST) {
    $configs = include('config/config.php');
    date_default_timezone_set('asia/singapore');
    $complete_date = date("Y-m-d H:i:s");
    if ($POST['order_status'] == '1') {
        $query = "UPDATE " . $vogomo_orders_table . " SET customer_id = '" . $POST['customer_id'] . "', qty = '" . $POST['order_qty'] . "', create_date = '" . $POST['order_date'] . "', complete_date = '" . $complete_date . "', status = '" . $POST['order_status'] . "', remark = '" . $POST['order_remark'] . "' WHERE order_id='" . $order_id . "' AND invoice_number='" . $order_number . "'";
    } else {
        $query = "UPDATE " . $vogomo_orders_table . " SET customer_id = '" . $POST['customer_id'] . "', qty = '" . $POST['order_qty'] . "', create_date = '" . $POST['order_date'] . "', status = '" . $POST['order_status'] . "', remark = '" . $POST['order_remark'] . "' WHERE order_id='" . $order_id . "' AND invoice_number='" . $order_number . "'";
    }

    $result = $configs->query($query);

    /*     * ***** Save System Log ******** */
    $action_text = "Edit Order - " . $order_number . " For Customer " . $POST['customer_id'];
    $action = "Edit Order";
    save_system_log($order_id, $action_text, $action);
    /*     * ***** Save System Log ******** */

    return array(true, "Order Number - " . $order_number . " Update Successful!");
}

function delete_order($order_id, $order_number) {
    $configs = include('config/config.php');

    $query = "DELETE FROM " . $vogomo_orders_table . " WHERE order_id='" . $order_id . "'";
    $result = $configs->query($query);

    /*     * ***** Save System Log ******** */
    $action_text = "Delete Order : " . $order_number;
    $action = "Delete Order";
    save_system_log($order_id, $action_text, $action);
    /*     * ***** Save System Log ******** */

    return array(true, "Order Number - " . $order_number . " Already Deleted!");
    //exit;
}

/* * ********** Order Functions ************** */
?> 