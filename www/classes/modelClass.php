<?php

// Include appropriate classes
include 'classes/dbClass.php';
include 'classes/uploadClass.php';
include 'classes/resizeImageClass.php';
include 'classes/mobileDetectClass.php';

// Create the model class
// Contains functions that correspond to the database and server
class Model extends Dbase {

    public $adminLoggedIn;
    public $userLoggedIn;
    public $loginMsg;
    public $device;
    
    public function __construct() {
        parent::__construct();
        
        // Create an array conatining pages that have forms which require validation
        $vPages = array('contact', 'addClient', 'addJob', 'editClient', 'addPost', 'editPost', 'addMember', 'editMember', 'editJob', 'addFaq', 'editFaq', 'changePassword');
        
        // Run pages which require validation through the validate class
        if(in_array($_GET['page'], $vPages)) {
            include 'classes/validateClass.php';
            $this -> validate = new Validate;
        }
        $this -> detectDevice();
        
    }#constructor


    // Checks if the user is logged in or not
    public function checkUserSession() {
        
        if($_GET['page'] == 'logout') {
            unset($_SESSION['userID']);
            unset($_SESSION['userName']);
            unset($_SESSION['userType']);
            unset($_SESSION['userActive']);
            unset($_SESSION['userAccess']);
            $this -> userLoggedIn = false;
            $this -> adminLoggedIn = false;
            $this -> loginMsg = 'You have successfully logged out!';
        }

        if($_POST['loginSubmit']) {
            if($_POST['userName'] && $_POST['userPassword']) {
                $this -> userLoggedIn = $this -> validateUser();

                if($this -> userLoggedIn == false) {
                    $this -> loginMsg = 'Login Unsuccessful';
                }

            } else {
                $this -> loginMsg = 'Enter username and password';
            }
        } else {
            
            if($_SESSION['userType'] == 'client') {
                $this -> userLoggedIn = true;
                
            } elseif($_SESSION['userType'] == 'admin') {
                $this -> adminLoggedIn = true;
            }
            
        }
    }

    // Get a valid user from the database and set it to the SESSION array
    public function validateUser() {

        $user = $this -> getUser();

        //print_r($user);
        if(is_array($user)) {
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['userName'] = $user['userName'];
            $_SESSION['userType'] = $user['userType'];
            $_SESSION['userActive'] = $user['userActive'];
            return true;
        } else {
            return false;
        }

    }

    // Update a user's password
    public function processUpdatePassword() {
        
        extract($_POST);
        
        //See if new password is valid
        $result['msg'] = $this -> validate -> checkPassword($newPassword, $confPassword);
        $result['ok'] = $this -> validate -> checkErrorMessages($result);
        
        if($result['ok'] == false) {
            //print_r($result);
            return $result;
        }
        
        $update = $this -> updatePassword($userID);
        
        return $update;
    }

    // Sends two emails using information from $_POST
    public function processSendMessage() {

        // Validate the form
        $validate = $this -> validateContactForm();

        //print_r($validate);

        if($validate['ok'] == false) {
            return $validate;
        }

        //print_r($_POST);

        // Process the form information


        // Send an email to Fusion Admin
        $email1 = 'Mike Bignell <mike@bignell.com>';
        $subject1 = 'Sent via Fusionnet contact form';

        $message1 = stripslashes($_POST['companyName']).' has sent an enquiry through the contact form.'."\n";
        $message1 .= 'Name:'.stripslashes($_POST['firstName']).' '.stripslashes($_POST['lastName'])."\n";
        $message1 .= 'Company: '.stripslashes($_POST['companyName'])."\n";
        $message1 .= 'Email: '.stripslashes($_POST['email'])."\n";
        $message1 .= 'Message: '."\n";
        $message1 .= stripslashes($_POST['message']);
        
        $headers1 = 'From: '. stripslashes($_POST['firstName']).' '.stripslashes($_POST['lastName']).'<'.stripslashes($_POST['email']).'> ' . "\r\n" .
        'Reply-To: ' .stripslashes($_POST['email']) . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=ISO-8859-1'."\r\n".
        'X-Mailer: PHP/'.phpversion();
        
        $sent1 = mail($email1, $subject1, $headers1, $message1);

        // Send thank you email
        $email2 = $_POST['email'];
        $subject2 = 'Thanks for your interest in Fusion Networks';

        $message2 = 'Hi '.stripslashes($_POST['firstName']).' '.stripslashes($_POST['lastName']).'
        Thank you for your interest in Fusion Networks. We will endeavour to get back to as soon as possible.
        Micheal Bignell / Technology Manager
        mike@fusionnet.com.au
        04 876 04382
        08 927 82473
        Level 28, AMP Tower<br>140 St. Georges Terrace, Perth, WA'."\n";
        
        $headers2 = 'From: Fusion Networks<admin@fusionnet.co.au> ' . "\r\n" .
        'Reply-To: mike@bignell.com' . "\r\n" .
        'MIME-Version: 1.0' . "\r\n" .
        'Content-type: text/html; charset=ISO-8859-1'."\r\n".
        'X-Mailer: PHP/'.phpversion();
        
        $sent2 = mail($email2, $subject2, $message2, $headers2);


        //Return success or failure message
        if($sent1 && $sent2) {
            $msg = 'Your message has been successfully sent';
        } else {
            $msg = 'Sorry we couldn\'t send your message. Please try again.';   
        }
        return $msg;
        

    }
    
