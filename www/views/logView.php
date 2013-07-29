<?php

// Displays the page which users login and logout from
class LogView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";

        $html .= '<div id="pageContent">'."\n";

        if($this -> model -> userLoggedIn) {
            header('Location: index.php?page=home');
        } else {
        	$html .= $this -> displayLoginForm();
        }
        
        $html .= '<p class="error">'.$this -> model -> loginMsg.'</p>'."\n";
        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;
        
        
    }
    
    // Returns the HTML form which logs a user in
    private function displayLoginForm() {

        
        $html .= '<form id="login" method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
        
        $html .= '<input class="long" type="text" name="userName" id="userName" placeholder="Username" value="'.htmlentities(stripslashes($_POST['userName'])).'"/>'."\n";
        $html .= '<br />'."\n";
        
        $html .= '<input class="long" type="password" name="userPassword" placeholder="Password" id="userPassword" />'."\n";
        $html .= '<br />'."\n";
        $html .= '<input type="submit" class="submitButton" name="loginSubmit" id="loginSubmit" value="Login" />'."\n";
        
        $html .= '</form>'."\n";

        return $html;
    }
    
    
}



?>