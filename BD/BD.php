<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'notebook';
$mysqli = mysqli_connect($host, $user, $password, $database);
if (mysqli_connect_errno()) echo mysqli_connect_error();
?>
