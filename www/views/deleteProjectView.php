<?php


// Displays the page which is used by the admin to delete a job
class DeleteJobView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";

        if(!$this -> model -> adminLoggedIn || $this -> model -> userLoggedIn) {
        	$html .= '<p class="error">You do not have access to this page</p>'."\n";
	        $html .= '</div>'."\n";
	        $html .= '</section>'."\n";
	        return $html;
        }

        //print_r($_POST);

        if($_POST['submitYes']) {

        	$deleteAct = $this -> model -> processDeleteAct($_POST['actID'], $_POST['actFile']);
            
            if($deleteAct == true) {
                header('Location: index.php?page=home');
            } else {
                $html .= '<p class="error">There was an error deleting the job. Please return later and try again.</p>'."\n";
                $html .= '<p class="button"><a href="index.php?page=home">Return to the Control Panel</a></p>'."\n";
            
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";
                return $html;
            }
            
        } elseif($_POST['submitNo']) {
            header('Location: index.php?page=home');
        }

        if(!$_POST['activityID']) {
            $html .= '<p class="error">Something went wrong finding the job you wish to delete. Please return to this page again.</p>'."\n";
            $html .= '<p class="button"><a href="index.php?page=home">Return to the Control Panel</a></p>'."\n";
        
            $html .= '</div>'."\n";
            $html .= '</section>'."\n";
            return $html;
        }


        //echo $deletePost;


        $job = $this -> model -> getJob($_POST['activityID']);


        $html .= '<article class="news-item">'."\n";
        $html .= '<h1>'.stripslashes($job['actTitle']).'</h1>'."\n";
        
        // Determine what type of media is being displayed
        $html .= '<p class="document" ><a href="files/pdfs/'.$job['actFile'].'" target="blank">Click here to download the PDF document</a></p>'."\n";
        
        $html .= '<p>'.stripslashes($job['actLocation']).'</p>'."\n";
        $html .= '<p>'.stripslashes($job['actContractID']).'</p>'."\n";
        $html .= '<p><i>'.$job['actDate'].'</i></p>'."\n";

        if($_SESSION['userType'] == 'admin') {
            $html .= '<p>Are you sure you want to delete this job?</p>'."\n";
            $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">
            <input type="hidden" name="actID" value="'.stripslashes($job['actID']).'" />
            <input type="hidden" name="actFile" value="'.stripslashes($job['actFile']).'" />
            <input type="submit" class="button" name="submitYes" value="Yes" />
            <input type="submit" class="button" name="submitNo" value="No" />
            </form>'."\n";
        }
        
        $html .= '</article>'."\n";

        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;




	}


}





?>