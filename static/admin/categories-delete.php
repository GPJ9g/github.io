<?php 
include dirname(__File__).'api/function.php';
if( isset($_GET['id'])  ){
	$rows = xiu_query("delete from categories where id in ( ".$_GET['id'].")") ;
	
}
header('location:baixiu/static/admin/categories.php');


