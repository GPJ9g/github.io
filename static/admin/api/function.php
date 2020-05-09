<?php 
    require_once dirname(dirname(__FILE__)).'/inc/config.php' ;
    /**
     * [xiu_fetch_all description]
     * sql 查询 结果为数组
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
	function xiu_fetch_all ($sql) {
		$connect=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if(!$connect)  exit('error') ;
		$query=mysqli_query($connect,$sql);
		if(!$query) return  ;
		while($row=mysqli_fetch_assoc($query)){
		   $date[]=$row;
		}
		mysqli_close($connect);
		return isset($date)? $date : "";
	}	
	/**
	 * [xiu_fetch_one description]
	 * sql 查询 结果为单
	 * @param  [type] $sql [description]
	 * @return [type]      [description]
	 */
	function xiu_fetch_one($sql){
		$res = xiu_fetch_all($sql);
		return isset($res[0]) ? $res[0] : NULL;
	}		
    /**
     * [xiu_query description]
     * sql 查询 改了多少的条数
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
	function xiu_query($sql){
		$connect=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if(!$connect)  exit('error') ;
		$query=mysqli_query($connect,$sql);
		if(!$query) return  ;
		$row =mysqli_affected_rows($connect);
		mysqli_close($connect);
		return $row;
	}
