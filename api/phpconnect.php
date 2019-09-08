<?php
$servername = "localhost";
$username = "id10810736_root";
$password = "cpdadmin";
$database = "id10810736_desafio_asc_php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {    
    echo "Connection failed: " . $e->getMessage();
    }
?>