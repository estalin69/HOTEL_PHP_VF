<?php

$servername = "localhost";
$username = "estalin";
$password = "73219707";
$dbname = "bd_hotel_v1";
$port = 3306;


$conn = new mysqli($servername, $username, $password, $dbname, $port);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
