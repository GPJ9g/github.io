<?php error_reporting(0);?>
<?php require_once 'inc/check.php' ?>
<?php require_once 'api/function.php' ?>
<?php 
    function retime($date){
      $time= strtotime($date);
      return date('Y年m月d日 <b\r> h时i分s秒',$time);
    }

    function state($date){
      $array=[
          'published' => '已发布',
           'drafted' => '草稿',
            'trashed' => '回收站'
      ];
      return empty($array[$date])? '未知' : $array[$date];
    }
   //=======================================================================================
   //分类
   //
    $array=[
          'published' => '已发布',
           'drafted' => '草稿',
            'trashed' => '回收站'
      ];
   //========================================================================================
   $indexs=empty($_GET['indexs'])? 1 : $_GET['indexs'];
   $size=10;
   $tag=($indexs-1)*$size;
   //========================================================================================
   //查询分类
   $categories = xiu_fetch_all("
    SELECT id ,name  from categories
    ");
   //=========================================================================================
   //查询全部文章
   // 

    // 全选
    // 
    if(empty($_GET[sort]) && empty($_GET[status])){
       $sort = '1=1';
    }elseif(empty($_GET[status]) && isset($_GET[sort])){
       $sort = 'posts.category_id='.$_GET[sort];
       
    }elseif(empty($_GET[sort]) && isset($_GET[status])){
       $sort="posts.status='{$_GET[status]}'";
       
    }
    else{
       $sort= "posts.category_id={$_GET[sort]} AND posts.status='{$_GET[status]}'";
      // $sort= "posts.category_id={$_GET[sort]} AND posts.status='published'";
       // 'posts.category_id='.$_GET[sort];
      
    }

        

   $date=xiu_fetch_all(sprintf("
      SELECT
      posts.id as id, posts.title,posts.created,posts.status,users.nickname as name,categories.slug as class
      FROM posts 
      inner join users on posts.user_id=users.id
      inner join categories on categories.id = posts.category_id
      where %s
      order by created desc
      LIMIT %d,%d
    ",$sort,(int)$tag,$size));
   //====================================================
   $total_count=(int)xiu_fetch_one("select count(1) as num from posts")['num'];
   $total_pages=(int)ceil($total_count/$size);
 ?>
 <?php 
     $visable = 5;
        $visable = $total_pages > $visable ? $visable : $total_pages;
        $region = ceil(($visable-1)/2);
        $begion = ($indexs - $region)>0? ($indexs - $region) : 1;
        // $end = ($begion + 2*$region)>$total_pages? $total_pages : ($begion + 2*$region) ;
        if(($begion + 2*$region)>$total_pages){
          $end=$total_pages;
          $begion=$total_pages-2*($visable-1)/2;
        }else{
          $end=$begion+2*$region;
        }
  ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>文章</title>
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
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        
        <form class="form-inline" action='<?php echo $_SERVER['PHP_SELF'] ?>' method='get'>
          <select name="sort" class="form-control input-sm">
            <option value="0">所有分类</option>
            <?php foreach($categories as $item) : ?>
              <option value="<?php echo $item[id] ?>"<?php echo $item[id]===$_GET[sort] ? 'selected = "selected"' : "" ; ?>>
                <?php echo $item[name] ?>
              </option>
            <?php endforeach ?>
           <!--   -->
          </select>
          <select name="status" class="form-control input-sm">
            <option value="0">所有状态</option>
            <?php foreach($array as $key => $value) : ?>
                 <option value="<?php echo $key ?>" <?php echo $key===$_GET[status] ? 'selected = "selected"' : "" ; ?>>
                   <?php echo $value ?>
                 </option>
            <?php endforeach ?>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>

        <ul class="pagination pagination-sm pull-right">
            <?php if($indexs > 1) : ?>
              <li><a href="?<?php echo 'indexs='.($indexs-1) ?>">上一页</a></li>
            <?php endif ?>

            <?php for($i=$begion ; $i<=$end ; $i++) : ?>
              <li <?php echo $i == $indexs? 'class="active"' : ""; ?>><a href="?<?php echo 'indexs='.$i ?>"><?php echo $i ?></a></li>
            <?php endfor ?>
          
            <?php if($indexs<$total_pages) :?>
              <li><a href="?<?php echo 'indexs='.($indexs+1) ?>">下一页</a></li>
            <?php endif ?>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" ></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($date as  $item) : ?>
              <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td><?php echo $item['title'] ?></td>
            <td><?php echo $item['name'] ?></td>
            <td><?php echo $item['class'] ?></td>
            <td class="text-center"><?php echo  retime($item['created']); ?></td>
            <td class="text-center"><?php echo state($item['status']); ?></td>
            <td class="text-center">
              <a href="post-add.php?id=<?php echo $item['id'] ?>" class="btn btn-default btn-xs">编辑</a>
              <a data-href="post-delete.php?id=<?php echo $item['id'] ?>" class="btn btn-danger btn-xs posts" >删除</a>
            </td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

   <?php $current_index = posts ?>
  <?php include 'inc/sider.php' ?>
  
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/layer/layer.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
      $('.posts').click(function(){
        let $postUrl=$(this).attr('data-href')
        layer.confirm('确定删除？', {
              btn: ['确定','取消'] //按钮
            }, function(){
              location.href=$postUrl;
            }, function(){
              // layer.msg('也可以这样', {
              //   time: 20000, //20s后自动关闭
              //   btn: ['明白了', '知道了']
              // })
            })
      })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
