<?php

// Displays the page which is used by the admin to edit a team member's profile
class EditMemberView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
                $html .= '<section>'."\n";
                
                $html .= '<aside>'."\n";
                $html .= '</aside>'."\n";
            
                
                $html .= '<div id="pageContent">'."\n";

                if($_SESSION['userType'] != 'admin') {
                        $html .= '<p class="error">Access to this page is restricted</p>'."\n";
                        $html .= '<p class="button"><a href="index.php?page=home">Return home</a></p>'."\n";
                        $html .= '</div>'."\n";
                        $html .= '</section>'."\n";

                        return $html;
                }

                if($_POST['editSubmit']) {

                        $updateMember = $this -> model -> processUpdateMember();
                        //print_r($updateMember);
                        if($updateMember['ok'] == true) {
                                header('Location: index.php?page=about');
                        } else {
                                $html .= '<p class="error">'.$updateMember['msg'].'</p>'."\n";
                        }

                }

                if($_POST['submit'] != 'Update') {
                        $member = $this -> model -> getProfile($_POST['profID']);
                } else {

                        foreach($_POST as $post) {
                                $post = stripslashes($post);
                        }
                        

                        $member = $_POST;
                }

                $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">'."\n";

                $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";

                $html .= '<input class="long" type="hidden" name="profID" value="'.$member['profID'].'" />'."\n";
                $html .= '<input class="long" type="text" name="profName" value="'.htmlentities(stripslashes($member['profName']), ENT_QUOTES).'" placeholder="Member Name" />'."\n";
                $html .= '<p class="error">'.$updateMember['profName'].'</p>'."\n"; 

                $html .= '<textarea class="long" name="profContent" rows="7" cols="20" placeholder="Write something about your team member">'.htmlentities(stripslashes($member['profContent']),ENT_QUOTES).'</textarea>'."\n";
                $html .= '<p class="error">'.$updateMember['profContent'].'</p>'."\n"; 

                $html .= '<label class="long" for="profImg">Change the display picture</label>'."\n";
                $html .= '<input class="long" type="file" name="profImg" id="profImg"/>'."\n";

                $html .= '<input class="long" type="text" name="profEmail" value="'.htmlentities(stripslashes($member['profEmail'])).'" placeholder="Email Address" />'."\n";
                $html .= '<p class="error">'.$updateMember['profEmail'].'</p>'."\n"; 

                $html .= '<input class="button" type="submit" name="editSubmit" value="Update" />'."\n";

                $html .= '</form>'."\n";
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;
	}

}





?>