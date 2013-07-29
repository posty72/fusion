<?php

// Displays the page which is used by the admin to see a clients information in full
class ClientView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";

        if($_POST['resendSubmit']) {
        	$resend = $this -> model -> processResendActivationEmail();

        	if(is_array($resend)) {
        		$html .= '<p class="error">'.$resend['msg'].'</p>'."\n";
        	}
        }

        if($_SESSION['userType'] != 'admin') {
        	$html .= '<p class="error">You do not have access to this page</p>'."\n";
	        $html .= '</div>'."\n";
	        $html .= '</section>'."\n";
	        return $html;
        }

        if(!$_GET['id']) {
        	$html .= '<p class="error">There was an error finding the user. Please try coming back to this page again.</p>'."\n";
	        $html .= '</div>'."\n";
	        $html .= '</section>'."\n";
	        return $html;
        }

        $userID = $_GET['id'];

        $clientInfo = $this -> model -> getClient($userID);
        //print_r($clientInfo);

        if($resend == 'true') {
                $html .= '<p class="error">The reactivation email was successfully sent.</p>';
        }

        $html .= '<h2>'.stripslashes($clientInfo['userName']).'</h2>'."\n";
        $html .= '<p>'.stripslashes($clientInfo['userEmail']).'</p>'."\n";
        $html .= '<p>'.stripslashes($clientInfo['userCompany']).'</p>'."\n";
        $html .= '<p>'.stripslashes($clientInfo['userPh']).'</p>'."\n";
        //echo $client['userActive'];
        if($clientInfo['userActive'] == 0) {
        	$html .= '<p class="error">Account not active</p>'."\n";
        	$html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">
        	<input type="hidden" name="userID" value="'.$clientInfo['userID'].'" />
        	<input type="submit" class="button" name="resendSubmit" value="Resend activation email" />
        	</form>'."\n";
        } else {
        	$html .= '<p class="button"><a href="index.php?page=deactivateAcc&amp;id='.$clientInfo['userID'].'"">Deactivate this account</a></p>'."\n";
        }

        $html .= '<p class="button"><a href="index.php?page=editClient&amp;id='.$clientInfo['userID'].'">Edit Information</a></p>'."\n";

        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;


	}




}















?>