<?php

// Displays the page which is used by the admin to add a job
class AddNewJob extends View {
    
    // Returns HTML for the browser
	public function displayContent() {

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
                $html .= '<h2>New Project</h2>'."\n";

                if($_POST['submit']) {
                	$addJob = $this -> model -> processAddNewJob();
                	//print_r($addJob);
                	if($addJob['ok'] == true) {
                		$html .= '<p>Your project has been added successfully</p>'."\n";
                		$html .= '<p class="button"><a href="index.php">Return home</a></p>'."\n";
        		        $html .= '</div>'."\n";
        		        $html .= '</section>'."\n";
        		        return $html;
                	} else {
                		$html .= '<p class="error">'.$addJob['msg'].'</p>'."\n";
                		$html .= $this -> displayAddJobForm($addJob);
                	}
                } else {
                	$html .= $this -> displayAddJobForm(NULL);
                }

                //print_r($_POST);
                //print_r($_FILES);

                $html .= '</div>'."\n";
                
                $html .= '</section>'."\n";

                return $html;
        }

        // Displays the form which allows admin to add a new job/activity/project
        // Needs the array containing any errors
        private function displayAddJobForm($errors) {

        		$clients = $this -> model -> getAllClients();

                $html .= '<form method="post" action="'.$_SERVER['REQUEST_URI'].'" enctype="multipart/form-data">'."\n";
                //$html .= '<label class="short" for="client">Client</label>'."\n";
                $html .= '<select class="short" name="client">'."\n";
                if(!$_POST['client']) {
                	$html .= '<option value="null" disabled selected>Choose the client</option>'."\n";
                }
                foreach($clients as $client) {

                	if($_POST['client'] == $client['userID']) {
                		$html .= '<option value="'.$client['userID'].'" selected>'.$client['userName'].'</option>'."\n";
                	} else {
                		$html .= '<option value="'.$client['userID'].'">'.$client['userName'].'</option>'."\n";
                	}

                }
                $html .= '</select>'."\n";
                $html .= '<p class="error">'.$errors['client'].'</p>'."\n";

                $html .= '<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />'."\n";

                $html .= '<input type="text" class="long" name="actTitle" id="actTitle" placeholder="Project Name" value="'.htmlentities(stripslashes($_POST['actTitle']),ENT_QUOTES).'" />'."\n";
                $html .= '<p class="error">'.$errors['title'].'</p>'."\n";
                $html .= '<label for="file">Document</label>'."\n";
                $html .= '<input type="file" name="doc" id="file">'."\n";
                $html .= '<p class="error">'.$errors['doc'].'</p>'."\n";
                $html .= '<input type="text" class="long" name="actContractID" id="actContractID" placeholder="Contract ID" value="'.htmlentities(stripslashes($_POST['actContractID']),ENT_QUOTES).'" />'."\n";
                $html .= '<input type="text" class="long" name="actLocation" id="actLocation" placeholder="Location" value="'.htmlentities(stripslashes($_POST['actLocation']),ENT_QUOTES).'" />'."\n";
                $html .= '<input type="submit" class="button" name="submit" />'."\n";
                $html .= '</form>'."\n";

		return $html;
	}



}







?>