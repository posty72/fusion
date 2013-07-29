<?php

// Displays all news items
// Items divided up using pagination
class NewsView extends View {
    
    // Set global variables
    public $newsItems;
    public $newsHeadlines;
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        // Show news headlines
        $html .= $this -> displayHeadlines();
        
        $html .= '<div id="pageContent">'."\n";
        
        // Pagination form
        $html .= '<form id="itemLimit" method="get" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
        $html .= '<label>Items to show:</label>'."\n";
        $html .= '<select name="limit">'."\n";
        $html .= '<option value="3"'."\n";
        if($_GET['limit'] == '3') {
            $html .= ' SELECTED'."\n";
        }
        $html .= '>3</option>'."\n";
        $html .= '<option value="10"'."\n";
        if($_GET['limit'] == '10') {
            $html .= ' SELECTED'."\n";
        }
        $html .= '>10</option>'."\n";
        $html .= '<option value="all"'."\n";
        if($_GET['limit'] == 'all') {
            $html .= ' SELECTED'."\n";
        }
        $html .= '>All</option>'."\n";
        $html .= '</select>'."\n";
        $html .= '<input class="submitButton" type="submit" name="submit" value="Go" />'."\n";
        $html .= '<input type="hidden" name="page" value="news" />'."\n";
        $html .= '</form>'."\n";

        if($_SESSION['userType'] == 'admin') {
            $html .= '<p class="button"><a href="index.php?page=addPost">Add a new post</a></p>'."\n";
        }

        $html .= $this -> displayNewsItems();
        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";
        
        
        return $html;
    }
    

    // Displays all the news items
    private function displayNewsItems() {
        
        if($_GET['limit']) {
            $limit = $_GET['limit'];
        } else {
            $limit = 3;
        }
        
        $this -> newsItems = $this -> model -> newsPagination($limit);
        
        //print_r($this -> newsItems);
        
        if($this -> newsItems != false) {
            
            // Loop through news items as limited by the form
            foreach($this -> newsItems as $item) {
                
                $html .= '<article class="news-item">'."\n";
                $html .= '<h1><a href="index.php?page=newsItem&amp;id='.$item['postID'].'">'.stripslashes($item['postTitle']).'</a></h1>'."\n";
                
                // Determine what type of media is being displayed
                if($item['postMediaType'] == 'image') {
                    $html .= '<img src="images/news/'.$item['postMedia'].'" alt="'.$item['postTitle'].'" />'."\n";
                } elseif($item['postMediaType'] == 'video') {

                    if($this -> model -> device == 'desktop') {
                        $html .= '<iframe class="no-mobile" src="http://www.youtube.com/embed/'.$item['postMedia'].'" allowfullscreen></iframe>'."\n";
                    } else {

                        $html .= '<div class="youtube-mobile">
                            <a href="http://www.youtube.com/watch?v='.$item['postMedia'].'" target="blank">
                                <img src="http://img.youtube.com/vi/'.$item['postMedia'].'/0.jpg" alt="'.$item['postTitle'].'"/>
                                <span class="play">
                                    <span></span>
                                </span>
                            </a>
                        </div>';

                    }

                    //$html .= '<iframe class="no-mobile" src="'.$item['postMedia'].'" allowfullscreen></iframe>'."\n";
                } elseif($item['postMediaType'] == 'pdf') {
                    $html .= '<p class="document" ><a href="files/pdfs/'.$item['postMedia'].'" target="blank">Click here to download the PDF document</a></p>'."\n";
                } elseif($item['postMediaType'] == 'plain') {
                    $html .= ''."\n";
                }

                if($_SESSION['userType'] == 'admin') {
                    $html .= '<form class="inline" method="post" action="index.php?page=deletePost">
                    <input type="hidden" name="postID" value="'.$item['postID'].'" />
                    <input type="submit" class="button" name="submit" value="Delete" />
                    </form>'."\n";
                    $html .= '<form class="inline" method="post" action="index.php?page=editPost&amp;id='.$item['postID'].'">
                    <input type="hidden" name="postID" value="'.$item['postID'].'" />
                    <input type="submit" class="button" name="submit" value="Edit" />
                    </form>'."\n";
                }
                if(strlen($item['postContent']) > 400) {
                    $item['postContent'] = substr($item['postContent'], 0, 350).'...'."\n";
                }

                $html .= '<p>'.stripslashes($item['postContent']).'</p>'."\n";
                
                $html .= '</article>'."\n";
            } 
            
        } else {
            $html .= '<article class="news-item">'."\n";
            $html .= '<p>No stories to show</p>'."\n";
            $html .= '</article>'."\n";
        }

        if(is_numeric($limit)) {
            $res = $this -> model -> getNewsItems('0', '999999');
            $amount = count($res);
            $pageAm = ceil($amount / $limit);
        }
        
        //echo $pageAm;
        if($pageAm > 1) {
            $html .= '<div id="paginate">'."\n";

            $html .= '<div id="paginate-numbers">'."\n";
            $html .= '<p>Page: </p>'."\n";

            for($i = 1; $i <= $pageAm; $i++) {

                if(!isset($_GET['pn']) && $i ==1) {
                    $html .='<p><strong><a href="index.php?page=news&amp;limit='.$limit.'&amp;pn='.$i.'">'.$i.'</a></strong></p>'."\n";
                } elseif($i == $_GET['pn']) {
                    $html .='<p><strong><a href="index.php?page=news&amp;limit='.$limit.'&amp;pn='.$i.'">'.$i.'</a></strong></p>'."\n";
                    //echo $i;
                } else {
                    $html .='<p><a href="index.php?page=news&amp;limit='.$limit.'&amp;pn='.$i.'">'.$i.'</a></p>'."\n";
                    
                }
            }

            $html .= '</div>'."\n";
                
            $next = $_GET['pn'] + 1;
            $prev = $_GET['pn'] - 1;

            if(!isset($_GET['pn']) && $pageAm > 1) {
                $next = 2;
            }
            
            if($prev != 0 && isset($_GET['pn'])) {
                $html .= '<p class="clear-all"><a class="button" href="index.php?page=news&amp;limit='.$limit.'&amp;pn='.$prev.'"><< Previous Page</a>'."\n";
            } else {
                $html .= '<p class="clear-all">';
            }

            if(($_GET['pn'] != $pageAm && isset($_GET['pn'])) || (!isset($_GET['pn']) && $pageAm > 1)) {
                $html .= '<a class="button" href="index.php?page=news&amp;limit='.$limit.'&amp;pn='.$next.'">Next Page >></a></p>'."\n";
            } else {
                $html .= '</p>';
            }

            $html .= '</div>'."\n";

        }
        
        return $html;
    }
    
}



?>