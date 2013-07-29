<?php

// Displays the pages wihich are publicly accessible
class SiteMapView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<div id="pageContent">'."\n";
        $html .= '<ul class="list-style-on">';
        $html .= '<li><a href="index.php?page=home">Home</a></li>';
        $html .= '<li><a href="index.php?page=news">News</a></li>';
        $html .= '<li><a href="index.php?page=about">About</a></li>';
        $html .= '<li><a href="index.php?page=contact">Contact</a></li>';
        $html .= '<li><a href="index.php?page=login">Login</a></li>';
        $html .= '</ul>';
        $html .= '</div>'."\n";
        
        $html .= '</section>'."\n";

        return $html;
    }

}

?>