<?php require dirname(__File__).'/api/function.php' ?>
<?php 
require_once 'inc/config.php';
function current_user(){
   $nickname=$_POST['nickname'];
   $email=$_POST['email'];
   $password=$_POST['password'];
   $agpassword=$_POST['ag-password'];
   if($password != $agpassword){
      $GLOBALS['error']='两次密码不一致';
      return;
   }
   $connet=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
   if(empty($connet)){
      return;
   }
   $query=mysqli_query($connet,sprintf("select * from users where email ='%s' limit 1",$email) );

   if(empty($query)){
    var_dump($query);
    return;
   }
   $date=mysqli_fetch_assoc($query);
   if(isset($date)){
     $GLOBALS['error']='邮箱已存在';
     return;
   }
   $sql = sprintf("insert into users values (null,'%s','%s','%s','%s','/static/uploads/avatar.jpg',null,
   'ordinary')",$email,$email,$password,$nickname);
   var_dump($sql);
   $row = xiu_query($sql);
   var_dump($row);


 }
 //=============================================================
 //请求为post
 if($_SERVER['REQUEST_METHOD']=='POST'){
    if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['nickname']) || empty($_POST['ag-password'])){
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
    <form class="login-wrap" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post' >
      <img class="avatar" src="../assets/img/default.png">
      <?php if(isset($GLOBALS['error'])) : ?>
      <div class="alert alert-danger">
        <strong><?php echo $GLOBALS['error']; ?></strong> 
      </div>
      <?php endif; ?>

      <div class="form-group">
        <label for="nickname" class="sr-only" >昵称</label>
        <input id="nickname" name='nickname' type="text" class="form-control" placeholder="昵称" autofocus <?php if(isset($GLOBALS['error'])){echo "value=".$_POST['email'];} ?> autocomplete='off'>
      </div>
      <div class="form-group">
        <label for="email" class="sr-only" >邮箱</label>
        <input id="email" name='email' type="emali" class="form-control" placeholder="邮箱" autofocus <?php if(isset($GLOBALS['error'])){echo "value=".$_POST['email'];} ?> autocomplete='off'>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" type="password" class="form-control" placeholder="密码" autocomplete='off'>
      </div>
      <div class="form-group">
        <label for="ag-password" class="sr-only">确认密码</label>
        <input id="ag-password" name="ag-password" type="password" class="form-control" placeholder="确认密码" autocomplete='off'>
     </div>
      <button class="btn btn-primary btn-block">注 册</button>
    </form>
  </div>
</body>
</html>