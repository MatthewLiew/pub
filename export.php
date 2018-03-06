<?php
	require("config/function.php");
	date_default_timezone_set('asia/singapore');
	$current_date_time = date("d_m_Y");
	
	$action = $_GET['action'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
	$customer_id = $_GET['customer_id'];
	$order_status = $_GET['order_status'];
	
	// No point in creating the export file on the file-system. We'll stream
	// it straight to the browser. Much nicer.

	// Open the output stream
	$fh = fopen('php://output', 'w');

	// Start output buffering (to capture stream contents)
	ob_start();

	// CSV Header
	$header = array();
	//$header = array('Invoice Number', 'Contact Person Name', 'Quantity', 'Date');
	$header = array('Invoice_Number', 'Contact Person Name', 'Quantity', 'Date');
	fputcsv($fh, $header);

	// CSV Data
	//foreach ($data as $k => $v) {
		// $line = array(1, 'name', '55', '');
		// fputcsv($fh, $line);
	///}
	
	$get_all_orders_by_customer = get_order_report_by_all_filter($start_date, $end_date, $customer_id, $order_status);
	foreach($get_all_orders_by_customer as $order_report){        
		//$line = array(1, 'name', '55', '');
		$invoice_number = $order_report['invoice_number'];
		$user_name = $order_report['user_id'] . " - " . $order_report['user_name'];
		$qty =  $order_report['qty'];
		$date = date("d-M-Y", strtotime($order_report['date']));
		$line = array($invoice_number, $user_name, $qty, $date);
		fputcsv($fh, $line);
	}

	// Get the contents of the output buffer
	$string = ob_get_clean();

	// Set the filename of the download
	$filename = 'order_csv_report_' . date('Ymd') .'-' . date('His');

	// Output CSV-specific headers
	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private', false);
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . $filename . '.csv";');
	header('Content-Transfer-Encoding: binary');

	// Stream the CSV data
	exit($string);
	
	/*************************************************/
?>