<?php

// Displays a callout if no user is logged in
// Displays a control panel for the administrator
// Displays a clients work to the clients users
class HomeView extends View {
    
    // Returns HTML for the browser
    protected function displayContent() {
        
        $html = ''."\n";
        
        if($_SESSION['userType'] == 'client') {
            $html .= $this -> displayClientInfo();
        } elseif($_SESSION['userType'] == 'admin'){
            $html .= $this -> displayAdminControls();
        }else {
            $html = $this -> displayCallout();
        }

        return $html;
    }
    
    // HTML code for the home page when the user is not logged in
    private function displayCallout() {
        
        $html = '<div id="portableCallout" class="noDesktop"></div>'."\n";
        
        $html .= '<!-- Section 1 - Connect Here -->'."\n";
        $html .= '<section id="one" class="page" data-speed="8" data-type="background">'."\n";
        $html .= '<div class="titleScroll" data-type="sprite" data-offsetY="100" data-Xposition="95%" data-speed="-5"></div>'."\n";
        $html .= '<article>'."\n";
        $html .= '<div id="logo"></div>'."\n";
        $html .= '<h1 class="titleBox"><a href="index.php?page=contact">Connect <em>Here</em> >></a></h1>'."\n";
        $html .= '</article>'."\n";
        $html .= '</section>'."\n";
        
        $html .= '<!-- Section 2 - What we stand for -->'."\n";
        $html .= '<section id="two" class="page" data-speed="6" data-type="background">'."\n";
        $html .= '<div class="titleScroll" data-type="sprite" data-offsetY="100" data-Xposition="50%" data-speed="-10000"></div>'."\n";
        $html .= '<article>'."\n";
        $html .= '<h1 class="titleBox"><a href="index.php?page=about">What We <em>Stand</em> For</a></h1>'."\n";
        $html .= '<p class="contentBox">Our goal is to be the best in the west, for delivering end-to-end quality connections. Because we connect project needs with complete quality solutions, to meet your timeframes - every time.'."\n";
        $html .= '<br />'."\n";
        $html .= 'Fusion Networks takes the time to truly understand your project needs and develops a quality solution which is evident throughout the project and remains flexible in the event of changes to your project needs. We keep you informed of progress and any issues so there are no surprises.  On completion of the project we undergo a rigorous quality audit and transfer documentation and test results in full.</p>'."\n";
        $html .= '<div id="portable-image-1" class="portable-image"></div>'."\n";
        $html .= '</article>'."\n";
        $html .= '</section>'."\n";
        
        $html .= '<!-- Section 3 - Our Experience -->'."\n";
        $html .= '<section id="three" class="page" data-speed="6" data-type="background">'."\n";
        $html .= '<div class="titleScroll" data-type="sprite" data-offsetY="100" data-Xposition="50%" data-speed="-10"></div>'."\n";
        $html .= '<article>'."\n";
        $html .= '<h1 class="titleBox"><a href="index.php?page=about">Our <em>Experience</em></a></h1>'."\n";
        $html .= '<div class="contentBox">'."\n";
        $html .= '<h2>Fusion Networks is built on:</h2>'."\n";
        $html .= '<ul>'."\n";
        $html .= '<li>'."\n";
        $html .= '<p>10 years Telecommunications and remote mining communication work with the following companies: Rio Tinto, BHP, Fortescue, Telecom NZ, Transfield, Downer, Calibre, Fast, Lycopodium, Fluor, GHD, KBR, SKM.</p>'."\n";
        $html .= '</li>'."\n";
        $html .= '<li>'."\n";
        $html .= '<h2>Recent projects include:</h2>'."\n";
        $html .= '<p>800+ room camp communication installs, Gpon networks, MATV systems, Point to Point networks, comms room build and fits outs, automisation projects, maintenance works on fibre links, quality insurance and network auditing.</p>'."\n";
        $html .= '</li>'."\n";
        $html .= '</ul>'."\n";
        $html .= '</div>'."\n";
        $html .= '<div id="portable-image-2" class="portable-image"></div>'."\n";
        $html .= '</article>'."\n";
        $html .= '</section>'."\n";
        
        return $html;
        
    }

    /*************** ADMIN CONTROLS ***************/

