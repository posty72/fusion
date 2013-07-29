<?php

abstract class View {
    
    // Set variables
    protected $pageInfo;
    protected $model;
    
    // Create the constructor
    public function __construct($info, $model) {
        $this -> pageInfo = $info;
        $this -> model = $model;
    }
    
    public function displayPage() {
        
        $this -> model -> checkUserSession();
        $html = $this -> displayHeader();
        $html .= $this -> displayContent();
        $html .= $this -> displayFooter();
        
        return $html;
    }
    
    // Instantiate the displayContent function
    abstract protected function displayContent();
    
    // Returns the header HTML
    private function displayHeader() {
        
        $html = '<!DOCTYPE html>'."\n";
        $html .= '<html>'."\n";
        $html .= '<head>'."\n";
        $html .= '<meta charset="utf-8" />'."\n";
        $html .= '<meta name="description" content="'.$this -> pageInfo['pageDescription'].'" />'."\n";
        $html .= '<meta name="keywords" content="'.$this -> pageInfo['pageKeywords'].'" />'."\n";
        $html .= '<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />'."\n";
        #$html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge" />'."\n";
        $html .= '<title>Fusion Networks | '.$this -> pageInfo['pageTitle'].'</title>'."\n";
        $html .= '<link rel="icon" type="image/png" href="graphics/icon.png" />'."\n";
        
        $html .= '<!-- Stylesheets -->'."\n";
        $html .= '<link rel="stylesheet" type="text/css" href="css/base.css" />'."\n";
        $html .= '<!-- IE Conditionals -->'."\n";
        
        $html .= '<!--[if lt IE 9]>'."\n";
        $html .= '<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>'."\n";
        $html .= '<link type="text/css" rel="stylesheet" href="css/ie.css" />'."\n";
        $html .= '<![endif]-->'."\n";
        
        if($_GET['page'] == 'home' && !$_SESSION['userName']) {
            $html .= '<link rel="stylesheet" href="css/home.css" />'."\n";
        }
        
        $html .= '<!-- Javascript -->'."\n";
        
        $html .= '<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>'."\n";
        if($_GET['page'] == 'home') {
            $html .= '<script type="text/javascript" src="js/parallax.js"></script>'."\n";
        }
        $html .= '</head>'."\n";
        $html .= '<body>'."\n";
        
        $html .= '<header>'."\n";
        
        $html .= '<h1><a href="index.php?page=home">Fusion Networks</a></h1>'."\n";
        
        // Navigation loop
        $html .= $this -> displayNav();
        
        $html .= '</header>'."\n";
        
        
        return $html;
    }
    
    // Returns the footer HTML
    private function displayFooter() {

        $links = array(
                'home',
                'news',
                'about',
                'contact',
                'login',
                'rss', 
                'siteMap'
            );
        
        $html = '<footer>'."\n";
        $html .= '<div>'."\n";
        $html .= '<ul>'."\n";

        foreach($links as $link) {

            if($link == 'login' && isset($_SESSION['userType'])) {
                $link = 'logout'."\n";
            }

            $html .= '<li><a href="index.php?page='.$link.'">'.ucfirst($link).'</a></li>'."\n";

        }

        $html .= '</ul>'."\n";
        $html .= '</div>'."\n";
        $html .= '<div>'."\n";
        $html .= '<h4>&copy; Fusion Networks 2013</h4>'."\n";
        //$html .= '<p><a href="privacy-policy.html">Privacy Policy</a></p>'."\n";
        //$html .= '<p><a href="terms-of-use.html">Terms of Use</a></p>'."\n";
        $html .= '</div>'."\n";
        $html .= '</footer>'."\n";
        $html .= '<script type="text/javascript" src="js/script.js"></script>'."\n";
        
        return $html;
    }
    
    // Returns the HTML for the navigation controls
    private function displayNav() {
        
        $links = array(
            'home',
            'news',
            'about',
            'contact'
        );
        
        
        //Navbar
        $html = '<nav>'."\n";
        $html .= '<ul>'."\n";
        
        foreach($links as $link) {
            
            $html .= '<li';

            if($link == $_GET['page']) {
                $html .= ' class="active" ';
            }

            $html .= '><a href="index.php?page='.$link.'">'."\n";
        
            if($_SESSION['userType'] == 'admin' && $link == 'home') {
                $html .= 'Control Panel'."\n";
            }elseif($_SESSION['userType'] == 'client' && $link == 'home') {
                $html .= 'Your Jobs'."\n";
            } else {
                $html .= ucfirst($link);
            }

            $html .= '</a></li>'."\n";
            
        }
        
        $html .= '</ul>'."\n";
        $html .= '</nav>'."\n";
        
        if($_SESSION['userActive'] == false) {
            $html .= '<h2><a href="index.php?page=login">Login</a></h2>'."\n";
        } elseif($this -> model -> adminLoggedIn || $this -> model -> userLoggedIn) {
            $html .= '<h2><a href="index.php?page=logout">Logout</a></h2>'."\n";
        }
        //print_r($_SESSION);

        return $html;
    }


    // Display's headlines on news pages
    public function displayHeadlines() {
        
        
        $this -> newsHeadlines = $this -> model -> getNewsHeadlines();
        
        /*echo '<pre>'."\n";
        print_r($this -> newsHeadlines);
        echo '</pre>'."\n";*/
        
        $html .= '<aside>'."\n";
        $html .= '<div id="all-stories">'."\n";
        
        if(!is_array($this -> newsHeadlines) || $this -> newsHeadlines == false) {
            $html .= '<h2>No more stories</h2>'."\n";
            $html .= '</div>'."\n";
            $html .= '</aside>'."\n";
            return $html;
        } else {
            $html .= '<h2>More News</h2>'."\n";
        }
        $html .= '<ul>'."\n";
        
        // Loop through all news stories
        foreach($this -> newsHeadlines as $headline) {
            $html .= '<li><a href="index.php?page=newsItem&amp;id='.$headline['postID'].'">'.$headline['postTitle'].'</a></li>'."\n";
        }
        
        $html .= '</ul>'."\n";
        $html .= '</div>'."\n";
        $html .= '</aside>'."\n";
        
        return $html;
        
    }
    
}



?>