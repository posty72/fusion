<?php

// Displays a unigue page that activates a clients account using a token in the GET array
class ActivateAccountView extends View {
    
    // Returns HTML for the browser
	protected function displayContent() {


		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";

        $html .= '<div id="pageContent">'."\n";

        //print_r($_GET);

        if($_GET['token']) {

	        $activated = $this -> model -> activateAccount($_GET['token']);

	        if($activated == true) {
	        	$html .= '<h2>Your account has been successfully activated</h2>'."\n";
	        	$html .= '<p>Now you can login and view the jobs associated with your account</p>'."\n";
	        	$html .= '<p class="button"><a href="index.php?page=login">Login</a></p>'."\n";
	        } else {
	        	$html .= '<h2>Your account has not been activated</h2>'."\n";
	        	$html .= '<p>Please make sure you followed the correct link. If you cannot solve this problem, use your contact form or give us a call.</p>'."\n";
	        }
	    } else {
	    	$html .= '<h2>Permission Denied</h2>'."\n";
	    	$html .= '<p class="button"><a href="index.php">Return to the home page</a></p>'."\n";
	    }
        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

		return $html;


	}


}













?>