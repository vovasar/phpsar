<?php
require ('bd.php');
if (!empty($_POST)){
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$name=$_POST['name'];
$phone=$_POST['phone'];
$email=$_POST['email'];
$adress=$_POST['adress'];
$sql="INSERT INTO friends(first_name, last_name, name, phone, email, adress)
      VALUES ('$first_name', '$last_name', '$name', '$phone','$email', '$adress')"; 
if (mysqli_query($mysqli,$sql)){
echo "добавленоо";} else{echo "ошипка" . mysqli_error($mysqli);
}
} 

if (mysqli_errno($mysqli)) echo mysqli_error();
header("Location:create.php");

if (isset($_GET['role'])){
      $sql = "UPDATE `users` SET `role`='reader' WHERE `id`==".$_GET['role'];
      mysqli_query($mysqli, $sql);
      if (mysqli_errno($mysqli)) echo mysqli_error();
}
?>

    