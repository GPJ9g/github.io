<?php 
require_once '../inc/config.php';
if(empty($_GET)){
	header('location:login.php');
}
$email=$_GET['email'];
$connect=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$query=mysqli_query($connect,sprintf("select * from users where email = %s limit 1",$email));
$date=mysqli_fetch_assoc($query);
mysqli_close($connect);
header('Content_Type:application/javascript');
$json=json_encode($date);
echo "fun({$json})";