    // Display the options for users with administrator privileges
    private function displayAdminControls() {


        
        
        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        $html .= '<div>'."\n";
        $html .= '<h2>Add a new...</h2>'."\n";
        $html .= '<ul>'."\n";
        $html .= '<li><a href="index.php?page=addClient">Client</a></li>'."\n";
        $html .= '<li><a href="index.php?page=addJob">Project</a></li>'."\n";
        $html .= '<li><a href="index.php?page=addPost">News Item</a></li>'."\n";
        $html .= '<li><a href="index.php?page=addMember">Team Member</a></li>'."\n";
        $html .= '<li><a href="index.php?page=addFaq">Frequently Asked Question</a></li>'."\n";
        $html .= '</ul>'."\n";
        $html .= '</div>'."\n";
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";
        $html .= '<h2>Administrator Control Panel</h2>'."\n";


        $html .= $this -> displayClients();
        $html .= $this -> displayJobs();

        $html .= '</div>'."\n";
        
        $html .= '</section>'."\n";

        return $html;

    }

    // Display the all the clients
    private function displayClients() {

        $html .= '<h3>Clients</h3>'."\n";
        $clients = $this -> model -> getAllClients();
        $html .= '<p>Click a username to see all their information</p>';

        if($clients) {
            $html .= '<table id="clients-table">'."\n";
            $html .= '<tr>'."\n";
            $html .= '<th>UserID</th>'."\n";
            $html .= '<th class="title">Username</th>'."\n";
            $html .= '<th>Email</th>'."\n";
            $html .= '<th>Company</th>'."\n";
            $html .= '<th>Phone Number</th>'."\n";
            $html .= '<th>Account Active</th>'."\n";
            $html .= '</tr>'."\n";

            // Loop through each user 
            foreach($clients as $c) {

                if($c['userActive'] == 0) {
                   $html .= '<tr class="unactive-acc">'."\n";
                } else {
                    $html .= '<tr>'."\n";
                }

                $html .= '<td>'.stripslashes($c['userID']).'</td>'."\n";
                $html .= '<td class="userName"><a href="index.php?page=client&amp;id='.$c['userID'].'">'.stripslashes($c['userName']).'</a></td>'."\n";
                $html .= '<td>'.stripslashes($c['userEmail']).'</td>'."\n";
                $html .= '<td>'.stripslashes($c['userCompany']).'</td>'."\n";
                $html .= '<td>'.stripslashes($c['userPh']).'</td>'."\n";
                if($c['userActive'] == 0) {
                    $html .= '<td>False</td>'."\n";
                } else {
                    $html .= '<td>True</td>'."\n";
                }
                $html .= '</tr>'."\n";
            }
            $html .= '</table>'."\n";
        } else {
            $html .= '<p>No clients could be found</p>'."\n";
        }

        return $html;
    }

    // Display all the jobs 
    private function displayJobs() {

        $jobs = $this -> model -> getJobs();

        $inc = 1;

        if(is_array($jobs)) {
            foreach($jobs as $job) {

                $user = $this -> model -> getClient($job['user']['userID']);

                $html .= '<article class="news=item">'."\n";
                $html .= '<h1>'.$job['act']['actTitle'].'</h1>'."\n";
                //print_r($job['user']);
                $html .= '<p class="document" ><a href="files/pdfs/'.htmlentities($job['act']['actFile']).'" target="blank">Click here to download the PDF document</a></p>'."\n";
                $html .= '<h2>'.stripslashes($user['userName']).'</h2>'."\n";
                $html .= '<p>Contract ID: '.stripslashes($job['act']['actContractID']).'</p>'."\n";
                $html .= '<p>Location: '.stripslashes($job['act']['actLocation']).'</p>'."\n";
                $html .= '<p><i>Date: '.stripslashes($job['act']['actDate']).'</i></p>'."\n";
                //echo '<pre>'."\n";
                //print_r($job);
                //echo '</pre>'."\n";



                if($_SESSION['userType'] == 'admin') {
                    $html .= '<div class="clear-all">'."\n";
                    $html .= '<form class="inline" method="post" action="index.php?page=deleteJob">
                    <input type="hidden" name="activityID" value="'.$job['act']['actID'].'" />
                    <input type="submit" class="button" name="submit" value="Delete" />
                    </form>'."\n";
                    $html .= '<form class="inline" method="post" action="index.php?page=editJob&amp;id='.$job['act']['actID'].'">
                    <input type="hidden" name="activityID" value="'.$job['act']['actID'].'" />
                    <input type="submit" class="button" name="submit" value="Edit" />
                    </form>'."\n";
                    $html .= '</div>'."\n";
                }

                $html .= '</article>'."\n";
            }
        }

        return $html;

    }


