<?php 
    $host = "localhost";
    $database = "spedi_warehouse";
    $username = "root";
    $password = "";
    $conn = mysqli_connect($host, $username, $password, $database);

    // Check Connection
    if (!$conn()) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
?>