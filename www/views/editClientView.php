<?php

// Displays the page which is used  to edit a clients information
class EditClientView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
                $html .= '<section>'."\n";
                
                $html .= '<aside>'."\n";
                $html .= '</aside>'."\n";
            
                
                $html .= '<div id="pageContent">'."\n";

                if(!isset($_SESSION['userType'])) {
                	$html .= '<p class="error">You do not have access to this page</p>'."\n";
        	        $html .= '</div>'."\n";
        	        $html .= '</section>'."\n";
        	        return $html;
                }



                if(!$_GET['id'] && $_SESSION['userType'] == 'admin') {
                	$html .= '<p class="error">There was an error finding the user. Please try coming back to this page again.</p>'."\n";
        	        $html .= '</div>'."\n";
        	        $html .= '</section>'."\n";
        	        return $html;
                }

                //print_r($_SESSION);

                if($_SESSION['userType'] == 'admin') {
                	$userID = $_GET['id'];
                } else {
                	$userID = $_SESSION['userID'];
                }

                $clientInfo = $this -> model -> getClient($userID);
                //print_r($clientInfo);

                if($_POST['submit']) {
                	$update = $this -> model -> processUpdateClientInfo($_SESSION['userType']);
                	if($update['ok']) {

                                if($_SESSION['userType'] == 'admin') {
                		      header('Location: index.php?page=client&id='.$userID);
                                } else {
                                        header('Location: index.php?page=home');
                                }
                	} else {
                		$html .= '<p class="error">'.$update['msg'].'</p>'."\n";
                	}
                }
                
                $html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'">'."\n";
                $html .= '<input type="hidden" name="userID" value="'.$clientInfo['userID'].'" />'."\n";

                if($_SESSION['userType'] == 'admin') {
                        $html .= '<input class="long" type="text" name="userName" id="userName" placeholder="Username" value="'.htmlentities(stripslashes($clientInfo['userName'])).'"/>'."\n";
                        $html .= '<p id="nameMsg" class="error">'.$err['userName'].'</p>'."\n";
                }

                $html .= '<input class="long" type="text" name="userEmail" id="email" placeholder="Email" value="'.htmlentities(stripslashes($clientInfo['userEmail'])).'"/>'."\n";
                $html .= '<p id="emailMsg" class="error">'.$err['userEmail'].'</p>'."\n";

                $html .= '<input class="long" type="text" name="userCompany" id="companyName" placeholder="Company" value="'.htmlentities(stripslashes($clientInfo['userCompany'])).'"/>'."\n";
                $html .= '<p id="cnameMsg" class="error">'.$err['userCompany'].'</p>'."\n";

                $html .= '<input class="long" type="text" name="userPh" id="phoneNumber" placeholder="Phone Number" value="'.htmlentities(stripslashes($clientInfo['userPh'])).'"/>'."\n";
                $html .= '<p id="phnoMsg" class="error">'.$err['userPh'].'</p>'."\n";

                $html .= '<input type="submit" class="submitButton" name="submit" value="Update"/>'."\n";

                $html .= '</form>'."\n";

                
                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;



	}




}










?>