<?php

// Displays the page which is used by the admin to add a client
class AddClientView extends View {
    
    // Returns HTML for the browser
	protected function displayContent() {

        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '</aside>'."\n";

        $html .= '<div id="pageContent">'."\n";

        if($_SESSION['userType'] != 'admin') {
                $html .= '<p class="error">Access to this page is restricted</p>'."\n";
                $html .= '<p class="button"><a href="index.php?page=home">Return home</a></p>'."\n";
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;
        }

        $html .= '<p>Adding a user here will send them an email containing their password and a link to activate their account. Once the client clicks this link, their account will become active. Accounts can be deactivated from the control panel.</p>'."\n";
        if($_POST['submit']) {

        	$rs = $this -> model -> processAddClient();

        	//print_r($rs);

        	if(is_array($rs) || $rs == false) {
        		$html .= '<p class="error">'.$rs['msg'].'</p>'."\n";
        	} else {
                        $html .= '<br />'."\n";
        		$html .= '<p>User was successfully created.</p>'."\n";
        		$html .= '<p class="button"><a href="index.php">Return to control panel</a></p>'."\n";
		        $html .= '</div>'."\n";
		        $html .= '</section>'."\n";
        		return $html;
        	}

        }
        $html .= $this -> displayAddClientForm($rs);
        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

		return $html;
	}

    // Displays the form which allows admin to add a new user
    // Needs the array containing errors
	private function displayAddClientForm($err) {

		if(is_array($err)) {
			//print_r($err);
			extract($err);
		}

        
        $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";

        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";

        $html .= '<input class="long" type="text" name="userName" id="userName" placeholder="Username" value="'.htmlentities(stripslashes($_POST['userName'])).'"/>'."\n";
        $html .= '<p id="userNameMsg" class="error">'.$err['userName'].'</p>'."\n";

        $html .= '<input class="long" type="text" name="userEmail" id="email" placeholder="Email" value="'.htmlentities(stripslashes($_POST['userEmail'])).'"/>'."\n";
        $html .= '<p id="emailMsg" class="error">'.$err['userEmail'].'</p>'."\n";

        $html .= '<input class="long" type="text" name="userCompany" id="companyName" placeholder="Company" value="'.htmlentities(stripslashes($_POST['userCompany'])).'"/>'."\n";
        $html .= '<p id="cnameMsg" class="error">'.$err['userCompany'].'</p>'."\n";

        $html .= '<input class="long" type="text" name="userPh" id="phoneNumber" placeholder="Phone Number" value="'.htmlentities(stripslashes($_POST['userPh'])).'"/>'."\n";
        $html .= '<p id="phnoMsg" class="error">'.$err['userPh'].'</p>'."\n";

        $html .= '<input type="submit" class="submitButton" name="submit" value="Add User"/>'."\n";

        $html .= '</form>'."\n";

        return $html;

	}


}







?>