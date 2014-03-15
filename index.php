<?php
require 'vendor/autoload.php';

use ThauEx\SimpleHtmlDom\SHD;

// get post url
function crawler($page = 0)
{	
	$url = 'http://rumaysho.com/arsip-artikel';
	$apiUrl = 'http://localhost/dop/rumaysho-api/';
	$currentUrl = ( $page != 0 ) ? $url."//page//".$page : $url;
	SHD::$fileCacheDir = "tmp";
	$html = SHD::fileGetHtml($currentUrl);
	foreach($html->find('div.list_item') as $element) 
		foreach ($element->find('h3 a') as $key)
	       getPost($key->href);

	$paging = $html->find('div.pagination',0);
	$lastpaging = $paging->lastChild()->plaintext;
	if($lastpaging == 'LAST');
		$page++;
		crawler($page);

}
// get post data
function getPost($url = '')
{
	SHD::$fileCacheDir = "tmp";
	$html = SHD::fileGetHtml($url);
	$post = $html->find('div.post',0);
	$title = $post->find('h2.post-title',0);
	$date = $post->find('p.post-meta span.date',0);
	$author = $post->find('p.post-meta span.author',0);
	$cat = $post->find('p.post-meta span.meta-cat a',0);
	$content = $post->find('div.post_content',0);
	echo '<li>';
	echo '<p>Title : '.$title->plaintext.'</p>';
	echo '<p>Date : '.$date->plaintext.'</p>';
	echo '<p>Author : '.$author->plaintext.'</p>';
	echo '<p>Category : '.$cat->plaintext.'</p>';
	echo '<p>Content :</p>';
	echo $content->innertext;
	echo '</li>';
}
echo '
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
';
echo "<ol>";
crawler();
echo "</ol>";
echo "
</body>
</html>";