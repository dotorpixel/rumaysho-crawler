<?php
require 'vendor/autoload.php';

use ThauEx\SimpleHtmlDom\SHD;

// get post url
function crawler($page = 0)
{	
	$url = 'http://rumaysho.com/arsip-artikel';
	$currentUrl = ( $page != 0 ) ? $url."//page//".$page : $url;
	SHD::$fileCacheDir = "tmp";
	$html = SHD::fileGetHtml($currentUrl);
	foreach($html->find('div.list_item') as $element) 
		foreach ($element->find('h3 a') as $key)
	       getPost($key->href);

	if( $paging = $html->find('div.pagination',0) )
		$lastpaging = $paging->lastChild()->plaintext;

	if($lastpaging == 'LAST'){
		$page++;
		crawler($page);
	}
	die();
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
	$category = $post->find('p.post-meta span.meta-cat a',0);
	$content = $post->find('div.post_content',0);

	//create array of data to be posted
	$postData['title'] = $title->plaintext;
	$postData['date'] = $date->plaintext;
	$postData['author'] = $author->plaintext;
	$postData['category'] = $category->plaintext;
	$postData['content'] = $content->innertext;
	$postData['url'] = $url;
	savePost($postData);
}

function savePost($postData)
{
	$apiUrl = 'localhost/dop/rumaysho-api/article';

	//traverse array and prepare data for posting (key1=value1)
	foreach ( $postData as $key => $value) {
	    $postItems[] = $key . '=' . $value;
	}
	 
	//create the final string to be posted using implode()
	$postString = implode ('&', $postItems);
	 
	//create cURL connection
	$curlConnection = curl_init($apiUrl);
	 
	//set options
	curl_setopt($curlConnection, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($curlConnection, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlConnection, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curlConnection, CURLOPT_FOLLOWLOCATION, 1);
	 
	//set data to be posted
	curl_setopt($curlConnection, CURLOPT_POSTFIELDS, $postString);
	 
	//perform our request
	$result = curl_exec($curlConnection);
	 
	//show information regarding the request
	// echo '<pre>';
	// print_r(curl_getinfo($curlConnection));
	// echo curl_errno($curlConnection) . '-' . 
	// curl_error($curlConnection);
	 
	//close the connection
	curl_close($curlConnection);
}

crawler(119);