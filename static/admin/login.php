<?php 
require_once 'inc/config.php';
function current_user(){
   $email=$_POST['email'];
   $password=$_POST['password'];
   $connet=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   if(empty($connet)){
   		return;
   }
   $query=mysqli_query($connet,sprintf("select * from users where email ='%s' limit 1",$email) );
   if(empty($query)){
   	return;
   }
   $date=mysqli_fetch_assoc($query);
   if(!$date){
     $GLOBALS['error']='邮箱或密码不匹配';
     return;
   }
   if($date['password']!==$password){
     $GLOBALS['error']='邮箱与密码错舞';
     return;
   }
   session_start();
   $_SESSION['user_date']=$date;
   mysqli_close($connet);
   header('location:index.php');
 }
 //=============================================================
 //请求为post
 if($_SERVER['REQUEST_METHOD']=='POST'){
  	if(empty($_POST['email']) || empty($_POST['password'])){
    	$GLOBALS['error'] = '请输入完整信息';
  	}else{
  		current_user();
    }
  }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post' autocomplete='off'>
      <img class="avatar" src="../assets/img/default.png">
      <?php if(isset($GLOBALS['error'])) : ?>
      <div class="alert alert-danger">
        <strong><?php echo $GLOBALS['error']; ?></strong> 
      </div>
      <?php endif; ?>
      <div class="form-group">
        <label for="email" class="sr-only" >邮箱</label>
        <input id="email" name='email' type="emali" class="form-control" placeholder="邮箱" autofocus <?php if(isset($GLOBALS['error'])){echo "value=".$_POST['email'];} ?>>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block">登 录</button>
      <a class="btn btn-primary btn-block" href="/static/admin/register.php">注 册</a> 
    </form>
  </div>
  <script>
    function fun(date){
      console.log(date)
    }
    $email=document.getElementById('email').value
    var xml=new XMLHttpRequest()
    xml.open('GET','api/avavtar.php?email='+$email)
    xml.send(null)
    xml.onreadystatechange=function(){
      if(this.readyState!==4) return
        fun(this.responseText)
 
    }
   
     // console.log(XMLHttpRequest,xml)
  </script>
</body>
</html>
