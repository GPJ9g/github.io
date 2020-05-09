<?php require dirname(__File__).'/admin/api/function.php' ?>
<?php 
	$content = xiu_fetch_all('select id,slug,content,title from posts order by created desc');
 ?>
<?php
header('Content-Type: application/xml');
?>
<?xml version="1.1" encoding="utf-8"?>
   <channel>
		<?php foreach ($content as $item): ?>
			<item>
				<title><?php echo $item['slug'] ?></title>
				<link>/static/post/cs.php?id=<?php echo $item['id'] ?></link>
				<description><?php echo $item['title'] ?></description>
			</item>
		<?php endforeach ?>
   </channel>