    //Validates the contact form 
    private function validateContactForm() {
        
        extract($_POST);
        
        $result = array();
        
        $result['nameMsg'] = $this -> validate -> checkName($firstName);
        if(!$result['nameMsg']) {
            $result['nameMsg'] = $this -> validate -> checkName($lastName);
        }
        $result['cnameMsg'] = $this -> validate -> checkRequired($companyName);
        $result['emailMsg'] = $this -> validate -> checkEmail($email);
        $result['phnoMsg'] = $this -> validate -> checkNumeric($phoneNumber);
        $result['messageMsg'] = $this -> validate -> checkRequired($message);
        
        $result['ok'] = $this -> validate -> checkErrorMessages($result);
        
        return $result;
    }

    // Adds a new client user
    public function processAddClient() {

        $v = $this -> validateClientForm($_SESSION['userType']);

        if($v['ok'] == false) {
            return $v;
        }

        $e = $this -> checkUserExists();
        if($e == true) {
            $e = array();
            $e['ok'] = false;
            $e['msg'] = 'User already exists';
            //print_r($e);
            return $e;
        }

        $password = $this -> generateString(8);
        $token = $this -> generateString(40);

        $a = $this -> addClient($password, $token);

        //print_r($a);

        if($a != false) {
            
            $clientInfo = $this -> getClient($a['MAX(userID)']);

            //print_r($clientInfo);

            $email = $clientInfo['userEmail'];
            $subject = 'Fusion Networks Account Information';
            $header =   'From: Fusion Networks<mike@bignell.com> ' . "\r\n" .
                        'Reply-To: mike@bignell.com' . "\r\n" .
                        'MIME-Version: 1.0' . "\r\n" .
                        'Content-type: text/html; charset=ISO-8859-1'."\r\n".
                        'X-Mailer: PHP/'.phpversion();
            $message = 'Hi there
            You will find your account information for your new Fusion Networks account below.
            Your username is '.stripslashes($clientInfo['userName']).'.
            Your password is '.$password.'.
            Please make sure you change your password to after youlog in.
            Click the link below to activate your account
            http://joshua.post.yoobee.net.nz/_Assignments/WE06/Fusion_Networks/www/index.php?page=activate&token='.$token.'
            Thank you for choosing Fusion Networks.

            Micheal Bignell / Technology Manager
            mike@fusionnet.com.au
            04 876 04382
            08 927 82473
            Level 28, AMP Tower,140 St. Georges Terrace, Perth, WA';

            $sent = mail($email, $subject, $header, $message);

            if($sent) {
                return true;
            } else {
                $m = array();
                $m['ok'] = false;
                $m['msg'] = 'Activation email could not be sent';
                $failure = $this -> deleteUser($clientInfo['userID']);
                return $m;
            }

        } else {
            return false;
        }

    } //Edits and validates a Lance user's information
    public function processUpdateClientInfo($userType) {
        
        $vresult = $this -> validateClientForm($userType);
        //print_r($vresult);

        if($vresult['ok'] == false) {
            return $vresult;
        }
        
        if($vresult['ok']) {
            $result = $this -> updateClient($userType);
        }
        
        return $result;
    }
    
