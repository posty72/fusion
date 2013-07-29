<?php

// Displays the page which is used by the admin to add a member
class addMemberView extends View {
    
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

                if($_POST['submit']) {

                        // Start the creation process
                        $member = $this -> model -> processAddMember();

                        if($member['ok'] == false) {
                                $msg = $member;
                        }

                        if($member['ok'] == true) {
                                header('Location: index.php?page=about');
                        }

                }


                $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">'."\n";

                $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";

                $html .= '<input class="long" type="text" name="profName" value="'.htmlentities(stripslashes($_POST['profName']),ENT_QUOTES).'" placeholder="New Member\'s Name" />'."\n";
                $html .= '<p class="error">'.$msg['profName'].'</p>'."\n"; 

                $html .= '<textarea class="long" name="profContent" rows="7" cols="20" placeholder="Write something about your new team member">'.htmlentities(stripslashes($_POST['profContent']),ENT_QUOTES).'</textarea>'."\n";
                $html .= '<p class="error">'.$msg['profContent'].'</p>'."\n"; 

                $html .= '<label class="long" for="profImg">Add a picture of the employee</label>'."\n";
                $html .= '<input class="long" type="file" name="profImg" id="profImg" />'."\n";

                $html .= '<input class="long" type="text" name="profEmail" value="'.htmlentities(stripslashes($_POST['profEmail']),ENT_QUOTES).'" placeholder="Email Address" />'."\n";
                $html .= '<p class="error">'.$msg['profEmail'].'</p>'."\n"; 

                $html .= '<input class="button" type="submit" name="submit" value="Add Member" />'."\n";

                $html .= '</form>'."\n";

                
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;
	} 

}





?>