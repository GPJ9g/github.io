<?php require_once 'inc/check.php' ?>
<?php require './api/function.php' ?>
<?php 
  $categories = xiu_fetch_all("SELECT id,name FROM categories");
 ?>
 <?php 
 //=================================================================================
 //添加数据
 function posts_add(){
  if(empty($_POST['slug'])){
    return;
  }
  if(empty($_POST['title'])){
    return;
  }
  if(empty($_POST['concent'])){
    return;
  }

  $slug = $_POST['slug'];
  $title = $_POST['title'];
  $concent = $_POST['concent'];
  $category = $_POST['category'];
  $status = $_POST['status'];
  $created = date('y-m-d h:i:s');

  echo $concent;
  
  if(isset($_POST['id'])){
    $sql = sprintf("update posts set
      slug='%s',title='%s',content='%s',created='%s',status='%s',category_id=%d
      where id=%d",$slug,$title,$concent,$created,$status,$category,$_POST['id']);
    $row = xiu_query($sql);
  }else{
     $sql = sprintf("insert into posts values (null,'%s','%s','%s','%s','%s',1,%d)",
     $slug,$title,$created,$concent,$status,$category);
     $row = xiu_query($sql);
  }
 }
 //===============================================================================
 //
 function getQuery(){
  $id=$_GET['id'];
  return xiu_fetch_one(sprintf('select * from posts where id=%d',$id));
 }
  //=============================================================
  //
  if($_SERVER['REQUEST_METHOD']=='POST'){
    posts_add();
  }
  if($_SERVER['REQUEST_METHOD']=='GET'){
    if(isset($_GET['id'])){
      $content = getQuery();
    }
  }
  ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>添加文章</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    <?php include 'inc/navbar.php' ?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <?php if(isset($content)) : ?>
          <form class="row" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <div class="col-md-9">
              <div class="form-group">
                <input type="hidden" name="id" value="<?php echo $content['id'] ?>">
                <label for="title">标题</label>
                <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题"
                value="<?php echo $content['title'] ?>">
              </div>

               <textarea id="editor_id" name="concent" style="width:1000px;height:300px;">
                  <?php echo $content['content'] ?> 
               </textarea>

            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="slug">别名</label>
                <input id="slug" class="form-control" name="slug" type="text" placeholder="slug"
                value="<?php echo $content['slug'] ?>">
                <p class="help-block"><strong>slug</strong></p>
              </div>
              <div class="form-group">
                <label for="category">所属分类</label>
                <select id="category" class="form-control" name="category">
                <?php foreach($categories as $item) : ?>
                  <option value="<?php echo $item['id'] ?>"<?php echo $item['id']==$content['category_id'] ?
                   'selected = "selected"' : "" ?>><?php echo $item['name'] ?>
                  </option>
                <?php endforeach ?>   
                </select>
              </div>

              <div class="form-group">
                <label for="created">发布时间</label>
                <input id="created" class="form-control" name="created" type="datetime-local">
              </div>

              <div class="form-group">
                <label for="status">状态</label>
                <select id="status" class="form-control" name="status">
                  <option value="drafted" <?php echo $content['status']=="drafted" ?
                   'selected = "selected"' : "" ?>>草稿</option>
                  <option value="published" <?php echo $content['status']=="published" ?
                   'selected = "selected"' : "" ?>>已发布</option>
                </select>
              </div>

              <div class="form-group">
                <button class="btn btn-primary" type="submit">保存</button>
              </div>
            </div>
          </form>

      <?php else : ?>

        <form class="row" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
          <div class="col-md-9">
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
            </div>
            
            <!-- 富文本编辑器 -->
             <textarea id="editor_id" name="concent" style="width:1000px;height:300px;"> 
             </textarea>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block"><strong>slug</strong></p>
            </div>
            <!-- <div class="form-group">
              <label for="feature">特色图像</label>
              show when image chose
              <img class="help-block thumbnail" style="display: none">
              <input id="feature" class="form-control" name="feature" type="file">
            </div> -->
            <div class="form-group">
              <label for="category">所属分类</label>
              <select id="category" class="form-control" name="category">
              <?php foreach($categories as $item) : ?>
                <option value="<?php echo $item['id'] ?>"><?php echo $item['name'] ?></option>
              <?php endforeach ?>   
              </select>
            </div>

            <div class="form-group">
              <label for="created">发布时间</label>
              <input id="created" class="form-control" name="created" type="datetime-local">
            </div>

            <div class="form-group">
              <label for="status">状态</label>
              <select id="status" class="form-control" name="status">
                <option value="drafted">草稿</option>
                <option value="published">已发布</option>
              </select>
            </div>

            <div class="form-group">
              <button class="btn btn-primary" type="submit">保存</button>
            </div>
          </div>
        </form>

      <?php endif ?>  
    </div>
  </div>


   <?php $current_index = 'post-add' ?>
   <?php include 'inc/sider.php' ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>

  <script charset="utf-8" src="\static\assets\vendors\kindeditor/kindeditor-all-min.js"></script>
  <script charset="utf-8" src="\static\assets\vendors\kindeditor/lang/zh-CN.js"></script>
  <script>
        const options = {
        items : ['preview','plainpaste','wordpaste','fontsize','forecolor'
        ,'code','lineheight','insertfile','fullscreen']
        }
            KindEditor.ready(function(K) {
                    window.editor = K.create('#editor_id',options);
            });
  </script>
</body>
</html>