    public function validateClientForm($userType) {

        extract ($_POST);
        //print_r($_POST);

        $errors = array();

        if($userType == 'admin') {
            $errors['userName'] = $this -> validate -> checkRequired($userName);
        }

        $errors['userEmail'] = $this -> validate -> checkEmail($userEmail);
        $errors['userCompany'] = $this -> validate -> checkRequired($userCompany);
        $errors['userPh'] = $this -> validate -> checkNumeric($userPh);

        $errors['ok'] = $this -> validate -> checkErrorMessages($errors);

        return $errors;

    }

    // Regereates a token and password and sends the information to the client
    public function processResendActivationEmail() {

        extract($_POST);
        //print_r($_POST);

        $password = $this -> generateString(8);
        $token = $this -> generateString(40);

        $a = $this -> editClientForResend($userID, $password, $token);

        if($a == true) {
            
            $clientInfo = $this -> getClient($userID);

            //print_r($clientInfo);



            $email = $clientInfo['userEmail'];
            $subject = 'Fusion Networks Account Information';
            $header =   'From: Fusion Networks<mike@bignell.com>> ' . "\r\n" .
                        'Reply-To: mike@bignell.com' . "\r\n" .
                        'MIME-Version: 1.0' . "\r\n" .
                        'Content-type: text/html; charset=ISO-8859-1'."\r\n".
                        'X-Mailer: PHP/'.phpversion();

            $message = 'Hi there
            You will find your account information for your new Fusion Networks account below.
            Your username is '.stripslashes($clientInfo['userName']).'
            Your password is '.$password.'
            Please make sure you change your password to after youlog in.
            Click the link below to activate your account
            http://joshua.post.yoobee.net.nz/_Assignments/WE06/Fusion_Networks/www/index.php?page=activate&token='.$token.'
            Thank you for choosing Fusion Networks

            Micheal Bignell / Technology Manager
            mike@fusionnet.com.au
            04 876 04382
            08 927 82473
            Level 28, AMP Tower<br>140 St. Georges Terrace, Perth, WA';


            $sent = mail($email, $subject, $header, $message);

            if($sent) {
                return true;
            } else {
                $m = array();
                $m['ok'] = false;
                $m['msg'] = 'Activation email could not be sent';
                return $m;
            }

        } else {
            return false;
        }

    }

    public function processAddNewJob() {

        $v = $this -> validateJobForm('add');

        if($v['ok'] == false) {
            //print_r($v);
            return $v;
        }

        $u = $this -> uploadFile();

        //print_r($u);

        if($u['ok'] == false) {
            $u['msg'] = $u['uploadMsg'];
        } else{
            $u['doc'] = str_replace(' ', '', $u['doc']);
            //print_r($u);
            $u['ok'] = $this -> addNewJob($u['doc'], $_POST['client']);
        }

        return $u;

    }
    
    public function validateJobForm($mode) {

        extract ($_POST);
        //print_r($_POST);

        $errors = array();

        $errors['title'] = $this -> validate -> checkRequired($actTitle);

        if($mode == 'add') {
            $errors['doc'] = $this -> validate -> checkRequired($_FILES['doc']['name']);
        }

        $errors['client'] = $this -> validate -> checkSelectField($client);

        if(!$client) {
            $errors['client'] = 'Required field';
        }

        $errors['ok'] = $this -> validate -> checkErrorMessages($errors);

        return $errors;

    }

    // Delete a job and it's associated pdf file
    public function processDeleteAct($actID, $actFile) {

        if(isset($actID) && isset($actFile)) {

            @ unlink('files/pdfs/'.$actFile);

            //echo 'files/pdfs/'.$actFile;
            $job = $this -> deleteJob($actID);

            if($job == true) {
                return true;
            }

        }
        return false;
    }

