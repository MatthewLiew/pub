<?php
    //error_reporting(E_ALL | E_ALL);
    //Server DB Settings//
    require("database_table.php");
    /*$hostserver = "localhost";
    $userserver = "vogomo_pub";
    $passwordserver = "Admin@123";
    $dbnameserver = "vogomo_pub";*/
	
    $hostserver = "localhost";
    $userserver = "root";
    $passwordserver = "hydrax123";
    $dbnameserver = "vogomo_pub";
	
    $server = new mysqli($hostserver, $userserver, $passwordserver, $dbnameserver);
    if ($server->connect_errno) {
        echo "Failed to connect to MySQL: (" . $server->connect_errno . ") " . $server->connect_error;
        exit;
    } else {
        return $server; 
    }
?>