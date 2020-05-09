<?php require '../admin/api/function.php' ?>
<?php 
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$content = xiu_fetch_one(sprintf("select * from posts where id=%d",$id));
	}
   $title = xiu_fetch_all('SELECT name,id FROM categories');
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/static/assets/css/reset.css">
    <link rel="stylesheet" href="/static/assets/css/base.css">
    <link rel="stylesheet" href="/static/assets/css/index.css">
    <title><?php echo $content['slug'] ?>-日记</title>
</head>
<body>
<div class="container">
  
  
    <div class="header">
      <div class="header-main fx-cell">
        <div class="fx-cell fx-ai-ct fx-cell-bd">
          <ul class="fx-cell offset-right-4">
            <li>
              <a class="" href="/">首页</a>
            </li>
            
             <?php foreach ($title as $value) : ?>
              <li>
                <a <?php if(isset($_GET['class'])) : ?>
                      <?php echo $value['id']==$_GET['class'] ? 'class="active"' : "" ;  ?>
                   <?php endif ?>
                  href="/static/index.php?class=<?php echo $value['id'] ?>"><?php echo $value['name'] ?></a>
              </li>
            <?php endforeach ?>
            
          </ul>
        </div>
      </div>
    </div>
  
  <main class="main">
    <section class="main-content view">
      
    <article class="article">
        <div class="article-header">
            <h1 class="title"><?php echo $content['slug'] ?>-日记</h1>
            <p class="article-info">
                <span class="time offset-right-4">发布时间：<?php echo $content['created'] ?></span>
            </p>
        </div>
        <div class="article-body">
            <?php echo $content['content'] ?>
        </div>
    </article>

    </section>
  </main>
  
    <script src="\static\assets\vendors\highlight\highlight.pack.js"></script>
    <link rel="stylesheet" href="\static\assets\vendors\highlight/atom-one-dark.css">
    <script>
      let blocks = document.querySelectorAll("pre");
      blocks.forEach(block => {
        hljs.highlightBlock(block);
      });
    </script>
  

</div>
</body>
</html>