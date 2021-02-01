<?php 
    $host = "localhost";
    $database = "spedi_warehouse";
    $username = "root";
    $password = "";
    $conn = mysqli_connect($host, $username, $password, $database);

    // Check Connection
    if (mysqli_connect_error()) {
        echo "Failed to connect to DB: " . mysqli_connect_error();
        exit();
    }
    
?>