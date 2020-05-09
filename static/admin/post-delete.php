<?php 
include 'api/function.php';
if( isset($_GET['id'])  ){
	$rows = xiu_query("delete from posts where id in ( ".$_GET['id'].")") ;
	
}
header('location:'.$_SERVER['HTTP_REFERER']);
