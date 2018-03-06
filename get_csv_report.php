<?php require("config/function.php"); ?>
<?php
	error_reporting(E_ERROR | E_PARSE);
	$current_date_time = date("d_m_Y");
	
	/*
	$d = "export/".$current_date_time.".csv";
	echo $d;
	$f = fopen($d, "w") or die("did not open");
	fwrite($f, $editor) or die("did not write");
	fclose($f);
	*/
	
	header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");

echo "col1,col2";
for($i=0; $i<25;$i++)
{
    echo "key :".$i.", ".($i*$i)."\r\n";
}
	
	exit;
	
	
    $action = $_GET['action'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
	$customer_id = $_GET['customer_id'];
	$order_status = $_GET['order_status'];
    
    $list_csv = array();
    if($action === 'order_report'){
        //$get_all_orders_by_customer = get_order_report($start_date, $end_date);
		$get_all_orders_by_customer = get_order_report_by_all_filter($start_date, $end_date, $customer_id, $order_status);
        foreach($get_all_orders_by_customer as $order_report){            
            $data["Invoice Number"] = $order_report['invoice_number'];
            $data["Contact Person Name"] = $order_report['user_id'] . " - " . $order_report['user_name'];
            $data["Quantity"] =  $order_report['qty'];
            $data["Date"] = date("d-M-Y", strtotime($order_report['date']));
            //$data["Date"] = date("d-M-Y", strtotime($order_date));
            array_push($list_csv, $data);
        }
        $file = fopen("export/order_csv_report_". $current_date_time .".csv", "w");
        $first_line = ["Invoice Number", "Contact Person Name", "Quantity", "Date"];
        fputcsv($file, $first_line);
        foreach ($list_csv as $line){
                fputcsv($file, $line);
        }
        fclose($file);
        //$path = plugins_url("export/order_csv_report_". $current_date_time .".csv", __FILE__);
        $path = "export/order_csv_report_". $current_date_time .".csv";
        echo json_encode($path);
        exit();
    }
?>