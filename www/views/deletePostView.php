<?php

class DeletePostView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";

        if($_SESSION['userType'] != 'admin') {
        	$html .= '<p class="error">You do not have access to this page</p>'."\n";
	        $html .= '</div>'."\n";
	        $html .= '</section>'."\n";
	        return $html;
        }

        if(!$_POST['postID']) {
            $html .= '<p class="error">Something went wrong finding the post you wish to delete. Please return to this page again.</p>'."\n";
            $html .= '<p class="button"><a href="index.php?page=home">Return to the Control Panel</a></p>'."\n";
        
            $html .= '</div>'."\n";
            $html .= '</section>'."\n";
            return $html;
        }

        //print_r($_POST);

        if($_POST['submitYes']) {
        	$deletePost = $this -> model -> processDeletePost();
            if($deletePost['ok']) {
                header('Location: index.php?page=news');
            } else {
                $html .= '<p class="error">There was an error deleting the post. Please return later and try again.</p>'."\n";
            }
        } elseif($_POST['submitNo']) {
            header('Location: index.php?page=news');
        }


        //echo $deletePost;


        $item = $this -> model -> getNewsItem($_POST['postID']);


        $html .= '<article class="news-item">'."\n";
        $html .= '<h1>'.stripslashes($item['postTitle']).'</h1>'."\n";
        $html .= '<p>'.stripslashes($item['postContent']).'</p>'."\n";
        
        // Determine what type of media is being displayed
        if($item['postMediaType'] == 'image') {
            $html .= '<img src="images/news/'.$item['postMedia'].'" alt="'.$item['postTitle'].'" />'."\n";
        } elseif($item['postMediaType'] == 'video') {

            if($this -> model -> device == 'desktop') {
                $html .= '<iframe class="no-mobile" src="http://www.youtube.com/embed/'.$item['postMedia'].'" allowfullscreen></iframe>'."\n";
            } else {
                $html .= '<a href="http://www.youtube.com/watch?v='.$item['postMedia'].'" target="blank"><img src="http://img.youtube.com/vi/'.$item['postMedia'].'/0.jpg" alt="'.$item['postTitle'].'"/></a>';
            }
        } elseif($item['postMediaType'] == 'pdf') {
            $html .= '<p class="document" ><a href="files/pdfs/'.$item['postMedia'].'" target="blank">Click here to download the PDF document</a></p>'."\n";
        } elseif($item['postMediaType'] == 'plain') {
            $html .= ''."\n";
        }

        if($_SESSION['userType'] == 'admin') {
            $html .= '<p>Are you sure you want to delete this post?</p>'."\n";
            $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
            <input type="hidden" name="postID" value="'.$item['postID'].'" />
            <input type="submit" class="button" name="submitYes" value="Yes" />
            <input type="submit" class="button" name="submitNo" value="No" />
            </form>'."\n";
        }
        
        $html .= '</article>'."\n";

        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;




	}


}





?>