<?php require dirname(__File__).'/admin/api/function.php' ?>
<?php 
//============================================================
//分页
//
  $indexs=empty($_GET['indexs'])? 1 : $_GET['indexs'];
  $size=8;
  $tag=($indexs-1)*$size;

//============================================================================================
//查询
//
    $where = "status='published'";
    if(isset($_GET['class'])){
      $where = $where.' AND category_id='.$_GET['class'];
    }
    $concet = xiu_fetch_all(sprintf('select * from posts where %s 
      order by created desc
      limit %d , %d',$where,(int)$tag,$size));
//===========================================================================================
//分页

  $total_count=(int)xiu_fetch_one(sprintf("select count(1) as num from posts where %s",$where))['num']; //页数
  $total_pages=(int)ceil($total_count/$size);

    $visable = 3;
    $visable = $total_pages > $visable ? $visable : $total_pages;
    $region = ceil(($visable-1)/2);
    $begion = ($indexs - $region)>0? ($indexs - $region) : 1;
    if(($begion + 2*$region)>$total_pages){
      $end=$total_pages;
      $begion=$total_pages-2*($visable-1)/2;
    }else{
      $end=$begion+2*$region;
    }

//===========================================================================================
//分类查询
//
    $title = xiu_fetch_all('SELECT name,id FROM categories');

//===========================================================================================
//自定义方法
/*
  这个是判断所属类别的  
 */
  function title_query($index){
    $title = xiu_fetch_all('SELECT name,id FROM categories');

    $title_class=[];
    foreach($title as $key => $value){
      $title_class[$key]=$value;
    }

    $index = $index-1;
    return empty($title_class[$index]['name'])? $index : $title_class[$index]['name'] ;
  }

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
    <link rel="stylesheet" href="/static/assets/css/slef.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>菜鸟日记</title>
</head>
<body>
<div class="container">
    <div class="header">
      <div class="header-main fx-cell">
        <div class="fx-cell fx-ai-ct fx-cell-bd">
          <ul class="fx-cell offset-right-4">
            <li>
              <a <?php echo empty($_GET['class']) ? 'class="active"' : "" ;  ?>  href="/static/index.php">首页</a>
            </li>

            <?php foreach ($title as $value) : ?>
              <li>
                <a <?php if(isset($_GET['class'])) : ?>
                      <?php echo $value['id']==$_GET['class'] ? 'class="active"' : "" ;  ?>
                   <?php endif ?>
                  href="<?php echo $_SERVER['PHP_SELF'] ?>?class=<?php echo $value['id'] ?>"><?php echo $value['name'] ?></a>
              </li>
            <?php endforeach ?> 
          </ul> 
        </div>
          <!-- 登录 -->
             <div class="s-in" style="background: #73C9E5; padding: 0 10px;" >
                    <a href="/static/admin/login.php" style="color: #FFF;font-size: 16px;">登录</a>
             </div>  
      </div>
       <!-- 搜索 -->     
      <div class="around">
          <div class="around-main">
            <i class="material-icons around-icon around-start">search</i>
            <input type="text" class="around-input" placeholder="搜索" />
            <i class="material-icons around-icon around-clear">clear</i>
          </div> 
        <div class="around-results"></div>
      </div>

    </div>
  
  <main class="main">
    <section class="main-content home">
      
  <div class="home">

    <?php if(isset($concet)&& $concet!=null) : ?>
    <ul class="post-list">
        <?php foreach ($concet as $value) : ?>
               <li class="post-list-item">
                  <a target="_blank" href="/static/post/cs.php?id=<?php echo $value['id'] ?>">
                    <div class="post-list-title"><?php echo $value['title'] ?></div>
                    <div class="post-list-description"><?php echo $value['slug'] ?></div>
                    <div class="post-list-info">
                      <span>所属类别：<?php echo title_query($value['category_id']) ?> </span>
                    </div>
                  </a>
                  <span class="post-list-time"><?php echo $value['created'] ?></span>
                </li>
          <?php endforeach ?> 
      </ul>
    <?php endif ?>
    
      <div class="pagination">
            <?php if($indexs > 1) : ?>
              <a href="?<?php echo 'indexs='.($indexs-1) ?><?php echo isset($_GET['class']) ? '&class='.$_GET['class'] : "" ; ?>">上一页</a>
            <?php endif ?>

            <?php for($i=$begion ; $i<=$end ; $i++) : ?>
               <a <?php echo $i == $indexs? 'class="active"' : ""; ?> href="?<?php echo 'indexs='.$i ?><?php echo isset($_GET['class']) ? '&class='.$_GET['class'] : "" ; ?>"><?php echo $i ?></a>
            <?php endfor ?>
          
            <?php if($indexs<$total_pages) :?>
              <a href="?<?php echo 'indexs='.($indexs+1) ?><?php echo isset($_GET['class']) ? '&class='.$_GET['class'] : "" ; ?>">下一页</a>
            <?php endif ?>
    </div>

  <script>
    const searchBtn = document.querySelector('.around-start')
    const searchClear = document.querySelector('.around-clear')
    const searchInput = document.querySelector('.around-input')
    const searchResults = document.querySelector('.around-results')


      // searchClear.style.display = 'none';

      let searchValue = '',
        arrItems = [],
        arrContents = [],
        arrLinks = [],
        arrTitles = [],
        arrResults = [],
        indexItem = [],
        itemLength = 0;

      let tmpDiv = document.createElement('div')

      let xhr = new XMLHttpRequest()
      xhr.open('get','/static/foot.php',true)
      xhr.send()
      xhr.onreadystatechange = function(){
        if(xhr.readyState==4 && xhr.status == 200){
          let xml = xhr.responseXML
            arrItems = xml.getElementsByTagName('item')
            itemLength = arrItems.length
            
            // 遍历并保存所有文章对应的标题、链接、内容到对应的数组中
            // 同时过滤掉 HTML 标签
            for (i = 0; i < itemLength; i++) {
              arrContents[i] = arrItems[i].getElementsByTagName('description')[0].
                  childNodes[0].nodeValue.replace(/<.*?>/g, '');
              arrLinks[i] = arrItems[i].getElementsByTagName('link')[0].
                  childNodes[0].nodeValue.replace(/<.*?>/g, '');
              arrTitles[i] = arrItems[i].getElementsByTagName('title')[0].
                  childNodes[0].nodeValue.replace(/<.*?>/g, '');    
            }  
          }
        }
    searchBtn.onclick = searchConfirm;

    // 清空按钮点击函数
    searchClear.onclick = function(){
      searchInput.value = ''
      searchResults.style.display = 'none'
      searchClear.style.display = 'none'
    }

    searchInput.oninput = function () {
      setTimeout(searchConfirm, 0)
    }
    searchInput.onfocus = function () {
      searchResults.style.display = 'block'
    }

    function searchConfirm() {
      if (searchInput.value == '') {
          searchResults.style.display = 'none'
          searchClear.style.display = 'none'
      } else if (searchInput.value.search(/^\s+$/) >= 0) {
          // 检测输入值全是空白的情况
          searchInit()
          let itemDiv = tmpDiv.cloneNode(true)
          itemDiv.innerText = '请输入有效内容...'
          searchResults.appendChild(itemDiv)
      } else {
          // 合法输入值的情况
          searchInit()
          searchValue = searchInput.value
          // 在标题、内容中查找
          searchMatching(arrTitles, arrContents, searchValue)
      }
    }

    // 每次搜索完成后的初始化
    function searchInit() {
        arrResults = []
        indexItem = []
        searchResults.innerHTML = ''
        searchResults.style.display = 'block'
        searchClear.style.display = 'block'
    }
    /*
     arr1 大标题 
     arr2 简介
     input 搜索的内容
     */
    function searchMatching(arr1, arr2, input) {
        // 忽略输入大小写
        input = new RegExp(input, 'i')
        // 在所有文章标题、内容中匹配查询值
        for (i = 0; i < itemLength; i++) {
            if (arr1[i].search(input) !== -1 || arr2[i].search(input) !== -1) {
                // 优先搜索标题
                let arr
                if (arr1[i].search(input) !== -1) {
                    arr = arr1
                } else {
                    arr = arr2
                }
                arr = arr2
                indexItem.push(i)  // 保存匹配值的索引
                let indexContent = arr[i].search(input)
                // 此时 input 为 RegExp 格式 /input/i，转换为原 input 字符串长度
                let l = input.toString().length - 3
                let step = 10
                
                // 将匹配到内容的地方进行黄色标记，并包括周围一定数量的文本
                arrResults.push(arr[i].slice(indexContent - step, indexContent) +
                    '<mark>' + arr[i].slice(indexContent, indexContent + l) + '</mark>' +
                    arr[i].slice(indexContent + l, indexContent + l + step))
            }
        }

        // 输出总共匹配到的数目
        let totalDiv = tmpDiv.cloneNode(true)
        totalDiv.innerHTML = '总匹配：<b>' + indexItem.length + '</b> 项'
        searchResults.appendChild(totalDiv)

        // 未匹配到内容的情况
        if (indexItem.length == 0) {
            let itemDiv = tmpDiv.cloneNode(true)
            itemDiv.innerText = '未匹配到内容...'
            searchResults.appendChild(itemDiv)
        }

        // 将所有匹配内容进行组合
        for (i = 0; i < arrResults.length; i++) {
            let itemDiv = tmpDiv.cloneNode(true);
            itemDiv.innerHTML = '<b>《' + arrTitles[indexItem[i]] +'》</b><hr />' + arrResults[i]
            // itemDiv.innerHTML = '<b>《' + arrTitles[indexItem[i]] +'》</b><hr />' + arrResults[i]
            itemDiv.setAttribute('onclick', 'changeHref(arrLinks[indexItem[' + i + ']])')
            searchResults.appendChild(itemDiv)
        }
    }

        function changeHref(href) {
            // 在当前页面点开链接的情况
            location.href = href
        }
  </script>

    </section>
  </main>
  

</div>
</body>
</html>