<?php require_once 'inc/check.php' ?>
<?php require './api/function.php' ?>
<?php 
  $categories = xiu_fetch_all("SELECT id,name FROM categories");
 ?>
 <?php 
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
  

 // $txt = "insert into posts values (null, '{$slug}', '{$title}', '{$created}', '{$concent}','{$status}', 1, {$category})";
 $sql = sprintf("insert into posts values (null,'%s','%s','%s','%s','%s',1,%d)",
  $slug,$title,$created,$concent,$status,$category);
 $row = xiu_query($sql);


 }
  //=============================================================
  if($_SERVER['REQUEST_METHOD']=='POST'){
    posts_add();
  }
  ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
      <form class="row" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>

           <textarea id="editor_id" name="concent" style="width:1000px;height:300px;">
               HTML内容;
           </textarea>
        <!--   <div class="form-group" id="editor">
          
          </div>
          <textarea name="concent" id="textarea" style="display: none"></textarea> -->
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
  <!-- 富文本编辑器 -->
  <!-- <script type="text/javascript" src="\static\assets\vendors\wangEditor\release\wangEditor.min.js"></script>
   <script type="text/javascript">
    const E = window.wangEditor
    const editor = new E('#editor')
    const $textarea = $('textarea')
    // 自定义菜单配置
    editor.customConfig.menus = [
        'head',
        'bold',
        'code',
        'link',
        'fontSize'
    ]
     // 监控变化，同步更新到 textarea
     editor.customConfig.onchange = function (html) {
            $textarea.val(html)
        }
    editor.create()
    $textarea.val(editor.txt.html())//初始化textare的值
    </script> -->
</body>
</html>
