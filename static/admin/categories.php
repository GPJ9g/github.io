<?php require_once dirname(__File__).'/inc/check.php' ;?>
<?php require_once dirname(__File__).'/api/function.php' ;?>
<?php  
  function add_catgories(){
    $name=$_POST['name'];
    $slug=$_POST['slug'];
    $row=xiu_query(sprintf("insert into categories VALUES (null,'%s','%s')",$slug,$name));
    $GLOBALS['success']= $row>0;
    $GLOBALS['tips']= $row >0 ? '提交成功' : '提交失败' ;
  }
  function edit_catgories(){
    $edit_id=$_GET['id'];
    return xiu_fetch_one("select * from categories where id={$edit_id}");
  }
  function update_catgories(){
    xiu_query(sprintf("update categories set name='%s',slug='%s' where id='%d'",$_POST['name'],$_POST['slug'],$_GET['id']));
  }
  if($_SERVER['REQUEST_METHOD']==='POST'){
    if(empty($_POST['name']) || empty($_POST['slug'])){
      $GLOBALS['tips']='请输入完整信息';
    }else{
      if(isset($_GET['id'])){
        update_catgories();

      }else{
        add_catgories();
     
      } 
    }
  }
  if($_SERVER['REQUEST_METHOD']==='GET'){
      if(isset($_GET['id'])){
           $edit_date=edit_catgories();
      }
  }
?>
<?php 
  $dates=xiu_fetch_all("select * from categories");
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include '../admin/inc/navbar.php' ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
  
      <?php if(isset($GLOBALS['tips'])) : ?>
        <?php if(isset($GLOBALS['success']) && $GLOBALS['success']==true) : ?>
        <div class="alert alert-success">
           <strong>成功！</strong><?php echo $GLOBALS['tips']; ?>
        </div>
      <?php else : ?>
        <div class="alert alert-danger">
           <strong>错误！</strong><?php echo $GLOBALS['tips']; ?>
        </div>
      <?php endif ?>
      <?php endif ?>
      <div class="row">
        <div class="col-md-4">
        <?php if(empty($edit_date))  : ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method='post'>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
            </form>
        <?php endif ?>
        <?php if(isset($edit_date)) : ?>
           <form action="<?php echo $_SERVER['PHP_SELF'] ; ?>?id=<?php echo $edit_date['id']; ?>" method='post'>
            <h2>编辑<<?php echo $edit_date['slug'] ?>></h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" value="<?php echo $edit_date['name'] ?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" value="<?php echo $edit_date['slug'] ?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">编辑</button>
            </div>
            </form>
        <?php endif ?>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id='btn_delete' class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php if(!empty($dates)): ?>
             <?php foreach ($dates as $item) : ?>
              <tr>
                <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['slug']; ?></td>
                <td class="text-center">
                  <a href="categories.php?id=<?php echo $item['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="categories-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
             <?php endforeach ?>
            <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
    
   <?php $current_index = 'categories' ?>
   <?php include 'inc/sider.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
  $(function($){
    var dates=[]
      $('tbody input').on('change',function(){
        var id=$(this).data('id')
        $('#btn_delete').show()
        if($(this).prop("checked")){
            dates.push(id)
          }else{
            dates.splice($(this).index()-1,1)
            if(dates.length===0){
                $('#btn_delete').hide()
            }
          }
      
        
        console.log(dates,id,$(this).index())
      })
      $('#btn_delete').on('click',function(){
          // $.get("categories-delete.php?id="+dates)
          // 
          $(this).attr("href","categories-delete.php?id="+dates)
      })
      $('thead input').on('change',function(){
        $('tbody input').prop("checked",$(this).prop("checked"))
        dates=[]
        if($(this).prop("checked")){
          for(var i=0;i<$('tbody input').length;i++){
             dates.push($('tbody input')[i].getAttribute('data-id'))
          }
           $('#btn_delete').show()
           return
        }
        $('#btn_delete').hide()
      })
    }
  )

  </script>
  <script>NProgress.done()</script>
</body>
</html>
