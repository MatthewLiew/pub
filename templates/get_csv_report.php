<?php
    $action = $_GET['action'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $current_date_time = date("d_m_Y");
    $list_csv = array();
    if($action === 'order_report'){
        $order_report = get_order_report($start_date, $end_date);
        foreach($get_all_orders_by_customer as $list){            
            $data["Invoice Number"] = $order_report['invoice_number'];
            $data["Contact Person Name"] = $order_report['user_id'] . " - " . $order_report['user_name'];
            $data["Quantity"] =  $order_report['qty'];
            $data["Date"] = date("d-M-Y", strtotime($order_date));
            //$data["Date"] = date("d-M-Y", strtotime($order_date));
            array_push($list_csv, $data);
        }
        $file = fopen("../export/order_csv_report_". $current_date_time .".csv", "w");
        $first_line = ["Invoice Number", "Contact Person Name", "Quantity", "Date"];
        fputcsv($file, $first_line);
        foreach ($list_csv as $line){
                fputcsv($file, $line);
        }
        fclose($file);
        $path = plugins_url("../export/order_csv_report_". $current_date_time .".csv", __FILE__);
        echo json_encode($path);
        exit();
    }
?>