    public function processUpdateProject() {

        $valid = $this -> validateJobForm('edit');

        if($valid['ok'] != true) {
            echo 'Invalid';
            return $valid;
        }

        $oldAct = $this -> getJob($_POST['actID']);

        //print_r($_FILES);
        //print_r($oldAct);


        if($_FILES['doc']['name']) {

            @ unlink('files/pdfs/'.$oldAct['actFile']);

            $file = $this -> uploadFile();

            if($file['ok']) {
                $result['doc'] = true;
            }
        }

        if($result['doc'] == true) {
            //print_r($file);
            $file['doc'] = str_replace(' ', '', $file['doc']);
            $result = $this -> updateJob($file['doc']);
        } else {
            $result = $this -> updateJob($oldAct['actFile']);
        }

        return $result;
    }

    public function newsPagination($limit) {

        if(isset($_GET['pn'])) {       
            $start = ($_GET['pn'] - 1) * $limit;
        } else {
            $start = 0;
        }
        

       //echo $start;
        
        $post = $this -> getNewsItems($start, $limit);
        
        if(is_array($post)) {
            return $post;
        } else {
            return false;
        }

    }


    // Add a new post to the news feed
    public function processAddPost() {

        /*
        print_r($_POST);
        echo '<br />';
        print_r($_FILES);
        */

        $valid = $this -> validatePostForm($_GET['page']);

        //print_r($valid);

        if($valid['ok'] == false) {
            //echo 'Not valid';
            return $valid;
        }

        #echo 'A-Ok';
        if($_POST['postMediaType'] == 'none') {
            $insert = $this -> addPlainPost();
        } elseif($_POST['postMediaType'] == 'video') {
            //echo 'Adding video';
            $insert = $this -> addVideoPost();
        } elseif($_POST['postMediaType'] == 'pdf') {
            $pdf = $this -> uploadFile();
            if($pdf['ok'] == true) {
                $insert = $this -> addDocPost($pdf['doc']);
            } else {
                //echo $pdf['uploadMsg'];
                return $pdf;
            }
        } elseif($_POST['postMediaType'] == 'image') {
            $_FILES['postMediaFile']['name'] = str_replace(' ', '', $_FILES['postMediaFile']['name']);
            $img = $this -> uploadAndResizeImage($_FILES['postMediaFile'], 'postMediaFile', 'images/news', '480');
            //print_r($_FILES);
            if($img['ok'] == true) {
                $insert = $this -> addImgPost($img['img']);
            } else {
                //echo $img['uploadMsg'];
                return $img;
            }
        }

        return $insert;
    }

    // Edit a post on the newsfeed
    public function processEditPost() {

        $valid = $this -> validatePostForm($_GET['page']);

        //print_r($valid);
        if($valid['ok'] == false) {
            //echo 'Not valid';
            return $valid;
        }

        $result = $this -> updatePost();

        return $result;

    }

    // Delete a post
    public function processDeletePost() {

        $post = $this -> getNewsItem($_POST['postID']);

        //print_r($post);
        //print_r($_POST);

        if(is_array($post)) {
            //echo '$post is an array';
            if($post['postMediaType'] == 'pdf') {
                @ unlink('files/pdfs/'.$post['postMedia']);
            } elseif($post['postMediaType'] == 'image') {
                @ unlink('images/news/'.$post['postMedia']);
            }

            $deleted['ok'] = $this -> deletePost($post['postID']); 

        }

        return $deleted;
    }

