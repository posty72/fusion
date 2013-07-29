<?php

class RSSView {

	private $model;
	private $feeds = array();

	public function __construct($model) {
		$this -> model = $model;
	}

	public function displayPage() {

		$this -> generateFeeds();

		$html = $this -> displayFeeds();
		return $html;
	}

	private function generateFeeds() {

		$newsItems = $this -> model -> getNewsItems( 0, 'all');

		foreach($newsItems as $item) {
			$this -> feeds[] = array(	'title' => $item['postTitle'],
										'link' => 'http://joshua.post.yoobee.net.nz/_Assignments/WE06/Fusion_Networks/www/index.php?page=newsItem&amp;id='.$item['postID'],
										'description' => substr($item['postContent'], 0, 350),
										'pubDate' => $item['postDate']
										);
		}

		print_r($this -> feeds);

	}

	private function displayFeeds() {

		$doc = new DOMDocument('1.0', 'UTF-8');

		$rss = $doc -> createElement('rss');

		$rss -> setAttribute('version', '2.0');
		$doc -> appendChild($rss);

		$channel = $doc -> createElement('channel');
		$rss -> appendChild($channel);

		$chTitle = $doc -> createElement('title', 'Fusion Networks');
		$chLink = $doc -> createElement('link', 'http://joshua.post.yoobee.net.nz/_Assignments/WE06/Fusion_Networks/www/index.php');
		$chDescription = $doc -> createElement('description', 'News from Fusion Networks');
		$channel -> appendChild($chTitle);
		$channel -> appendChild($chLink);
		$channel -> appendChild($chDescription);

		foreach($this -> feeds as $post) {

			$item = $doc -> createElement('item');
			$channel -> appendChild($item);

			$itemTitle = $doc -> createElement('title', $post['title']);
			$itemLink = $doc -> createElement('link', $post['link']);
			$itemDesc = $doc -> createElement('description', $post['description']);
			$itemDate = $doc -> createElement('pubDate', $post['pubDate']);
			$item -> appendChild($itemTitle);
			$item -> appendChild($itemLink);
			$item -> appendChild($itemDesc);
			$item -> appendChild($itemDate)
		}

		$doc -> formatOutput = true;

		$xmlStr = $doc -> saveXML();
		$doc -> save('rsFeeds.rss');

		header('Content-type: text/xml');
		return $xmlStr;
	}


}