<?php

// Displays the page which is used by the admin to edit a job
class EditJobView extends View {
    
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

        if($_POST['submitUpdate']) {

        	$updateJob = $this -> model -> processUpdateProject();
        	//print_r($updateJob);

        	if($updateJob['ok'] != true) {
        		$errors = $updateJob;
                $html .= '<p class="error">'.$updateJob['msg'].'</p>'."\n";
        	} elseif($updateJob['ok'] == true) {
                header('Location: index.php?page=home');
            }

        }

        $clients = $this -> model -> getAllClients();
        $client = $this -> model -> getClientJob($_GET['id']);

        if($_POST['submitUpdate']) {
        	$client['act'] = $_POST;
        }

        //print_r($client);
        //print_r($_POST);

        $html .= '<form method="post" action="'.htmlentities($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">'."\n";
        //$html .= '<label class="short" for="client">Client</label>'."\n";
        $html .= '<select class="short" name="client">'."\n";

        $html .= '<option value="'.$client['user']['userID'].'" selected>'.$client['user']['userName'].'</option>'."\n";
        
        foreach($clients as $singleClient) {

        	if($singleClient['userID'] != $client['user']['userID']) {
        		$html .= '<option value="'.$singleClient['userID'].'">'.$singleClient['userName'].'</option>'."\n";
        	}

        }
        $html .= '</select>'."\n";
        $html .= '<p class="error">'.$errors['client'].'</p>'."\n";

        $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />'."\n";
        $html .= '<input type="hidden" name="actID" value="'.$client['act']['actID'].'" />'."\n";

        $html .= '<input type="text" class="long" name="actTitle" id="actTitle" placeholder="Project Name" value="'.htmlentities(stripslashes($client['act']['actTitle'])).'" />'."\n";
        $html .= '<p class="error">'.$errors['actTitle'].'</p>'."\n";
        $html .= '<label class="long" for="file">Update the document</label>'."\n";
        $html .= '<input type="file" name="doc" id="file">'."\n";
        $html .= '<p class="error">'.$errors['doc'].'</p>'."\n";
        $html .= '<input type="text" class="long" name="actContractID" id="actContractID" placeholder="Contract ID" value="'.htmlentities(stripslashes($client['act']['actContractID'])).'" />'."\n";
        $html .= '<input type="text" class="long" name="actLocation" id="actLocation" placeholder="Location" value="'.htmlentities(stripslashes($client['act']['actLocation'])).'" />'."\n";
        $html .= '<input type="submit" class="button" name="submitUpdate" />'."\n";
        $html .= '</form>'."\n";

        
        $html .= '</div>'."\n";
        $html .= '</section>'."\n";

        return $html;
	} 

}





?>