    // Validates the post form
    public function validatePostForm($mode) {

        // Youtube URL regular expression
        $regexp = '#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#';
        $valid['ok'] = false;

        if($mode == 'addPost') {

            $postMediaType = $_POST['postMediaType'];
            //print_r($_FILES);

            if($postMediaType == 'none') {

                $valid['ok'] = true;

            // Check if video is selected
            } elseif($postMediaType == 'video') {

                if(!preg_match($regexp, $_POST['postMediaText']) || !$_POST['postMediaText']) {
                    //echo 'Breaks';
                    $valid['postMediaType'] = 'Please enter a Youtube URL';
                } else {
                    $valid['ok'] = true;
                }

            // Check if pdf is selected
            } elseif($postMediaType == 'pdf') {
                if($_FILES['postMediaFile']['type'] == 'application/pdf' || $_FILES['postMediaFile']['type'] == 'application/x-pdf' || $_FILES['postMediaFile']['type'] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' || $_FILES['postMediaFile']['type'] == 'application/msword') {
                    //echo 'File is valid';
                    $valid['ok'] = true;
                } else {
                    //echo 'File is not valid';
                    $valid['postMediaType'] = 'Please select either a PDF or a Word document';
                }

            // Check if image is selected
            } elseif($postMediaType == 'image') {
                if($_FILES['postMediaFile']['type'] == 'image/jpeg' || $_FILES['postMediaFile']['type'] == 'image/jpg' || $_FILES['postMediaFile']['type'] == 'image/png' || $_FILES['postMediaFile']['type'] == 'image/gif' || $_FILES['postMediaFile']['type'] == 'image/pjpeg') {
                    $valid['ok'] = true;
                } else {
                    $valid['postMediaType'] = 'Please choose a JPG, PNG or GIF to upload';
                }
            } elseif($postMediaType == 'null') {
                if($_FILES['postMediaFile']['error'] == 4 && !$_POST['postMedia']) {
                    $valid = true;
                } else {
                    $valid['postMediaType'] = 'Please make sure you have selected the correct media type';
                }
            } else {
                $valid['postMediaType'] = 'Please make sure you select a media type';
            }

            $valid['postName'] = $this -> validate -> checkRequired($_POST['postName']);
            $valid['postContent'] = $this -> validate -> checkRequired($_POST['postContent']);

            $valid['ok'] = $this -> validate -> checkErrorMessages($valid);


        } elseif($mode == 'editPost') {
            

            $valid['postName'] = $this -> validate -> checkRequired($_POST['postTitle']);
            $valid['postContent'] = $this -> validate -> checkRequired($_POST['postContent']);
            //print_r($valid);
            $valid['ok'] = $this -> validate -> checkErrorMessages($valid);

        }
        //echo '$valid = ';
        //print_r($valid);
        return $valid;
    }


    // Add a new team member and upload a picture if necessary
    public function processAddMember() {


        $valid = $this -> validateMemberForm('add');

        if($valid['ok'] != true) {
            return $valid;
        }

        $result = array();

        if($_FILES['profImg']['name']) {
            $_FILES['profImg']['name'] = str_replace(' ', '', $_FILES['profImg']['name']);
            $img = $this -> uploadAndResizeImage($_FILES['profImg'], 'profImg', 'images/profiles', '480');
            //print_r($img);

            if($img['ok']) {
                $result['profImg'] = true;
            }

        }

        if($img['ok'] == true) {
            $profImg = $img['img'];
        } else {
            $profImg = 'defaultProfile.gif';
        }

        $result = $this -> addMember($profImg);

        return $result;

    }

    // Delete a team member and their profile picture
    public function processDeleteMember() {

        //print_r($_POST);

        if($_POST['profImg'] != 'defaultProfile.gif') {
            @ unlink('images/profiles/'.$_POST['profImg']);
        }

        $deleteMember = $this -> deleteMember($_POST['profID']);

        return $deleteMember;

    }

    // Update an employee's profile
    public function processUpdateMember() {

        $valid = $this -> validateMemberForm('edit');

        if($valid['ok'] != true) {
            return $valid;
        }

        $oldInfo = $this -> getProfile($_POST['profID']);

        if($_FILES['profImg']['name']) {

            @ unlink('images/profiles/'.$oldInfo['profImg']);

            $_FILES['progImg']['name'] = str_replace(' ', '', $_FILES['profImg']['name']);
            $img = $this -> uploadAndResizeImage($_FILES['profImg'], 'profImg', 'images/profiles', '480');

            if($img['ok']) {
                $result['profImg'] = true;
            }
        }

        if($img['ok'] == true) {
            $profImg = $img['img'];
        } elseif(isset($oldInfo['profImg'])) {
            $profImg = $oldInfo['profImg'];
        } else {
            $profImg = 'defaultProfile.gif';
        }

        $result = $this -> editMember($profImg);

        return $result;


    }


    // Validates the member form
    public function validateMemberForm($mode) {

        extract ($_POST);
        //print_r($_POST);

        $errors = array();

        $errors['profName'] = $this -> validate -> checkName($profName);
        $errors['profContent'] = $this -> validate -> checkRequired($profContent);
        $errors['profEmail'] = $this -> validate -> checkEmail($profEmail);

        $errors['ok'] = $this -> validate -> checkErrorMessages($errors);

        return $errors;
    }

