<?php

// Displays the page which is used by the admin to delete a frequntly asked question
class DeleteFaqView extends View {
    
    // Returns HTML for the browser
    public function displayContent() {



        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";

        if($_SESSION['userType'] != 'admin') {
            $html .= '<p class="error">You do not have access to this page</p>'."\n";
            $html .= '</div>'."\n";
            $html .= '</section>'."\n";
            return $html;
        }

        if($_POST['submitYes']) {
            //print_r($_POST);
            $deleteFaq = $this -> model -> deleteFaq($_POST['faqID']);

            if($deleteFaq['ok'] == true) {
                header('Location: index.php?page=about');
            } else {
                $html .= '<p class="error">Something went wrong while trying to delete the FAQ. Please return to this page and try again.</p>'."\n";
                $html .= '<p class="button"><a href="index.php?page=about">Return to about</a></p>'."\n";
        
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;
            }

        } elseif($_POST['submitNo']) {
            header('Location: index.php?page=about');
        }

        $f = $this -> model -> getFaq($_POST['faqID']);
        //print_r($f);
        
        $html .= '<article class="long inline">'."\n";
        $html .= '<h1>'.stripslashes($f['faqTitle']).'</h1>'."\n";
        $html .= '<p>'.stripslashes($f['faqContent']).'</p>'."\n";
        $html .= '</article>'."\n";

        $html .= '<p class="long">Are you sure you wish to delete this FAQ?</p>'."\n";
        $html .= '<form class="inline long" method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
        $html .= '<input type="hidden" name="faqID" value="'.$_POST['faqID'].'" />'."\n";
        $html .= '<input class="button" type="submit" name="submitYes" value="Yes" />'."\n";
        $html .= '<input class="button" type="submit" name="submitNo" value="No" />'."\n";
        $html .= '</form>'."\n";


        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;
    } 

}





?>