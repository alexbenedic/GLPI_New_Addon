<?php
$servername = "192.168.0.188";
$username = "glpi";
$password = "glpisecret";
$dbname = "glpi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
//echo "Connected successfully";
?>