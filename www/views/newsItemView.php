<?php

// Displays a single news item defined in the GET array
class NewsItemView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		if(!$_GET['id']) {
			$html .= '<h1 class="pageHeader">The page could not be founcd. Please try again.</h1>'."\n";
			$html = '<section>'."\n";
			$html .= '<aside></aside>'."\n";
			$html .= '<div id="pageContent">'."\n";
			$html .= '<p class="button"><a href="index.php?page=news">Return to news feed.</a></p>'."\n";
			$html .= '</div>'."\n";
			$html .= '</section>'."\n";
			return $html;
		}

		$item = $this -> model -> getNewsItem($_GET['id']);

		$html .= '<h1 class="pageHeader">'.$item['postTitle'].'</h1>'."\n";

		$html .= '<section>'."\n";
		$html .= '<aside>'."\n";
        $html .= $this -> displayHeadlines();
		$html .= '</aside>'."\n";
		$html .= '<div id="pageContent">'."\n";

		$html .= '<article>'."\n";
        
        // Determine what type of media is being displayed
        if($item['postMediaType'] == 'image') {
            $html .= '<img src="images/news/'.$item['postMedia'].'" alt="'.$item['postTitle'].'" />'."\n";
        } elseif($item['postMediaType'] == 'video') {
            $html .= '<iframe src="'.$item['postMedia'].'" frameborder="0" allowfullscreen></iframe>'."\n";
        } elseif($item['postMediaType'] == 'pdf') {
            $html .= '<p class="document"><a href="files/pdfs/'.$item['postMedia'].'" target="blank">Click here to download the PDF document</a></p>'."\n";
        } elseif($item['postMediaType'] == 'plain') {
            $html .= ''."\n";
        }

	        if($_SESSION['userType'] == 'admin') {
	            $html .= '<form class="inline" method="post" action="index.php?page=deletePost">
	            <input type="hidden" name="postID" value="'.$item['postID'].'" />
	            <input type="submit" class="button" name="submit" value="Delete" />
	            </form>'."\n";
	            $html .= '<form class="inline" method="post" action="index.php?page=editPost&id='.$item['postID'].'">
	            <input type="hidden" name="postID" value="'.$item['postID'].'" />
	            <input type="submit" class="button" name="submit" value="Edit" />
	            </form>'."\n";
		}
        $html .= '<p>'.$item['postContent'].'</p>'."\n";

		$html .= '</article>'."\n";
		
		$html .= '<p class="button clear-all"><a href="index.php?page=news">Return to news feed.</a></p>'."\n";


		$html .= '</div>'."\n";
		$html .= '</section>'."\n";

		return $html;

	}
}

?>