<?php
require 'vendor/autoload.php';

use ThauEx\SimpleHtmlDom\SHD;

// get post url
function crawler($page = 0)
{	
	if($page == 2)
		die();

	$url = 'http://rumaysho.com/arsip-artikel';
	$apiUrl = 'http://localhost/dop/rumaysho-api/';
	$currentUrl = ( $page != 0 ) ? $url."//page//".$page : $url;
	SHD::$fileCacheDir = "tmp";
	$html = SHD::fileGetHtml($currentUrl);
	foreach($html->find('div.list_item') as $element) 
		foreach ($element->find('h3 a') as $key)
	       echo '<li>'.$key->href.'</li>';

	$paging = $html->find('div.pagination',0);
	$lastpaging = $paging->lastChild()->plaintext;
	if($lastpaging == 'LAST');
		$page++;
		crawler($page);

}
echo "<ol>";
crawler();
echo "</ol>";