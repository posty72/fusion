<?php

// Displays the page which is used deactivate a clients account
class DeactivateAccView extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

		$html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
                $html .= '<section>'."\n";

                $html .= '<aside>'."\n";
                $html .= '</aside>'."\n";


                $html .= '<div id="pageContent">'."\n";

                if(!$this -> model -> adminLoggedIn) {
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

                $client = $this -> model -> getClient($_GET['id']);

                if($_POST['affirmitive']) {

                	$deactivate = $this -> model -> deactivateAcc($_GET['id']);

                	if($deactivate == true) {
                		$html .= '<h2>'.$client['userName'].'\'s account has been deactivated.</h2>'."\n";

                		$html .= '<p class="button"><a href="index.php?page=home">Return to the control panel</a></p>'."\n";

                	        $html .= '</div>'."\n";
                	        $html .= '</section>'."\n";

                	        return $html;
                	} else {
                		$html .= '<h2>There was an issue deactivating '.stripslashes($client['userName']).'\'s account</h2>'."\n";
                		$html .= '<p>Please try again.</p>'."\n";
                	        $html .= '</div>'."\n";
                	        $html .= '</section>'."\n";

                	        return $html;
                	}

                } elseif($_POST['negatory']) {
                	header('Location: index.php?page=client&id='.$_GET['id']);
                }

                $html .= '<h2>Are you sure you wish to deactivate '.$client['userName'].'\'s account? </h2>'."\n";
                $html .= '<p>It can be reinstated later if need be.</p>'."\n";
                $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">'."\n";
                $html .= '<input type="hidden" name="userID" value="'.$_GET['id'].'"/>'."\n";
                $html .= '<input class="button" type="submit" name="affirmitive" value="Yes" />'."\n";
                $html .= '<input class="button" type="submit" name="negatory" value="No" />'."\n";
                $html .= '</form>'."\n";

                $html .= '</div>'."\n";
                $html .= '</section>'."\n";

                return $html;


	}


}










?>