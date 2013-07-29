<?php

// Displays the page which is used by the admin to delete a member
class deleteMemberView extends View {
    
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

                if($_POST['submitYes']) {

                        $deleteMember = $this -> model -> processDeleteMember();

                        if($deleteMember['ok'] == true) {
                                header('Location: index.php?page=about');
                        } elseif($deleteMember['ok'] == false) {
                                $html .= '<p class="error">There was an error removing the profile from the database. Please return to this page and try again.</p>'."\n";
                                $html .= '<p class="button"><a href="index.php?page=home">Return home</a></p>'."\n";
                                $html .= '</div>'."\n";
                                $html .= '</section>'."\n";
                                return $html;
                        }

                } elseif($_POST['submitNo']) {
                        header('Location: index.php?page=about');
                }


                $p = $this -> model -> getProfile($_POST['profID']);
        
                $html .= '<article>'."\n";
                $html .= '<h1>'.stripslashes($p['profName']).'</h1>'."\n";
                $html .= '<p>'.stripslashes($p['profContent']).'</p>'."\n";
                $html .= '<p><i>'.stripslashes($p['profEmail']).'</i></p>'."\n";
                $html .= '<img src="images/profiles/'.$p['profImg'].'" alt="'.$p['profTitle'].'" />'."\n";

                $html .= '<form class="inline" method="post" action="index.php?page=deleteMember">
                <input type="hidden" name="profID" value="'.$p['profID'].'" />
                <input type="hidden" name="profImg" value="'.$p['profImg'].'" />
                <label class="long inline" for="submitYes">Are you sure you want to delete this profile?</label>
                <input type="submit" class="button" name="submitYes" value="Yes" />
                <input type="submit" class="button" name="submitNo" value="No" />
                </form>'."\n";
                
                

                $html .= '</article>'."\n";

                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;
	} 

}





?>