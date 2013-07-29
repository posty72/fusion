<?php

// Displays the page which is used by the logged-in users to change their password
class ChangePasswordView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";
        
        $html .= '<div id="pageContent">'."\n";

        if(!isset($_SESSION['userType']) || !isset($_SESSION['userID'])) {

            $html .= '<p class="error">You do not have access to this page</p>'."\n";
            $html .= '<p class="error"><a href="index.php?page=home">Return home</a></p>'."\n";
            $html .= '</div>'."\n";
            $html .= '</section>'."\n";
            return $html;
        }

        if($_POST['submit']) {

            $updatePassword = $this -> model -> processUpdatePassword();
            //print_r($updatePassword);

            if($updatePassword['ok']) {
                $html .= '<p>'.$updatePassword['msg'].'</p>'."\n";
                $html .= '<p class="button"><a href="index.php?page=home">Return to Your Jobs</a></p>'."\n";
                $html .= '</div>'."\n";
                
                $html .= '</section>'."\n";
                return $html;
            }


        }
        //echo $updatePassword['msg'];
        $html .= '<div class="error">'.$updatePassword['msg'].'</div>'."\n";

        $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
        $html .= '<input type="hidden" name="userID" value="'.$_SESSION['userID'].'" />'."\n";
        $html .= '<input type="hidden" name="userName" value="'.$_SESSION['userName'].'" />'."\n";
        $html .= '<input class="long" type="password" name="oldPassword" placeholder="Old Password" />'."\n";

        $html .= '<label class="long" for="newPassword">Choose a new password</label>'."\n";
        $html .= '<input class="long" type="password" name="newPassword" id="newPassword" placeholder="New Password" />'."\n";
        $html .= '<input class="long" type="password" name="confPassword" placeholder="Confirm New Password" />'."\n";
        $html .= '<input class="button" type="submit" name="submit" value="Change your password" />'."\n";
        $html .= '</form>'."\n";

        $html .= '</div>'."\n";
        
        $html .= '</section>'."\n";
        return $html;
    }
    
}



?>