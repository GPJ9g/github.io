<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <button id="test2">点击</button>
    <script src='\static\assets\vendors\jquery/jquery.min.js'></script>
    <script src='/static/assets/vendors/layer/layer.js'></script>
    <script>
            $('#test2').on('click', function(){
           layer.confirm('您是如何看待前端开发？', {
              btn: ['重要','奇葩'] //按钮
            }, function(){
              layer.msg('的确很重要', {icon: 1});
            }, function(){
              layer.msg('也可以这样', {
                time: 20000, //20s后自动关闭
                btn: ['明白了', '知道了']
              });
            });
          });
    </script>
</body>
</html>