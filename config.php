<?php
$host = 'localhost';
$user  = 'root';
$psw = '';
$db = 'std_management2';
$conn = new mysqli($host, $user, $psw, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}