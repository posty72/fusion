<?php

// Displays the page when the page cannot be found
class ErrorView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
    	
        $html .= '<h1 class="pageHeader">Error 404 - Page Not Found</h1>'."\n";
        
        $html .= '<section>'."\n";
        $html .= '<div id="pageContent">'."\n";
        $html .= '<p>This page does not exist. Please <a class="links" href="index.php?page=home">return to the home page.</a></p>'."\n";
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";


        return $html;
    }
    
    
    
    
}

?>