    /****************** CLIENT CONTROLS *****************/

    // Display client information and relevant jobs
    private function displayClientInfo() {

        $html = '<h1 class="pageHeader">'.$this -> pageInfo['pageHeading'].'</h1>'."\n";
        
        $html .= '<section>'."\n";
        
        $html .= '<aside>'."\n";
        if($_SESSION['userActive'] == true) {
            $html .= '<div>'."\n";
            $html .= '<h2>Options</h2>'."\n";
            $html .= '<ul>'."\n";
            $html .= '<li><a href="index.php?page=changePassword">Change Password</a></li>'."\n";
            $html .= '<li><a href="index.php?page=editClient">Edit Information</a></li>'."\n";
            $html .= '</ul>'."\n";
            $html .= '</div>'."\n";
        }
        $html .= '</aside>'."\n";
    
        
        $html .= '<div id="pageContent">'."\n";

        if($_SESSION['userActive'] == false) {
            $html .= '<p class="error">You have not not activated your account yet. Please check your emails or request another activation email.</p>'."\n";
        }

        $html .= '<article><h2>Your information</h2></article>'."\n";

        $info = $this -> model -> getClient($_SESSION['userID']);
        //print_r($_SESSION);

        $html .= '<table id="clientView">'."\n";
        $html .= '<tr>'."\n";
        $html .= '<td>Username</td>'."\n";
        $html .= '<td>'.stripslashes($info['userName']).'</td>'."\n";
        $html .= '</tr>'."\n";
        $html .= '<tr>'."\n";
        $html .= '<td>Email</td>'."\n";
        $html .= '<td>'.stripslashes($info['userEmail']).'</td>'."\n";
        $html .= '</tr>'."\n";
        $html .= '<tr>'."\n";
        $html .= '<td>Company</td>'."\n";
        $html .= '<td>'.stripslashes($info['userCompany']).'</td>'."\n";
        $html .= '</tr>'."\n";
        $html .= '<tr>'."\n";
        $html .= '<td>Phone Number</td>'."\n";
        $html .= '<td>'.stripslashes($info['userPh']).'</td>'."\n";
        $html .= '</tr>'."\n";
        $html .= '</table>'."\n";

        if($_SESSION['userActive'] == true) {

            $html .= '<article><h2>Your Jobs</h2></article>'."\n";
            $jobs = $this -> model -> getClientJobs($_SESSION['userID']);

            //print_r($jobs);

            if(is_array($jobs)) {

                foreach($jobs as $j) {

                        $html .= '<article class="news=item">'."\n";
                        $html .= '<h1>'.$j['act']['actTitle'].'</h1>'."\n";
                        //print_r($j['user']);
                        $html .= '<p class="document" ><a href="files/pdfs/'.htmlentities(stripslashes($j['act']['actFile'])).'" target="blank">Click here to download the PDF document</a></p>'."\n";
                        $html .= '<p>Contract ID: '.stripslashes($j['act']['actContractID']).'</p>'."\n";
                        $html .= '<p>Location: '.stripslashes($j['act']['actLocation']).'</p>'."\n";
                        $html .= '<p><i>Date: '.stripslashes($j['act']['actDate']).'</i></p>'."\n";
                        //echo '<pre>'."\n";
                        //print_r($j['act']);
                        //echo '</pre>'."\n";

                        $html .= '</article>'."\n";

                }
            } else {
                $html .= '<article>';
                $html .= '<p> No jobs to display.</p>';
                $html .= '</article>';
            }
        }

        $html .= '</div>'."\n";
        
        $html .= '</section>'."\n";

        return $html;

    

    }
    
}



?>