<?php

// Displays the page which is used by the admin to edit a frequently asked question
class EditFaqView extends View {
    
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

        $f = $this -> model -> getFaq($_POST['faqID']);


        if($_POST['submitEdit']) {

            $editFaq = $this -> model -> processEditFaq();

            if($editFaq['ok'] == true) {
                header('Location: index.php?page=about');
            } elseif($editFaq['msg']){
                $html .= '<p class="error">'.$editFaq['msg'].'</p>'."\n";
            } else {
                $editFaq = $errors;
            }

            $f = $_POST;
        }
        //print_r($f);


        $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
        $html .= '<input type="hidden" name="faqID" value="'.$_POST['faqID'].'" />'."\n";

        $html .= '<input type="text" class="long" name="faqTitle" id="faqTitle" placeholder="Question" value="'.htmlentities(stripslashes($f['faqTitle'])).'" />'."\n";
        $html .= '<p class="error">'.$errors['faqTitle'].'</p>'."\n";

        $html .= '<textarea class="long" name="faqContent" id="faqContent" placeholder="Answer" rows="7" cols="20">'.htmlentities(stripslashes($f['faqContent'])).'</textarea>'."\n";
        $html .= '<p class="error">'.$errors['faqContent'].'</p>'."\n";

        $html .= '<input type="submit" class="button" name="submitEdit" id="submitEdit" value="Update" />'."\n";
        $html .= '</form>'."\n";

        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;
    } 

}





?>