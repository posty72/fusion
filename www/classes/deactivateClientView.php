<?php

class DeactivateAcc extends View {


	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>';
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";

        if(!$this -> model -> adminLoggedIn) {
        	$html .= '<p class="error">You do not have access to this page</p>';
	        $html .= '</div>';
	        $html .= '</section>';
	        return $html;
        }

        if(!$_GET['id']) {
        	$html .= '<p class="error">There was an error finding the user. Please try coming back to this page again.</p>';
	        $html .= '</div>';
	        $html .= '</section>';
	        return $html;
        }

        $html .= '</div>';
        $html .= '</section>';

        return $html;


	}


}










?>