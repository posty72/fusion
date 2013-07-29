<?php

// Displays the page which is used by the admin to add a frquently asked question
class AddFaqView extends View {
    
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


        if($_POST['submit']) {

            $addFaq = $this -> model -> processAddFaq();

            if($addFaq['ok'] == true) {
                header('Location: index.php?page=about');
            } else {
                $addFaq = $errors;
            }

        }


        $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
        $html .= '<input type="text" class="long" name="faqTitle" id="faqTitle" placeholder="Question" value="'.htmlentities(stripslashes($_POST['faqTitle'])).'" />'."\n";
        $html .= '<p class="error">'.$errors['faqTitle'].'</p>'."\n";

        $html .= '<textarea class="long" name="faqContent" id="faqContent" placeholder="Answer" rows="7" cols="20">'.htmlentities(stripslashes($_POST['faqContent'])).'</textarea>'."\n";
        $html .= '<p class="error">'.$errors['faqContent'].'</p>'."\n";

        $html .= '<input type="submit" class="button" name="submit" id="submit" value="Submit" />'."\n";
        $html .= '</form>'."\n";

        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;
	} 

}





?>