    // Uploads pdfs and word documents to the server
    public function uploadFile() {

        ini_set('memory_limit', '96M');
        ini_set('post_max_size', '20');
        ini_set('upload_max_filesize', '20');

        $docFilePath = 'files/pdfs';

        if(isset($_FILES['doc']['name'])) {
            $name = 'doc';
            $_FILES['doc']['name'] = str_replace(' ', '', $_FILES['doc']['name']);
        } elseif(isset($_FILES['postMediaFile']['name'])) {
            $name = 'postMediaFile';
            $_FILES['postMediaFile']['name'] = str_replace(' ', '', $_FILES['postMediaFile']['name']);
        } else {
            return false;
        }
        
        $fileTypes = array('application/pdf','application/x-pdf','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword');
        $upload = new Upload($name, $fileTypes, $docFilePath);
        
        $returnFile = $upload -> isUploaded();
        
        if(!$returnFile) {
            //echo $upload -> msg;
            $result['uploadMsg'] = $upload -> msg;
            $result['ok'] = false;
            return $result;
        }

        //echo 'rf '.$returnFile.$upload -> msg;
        
        $fileName = basename($returnFile);
        $docPath = $docFilePath.'/'.$fileName;

        if(file_exists($docPath)) {
            $result['doc'] = basename($docPath);
            $result['ok'] = true;
        } else {
            echo 'Falied at file_exists';
            $result['ok'] = false;
        }
        return $result;
    }


    // Validates and adds a new FAQ
    public function processAddFaq() {

        $valid = $this -> validateFaq('add');

        if($valid['ok'] == false) {
            return $valid;
        }

        $addFaq = $this -> addFaq();

        return $addFaq;

    }

    // Validates and updates a FAQ 
    public function processEditFaq() {

        $valid = $this -> validateFaq('edit');

        if($valid['ok'] == false) {
            return $valid;
        }

        $editFaq = $this -> updateFaq();

        return $editFaq;

    }


    // Validates a FAQ form
    public function validateFaq($mode) {

        extract ($_POST);
        //print_r($_POST);

        $errors = array();
        $errors['faqTitle'] = $this -> validate -> checkRequired($faqTitle);
        $errors['faqContent'] = $this -> validate -> checkRequired($faqContent);

        $errors['ok'] = $this -> validate -> checkErrorMessages($errors);

        return $errors;
    }

    // Uploads and resizes images
    public function uploadAndResizeImage($fileFormName, $fileName, $imgPath, $imgSize) {
        
        if(!$fileFormName) {
            return false;
        }

        //echo 'Image Path = '.$imgPath;
        
        $fileTypes = array('image/jpeg','image/jpg','image/png','image/gif','image/pjpeg');
        $upload = new Upload($fileName, $fileTypes, $imgPath);
        
        $returnFile = $upload -> isUploaded();
        
        if(!$returnFile) {
            $result['uploadMsg'] = $upload -> msg;
            $result['ok'] = false;
            return $result;
        }
        
        $fileName = basename($returnFile);
        $path = $imgPath.'/'.$fileName;

        $imgInfo = getimagesize($returnFile);
        
        if($imgInfo[0] > $imgSize || $imgInfo[1] > $imgSize) {
            $resizeObj = new ResizeImage($path, $imgSize, $imgPath, '');
            if(!$resizeObj -> resize()) {
                echo 'Unable to resize image to '.$imgSize.' pixels';
            }
        }
        
        if(file_exists($path)) {
            $result['img'] = basename($path);
            $result['ok'] = true;
            return $result;
        } else {
            return false;
        }
        
    }
    
    private function detectDevice() {
        
        //Instantiate
        $mobileDetect = new Mobile_Detect();
        
        //is it mobile?
        if($mobileDetect -> isMobile()) {
            
            
            //is it a tablet?
            if($mobileDetect -> isTablet()) {
                $this -> device = 'tablet';
            } else {
                $this -> device = 'mobile';
            }
            
        } else {
        //display desktop content
            $this -> device = 'desktop';
        }
        
    }
    

    private function generateString($limit) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $string = '';
        for ($i = 0; $i < $limit; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $string;

    }
    
    
}#class






?>