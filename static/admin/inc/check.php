<?php 
session_start();
if(empty($_SESSION['user_date'])){
	header('location:login.php');
}

