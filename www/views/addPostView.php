<?php

// Displays the page which is used by the admin to add a post
class AddPostView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
                $html .= '<section>'."\n";
                
                $html .= '<aside>'."\n";
                $html .= '</aside>'."\n";
            
                
                $html .= '<div id="pageContent">'."\n";

                if(!$this -> model -> adminLoggedIn || $this -> model -> userLoggedIn) {
                	$html .= '<p class="error">You do not have access to this page</p>'."\n";
        	        $html .= '</div>'."\n";
        	        $html .= '</section>'."\n";
        	        return $html;
                }

                if($_POST['submit']) {
                	$post = $this -> model -> processAddPost();

                        if($post['ok'] == false) {
                                //echo '$post = '."\n";
                                //print_r($post);
                                $html .= '<p class="error">'.$post['msg'].'</p>'."\n";
                        } else {
                                header('Location: index.php?page=news');
                        }
                }
                
                $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">'."\n";

                $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";
                $html .= '<input class="long" type="text" name="postName" id="postName" placeholder="Post Name" value="'.htmlentities(stripslashes($_POST['postName'])).'"/>'."\n";
                $html .= '<p id="postNameMsg" class="error">'.$post['postName'].'</p>'."\n";


                $html .= '<textarea class="long" name="postContent" rows="7" cols="20" placeholder="Content">'.htmlentities(stripslashes($_POST['postContent'])).'</textarea>'."\n";
                $html .= '<p class="error">'.$post['postContent'].'</p>'."\n";

                $html .= '<select class="long" name="postMediaType" id="postMediaType">'."\n";

                if($_POST['postMediaType'] == 'video') {
                        $html .= '<option value="video" selected>Youtube URL</option>'."\n";
                } elseif($_POST['postMediaType'] == 'image') {
                        $html .= '<option value="image" selected>Image</option>'."\n";
                } elseif($_POST['postMediaType'] == 'pdf') {
                        $html .= '<option value="pdf" selected>Document</option>'."\n";
                } else {
                        $html .= '<option value="null" disabled selected>Select the type of media</option>'."\n";
                }

                $html .= '<option value="none">None</option>'."\n";
                $html .= '<option value="video">Youtube Embed</option>'."\n";
                $html .= '<option value="image">Image</option>'."\n";
                $html .= '<option value="pdf">Document</option>'."\n";
                $html .= '</select>'."\n";

                $html .= '<label class="long" for="postMediaFile">Upload File</label>'."\n";
                $html .= '<input class="long" type="file" name="postMediaFile" id="postMediaFile" />'."\n";
                $html .= '<input class="long" type="text" name="postMediaText" id="postMediaText" placeholder="Youtube Link" value="'.htmlentities(stripslashes($_POST['postMediaText'])).'"/>'."\n";
                $html .= '<p class="error">'.$post['postMediaType'].'</p>'."\n";

                $html .= '<input type="submit" class="submitButton" name="submit" value="Add Post"/>'."\n";

                $html .= '</form>'."\n";

                
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;




	}


}





?>