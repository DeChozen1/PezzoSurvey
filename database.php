<?php

$servername = "127.0.0.1";
$port = 3306;
$username = "u541390858_PizzaSlice";
$password = "WyssMemento38!!!!";
$dbname = "u541390858_PizzaFans";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: "
        . $conn->connect_error);
}