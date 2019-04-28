<?php 

$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "japanese_review";

$conn = new mysqli($servername, $username, $password,$dbname);
date_default_timezone_set('Asia/Bangkok'); 
session_start();
?>