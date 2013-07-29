<?php

// Displays the page which is used by the admin to edit a post
class EditPostView extends View {
    
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

                if($_POST['submit'] == 'Update Post') {
                	$edit = $this -> model -> processEditPost();

                        if($edit['ok'] = true) {
                                header('Location: index.php?page=news');
                        } else {
                                $msg = $edit['msg'];
                        }
                }

                $item = $this -> model -> getNewsItem($_GET['id']);

                //print_r($_POST);
                //print_r($item);
                
                $html .= '<article class="news-item">'."\n";
                $html .= '<h1>'.$item['postTitle'].'</h1>'."\n";
                $html .= '<p class="error">'.$msg.'</p>'."\n";
                
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
                $html .= '</article>'."\n";
                
                $html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
                $html .= '<input type="hidden" name="postID" value="'.$_GET['id'].'" />'."\n";
                $html .= '<input class="long" type="text" name="postTitle" id="postTitle" placeholder="Post Name" value="'.htmlentities(stripslashes($item['postTitle'])).'"/>'."\n";
                $html .= '<p id="postNameMsg" class="error">'.$edit['postTitle'].'</p>'."\n";


                $html .= '<textarea class="long" name="postContent" rows="7" cols="20" placeholder="Content">'.htmlentities(stripslashes($item['postContent'])).'</textarea>'."\n";
                $html .= '<p class="error">'.$edit['postContent'].'</p>'."\n";

                $html .= '<input type="submit" class="submitButton" name="submit" value="Update Post"/>'."\n";

                $html .= '</form>'."\n";

                
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;




	}


}





?>