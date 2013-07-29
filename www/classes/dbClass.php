<?php

// Include the information for connecting to the database
include '../config.php';

/*

***All functions (except the constructor) within this class follow this basic structure***

Set the query in the variable to be sent tio the database
Run the query through the database
Check for a result
Return false if an error is found
Return the appropriate information if there are no errors

*/

// Create the database class
// Contains all function that access the database
class Dbase {
    
    // Set variables
    private $db;
    
    // Instantiate the database connection using a constructor
    public function __construct() {
        
        try {
            $this -> db = new mysqli(DBHOST,DBUSER,DBPASS,DBNAME);
            
            if(mysqli_connect_errno()) {
                throw new Exception('Unable to establish database connection');
            }
            
        } catch(Exception $e) {
            die($e -> getMessage());
        }
        
        
    }#constructor
    
    // Retrieve the current page's info from the database
    // Needs a page name
    public function getPageInfo($page) {
        
        // Set the query in a variable
        $qry = "SELECT pageName, pageTitle, pageHeading, pageKeywords, pageDescription FROM pages WHERE pageName = '".$page."'";
        
        // Run the query
        $rs = $this -> db -> query($qry);
        
        // Check for a result and return the information
        if($rs) {
            if($rs -> num_rows > 0) {
                
                // Set information from the database as an array and return it
                $pageInfo = $rs -> fetch_assoc();
                return $pageInfo;
            
            } else {
                //echo 'Page not found';
                return false;
            }#correct result
        } else {
            //echo 'Error executing query \'getPageInfo\'';
            return false;
        }#any result
        
        
    }#getPageInfo


    // Retrieve user info used to initiate a login
    public function getUser() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        $password = sha1($userPassword);

        $qry = "SELECT userID, userName, userType, userActive FROM users WHERE userName = '$userName' AND userPassword = '$password'";
//echo $qry;
        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                //print_r($user);
                return $user;
            } else {
                //echo 'No user found';
                return false;
            }
        } else {
            //echo 'Error executing query "getUser"';
            return false;
        }

    }
    
    //Updates a user's password
    //Needs a userID
    public function updatePassword($userID) {
        
        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }
        
        extract($_POST);
        $oldPassword = sha1($oldPassword);
        $newPassword = sha1($newPassword);
        
        $qry = "SELECT userPassword FROM users WHERE userID = '$userID'";
        $rs = $this -> db -> query($qry);
        $currentPassword = $rs -> fetch_assoc();
        
        $result = array();
        
        if($currentPassword['userPassword'] == $oldPassword) {
              
            $query = "UPDATE users SET userPassword = '$newPassword' WHERE userID = '$userID'";
            $result = $this -> db -> query($query);
            
            if($this -> db -> affected_rows > 0) {
                $update['ok'] = true;
                $update['msg'] = 'Password successfully changed';
                
            } else {
                $update['msg'] = 'Error updating password';
            }
            
        } else {
            $update['msg'] = 'Your password is incorrect';
        }
        return $update;
    }


    /*************************************************
    **************************************************
    ************** NEWS ITEMS FUNCTIONS ***************
    **************************************************
    *************************************************/
    
    // Returns news headlines
    public function getNewsHeadlines() {
        
        $qry = "SELECT postID, postTitle FROM posts ORDER BY posts.postDate LIMIT 15";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($rs -> num_rows > 0) {
                $h = array();
                
                while($row = $rs -> fetch_assoc()) {
                    $h[] = $row;
                }
                
                return $h;
                
            } else {
                //echo 'No headlines were found';
                return false;
            }
        } else {
            //echo 'Error executing query "getPageInfo"';
            return false;
        }
        
    }#getNewsHeadlines
    
    // Retrieve news items
    // Needs an initial value and a limit
    public function getNewsItems($s, $l) {
        
        $qry = "SELECT postID, postTitle, postContent, postMedia, postMediaType, postDate FROM posts ORDER BY postDate DESC";
        //echo $l;
        if($l != 'all') {
            $qry .= " LIMIT ".$s.", ".$l;
        }
        
        #echo $qry;
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($rs -> num_rows > 0) {
                $n = array();
                
                while($row = $rs -> fetch_assoc()) {
                    $n[] = $row;
                }
                return $n;
            } else {
                //echo 'No results returned from getNewsItems query';
                return false;
            }
        } else {
            //echo 'Error executing query "getNewsItems"';
            return false;
        }
        
    }#getNewsItems

    // Get a single news item
    // Requires the ID of the post
    public function getNewsItem($postID) {

        $qry = "SELECT postID, postTitle, postContent, postMedia, postMediaType, postDate FROM posts WHERE posts.postID = '$postID'";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {
                $post = $rs -> fetch_assoc();
                return $post;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    // Add a new post with no image, video or file
    public function addPlainPost() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        $qry = "INSERT INTO posts VALUES (NULL, '$postName', '$postContent', NULL, 'plain', NOW())";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                return false;
            }
        } else {
            //echo 'Error executing query "addPlainPost"';
            return false;
        }

    }

    // Adds a new video to the posts table
    public function addVideoPost() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        //print_r($_POST);
        extract($_POST);


        // Convert the URL to a basic Youtube ID to be displayed however needed
        $string = $postMediaText;
        $search = '/http:\/\/www.youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';
        $replace = "$1";    
        $url = preg_replace($search,$replace,$string);
        //echo $url;

        $qry = "INSERT INTO posts VALUES (NULL, '$postName', '$postContent', '$url', '$postMediaType', NOW())";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                //echo 'No posts were added';
                return false;
            }
        } else {
           //echo 'Error executing query "addVideoPost"';
            return false;
        }
    }

    // Add a post that contains a document to the post
    // Requires the file name of the doc
    public function addDocPost($fileName) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        //print_r($_POST);
        extract($_POST);

        $qry = "INSERT INTO posts VALUES (NULL, '$postName', '$postContent', '$fileName', '$postMediaType', NOW())";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                //echo 'The post was not inserted';
                return false;
            }
        } else {
            //echo 'Error executing query "addDocPost"';
            return false;
        }

    }

    // Add a post which contains an image
    // Requires the image name
    public function addImgPost($imgName) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        $qry = "INSERT INTO posts VALUES (NULL, '$postName', '$postContent', '$imgName', '$postMediaType', NOW())";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                return false;
            }
        } else {
           // echo 'Error executing query "addImgPost"';
            return true;
        }

    }

    // Updates a post that doesn't contain any media
    public function updatePost() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        //print_r($_POST);
        extract($_POST);

        $qry = "UPDATE posts SET postTitle = '$postTitle', postContent = '$postContent' WHERE postID = '$postID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['msg'] = 'The post was not updated';
            }
            return $result;
        } else {
            //echo 'Error executing query"updatePost"';
            return false;
        }


    }

    // Delete a post from the databse
    // Requires the ID of the post
    public function deletePost($postID) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        $qry = "DELETE FROM posts WHERE postID = '$postID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                return true;
            } else {
                //echo 'No rows were deleted';
                return false;
            }
        } else {
            //echo 'Error executing query "deletePost"';
            return false;
        }
    }


    /*************************************************
    **************************************************
    ************ FREQUENTLY ASKED QUESTIONS *************
    **************************************************
    *************************************************/
    
    // Retrieve Frequently asked questions
    public function getFaqs() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        
        $qry = "SELECT faqID, faqTitle, faqContent FROM faqs";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($rs -> num_rows > 0) {
                $f = array();
                
                while($row = $rs -> fetch_assoc()) {
                    $f[] = $row;
                }
                
                return $f;
                
            } else {
                //echo 'getFaqs returned no results';
                return false;
            }
        } else {
            //echo 'Error running "getFaqs" query';
            return false;
        }
        
        
    }#getFaqs


    // Get a single FAQ 
    // Requires an ID
    public function getFaq($faqID) {

        $qry = "SELECT faqID, faqTitle, faqContent FROM faqs WHERE faqID = '$faqID'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($rs -> num_rows > 0) {
                
                $f = $rs -> fetch_assoc();
                
                return $f;
                
            } else {
                //echo 'getFaq returned no results';
                return false;
            }
        } else {
            //echo 'Error running "getFaq" query';
            return false;
        }

    }

    // Insert a new FAQ to the database
    public function addFaq() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        $qry = "INSERT INTO faqs VALUES (NULL, '$faqTitle', '$faqContent')";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['ok'] = false;
            }
            return $result;
        } else {
            echo 'Error executing query "addFaq"';
            return false;
        }

    }

    // Updates a FAQ
    public function updateFaq() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        $qry = "UPDATE faqs SET faqTitle = '$faqTitle', faqContent = '$faqContent' WHERE faqID = '$faqID'";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['msg'] = 'Nothing was changed on the database';
            }
            return $result;
        } else {
            echo 'Error executing query "updateFaq"';
            return false;
        }

    }

    // Delete a FAQ from the database
    // Requires a faqID 
    public function deleteFaq($faqID) {

        $qry = "DELETE FROM faqs WHERE faqID = '$faqID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }



    /*************************************************
    **************************************************
    ************** EMPLOYEE PROFILES ***************
    **************************************************
    *************************************************/
    
    // Retrieve employee profiles
    public function getProfiles() {
        
        $qry = "SELECT profID, profName, profContent, profImg, profEmail FROM profiles";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($rs -> num_rows > 0) {
                $p = array();
                
                while($row = $rs -> fetch_assoc()) {
                    $p[] = $row;
                }
                return $p;
            } else {
                //echo 'getProfiles returned no results';
                return false;
            }
        } else {
            //echo 'Error running "getProfiles" query';
            return false;
        }
        
        
    }#getProfiles


    
    // Retrieve singular employee profile
    // Requires the profile ID
    public function getProfile($profID) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }
        
        $qry = "SELECT profID, profName, profContent, profImg, profEmail FROM profiles WHERE profID = '$profID'";
        
        $rs = $this -> db -> query($qry);
        
        if($rs) {
            if($rs -> num_rows > 0) {
                
                $p = $rs -> fetch_assoc();

                return $p;
            } else {
                //echo 'getProfile returned no results';
                return false;
            }
        } else {
            //echo 'Error running "getProfile" query';
            return false;
        }
        
        
    }#getProfiles

    

    // Insert a new member to the profiles table
    // Requires the file name of the image
    public function addMember($profImg) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);
        //print_r($_POST);

        $qry = "INSERT INTO profiles VALUES (NULL, '$profName', '$profContent', '$profImg', '$profEmail')";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                return false;
            }
        } else {
            echo 'Error executing query "addMember"';
            return false;
        }
    }

    // Remove a team member from the database
    // Requires an ID
    public function deleteMember($profID) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        $qry = "DELETE FROM profiles WHERE profID = '$profID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }


    }

    // Remove a team member from the database
    // Requires an ID
    public function editMember($profImg) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);
        //print_r($_POST);

        $qry = "UPDATE profiles SET profName = '$profName', profContent = '$profContent', profImg = '$profImg', profEmail = '$profEmail' WHERE profID = '$profID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
                return $result;
            } else {
                $result['msg'] = 'Nothing was changed.';
                return $result;
            }
        } else {
            echo 'Error executing query "editMember"';
            return false;
        }


    }


    /*************************************************
    **************************************************
    **************** USER FUNCTIONS ******************
    **************************************************
    *************************************************/

    // Retrieve all clients and information
    public function getAllClients() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        $qry = "SELECT userID, userName, userEmail, userType, userCompany, userPh, userActive FROM users WHERE userID NOT IN ( 1 )";
        $rs = $this -> db -> query($qry);
        if($rs) {
            if($rs -> num_rows > 0) {
                $c = array();
                while($row = $rs -> fetch_assoc()) {
                    $c[] = $row;
                }
                return $c;
            } else {
                //echo 'No clients found';
                return false;
            }
        }else{
            //echo 'Error executing query "getAllClients"';
            return false;
        }
    }


    // Retrieve a clients information
    // Requires an ID
    public function getClient($userID) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        //extract($_POST);

        $qry = "SELECT userID, userName, userPassword, userEmail, userCompany, userPh, userActive, userToken FROM users WHERE userID = '$userID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {
                $user = $rs -> fetch_assoc();
                return $user;
            } else {
                //echo 'User not found';
                return false;
            }
        } else {
            //echo 'Error executing query "getClient"';
            return false;
        }

    }


    // Insert a new user to the database
    // Requires a password and token that was sent to the user
    public function addClient($password, $token) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        $password = sha1($password);

        extract($_POST);
        //print_r($_POST);

        $qry = "INSERT INTO users VALUES (NULL, '$userName', '$password', '$userEmail', 'client', '$userCompany', '$userPh', 'false', '$token')";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {

                $qry = "SELECT MAX(userID) FROM users";
                $rs = $this -> db -> query($qry);
                $userID = $rs -> fetch_assoc();

                return $userID;

            } else {
                //echo 'User was not added';
                return false;
            }
        } else {
            //echo 'Error executing query "addClient"';
            return false;
        }
    }

    // Check if the user is already in the database
    public function checkUserExists() {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        $qry = "SELECT userName, userEmail FROM users WHERE userName = '$userName' OR userEmail = '$userEmail'";
        //echo $qry;
        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            //echo 'Error executing query "checkUserExists"';
            return false;
        } 

    }

    // Edit a clients information in case they need to reactivate their account
    // Requires an ID, and the password and token sent to the user
    public function editClientForResend($userID, $password, $token) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        $password = sha1($password);

        extract($_POST);
        //print_r($_POST);

        $qry = "UPDATE users SET userPassword = '$password', userToken = '$token' WHERE userID = '$userID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                return true;

            } else {
                //echo 'User was not updated';
                return false;
            }
        } else {
            //echo 'Error executing query "editClientForResend"';
            return false;
        }
    

    }

    // Activate a clients account and remove the token
    // Requires a token
    public function activateAccount($token) {

        $qry = "UPDATE users SET userActive = '1', userToken = NULL WHERE userToken = '$token'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    // Deactivates a user's account
    // Requires an ID
    public function deactivateAcc($userID) {

        $qry = "UPDATE users SET userActive = 0 WHERE userID = '$userID'";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                return true;
            } else {
                //echo 'User\'s account could not be deactivated';
                return false;
            }
        } else {
            //echo 'Error executing query "deactivateAcc"';
            return false;
        }

    }

    // Update a client's information
    // Needs the type of user
    public function updateClient($userType) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        extract($_POST);

        if($userType == 'client') {
            $qry = "UPDATE users SET userCompany = '$userCompany', userEmail = '$userEmail', userPh = '$userPh' WHERE userID = '$userID'";
        }elseif($userType == 'admin') {
            $qry = "UPDATE users SET userName = '$userName', userCompany = '$userCompany', userEmail = '$userEmail', userPh = '$userPh' WHERE userID = '$userID'";
            //echo $qry;
        } else {
            //echo 'Error finding userType when setting query for "updateClient"';
            return false;
        }

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $return['ok'] = true;
                return $return;
            } else {
                $return['msg'] = 'The info was not updated';
                return $return;
            }
        }else{
            //echo 'Error executing query "updatClient"';
            return false;
        }

    }

    // Deletes a user if the account could not be activated
    // Requires an ID
    public function deleteUser($userID) {
        $qry = "DELETE FROM users WHERE userID = '$userID'";
        //echo $qry;
        $rs = $this -> db -> query($qry);
        if($rs) {
            if($this -> db -> affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /*************************************************
    **************************************************
    ******* JOBS/PROJECTS/ACTIVITY FUNCTIONS *********
    **************************************************
    *************************************************/

    // Add a new activity
    // Needs a file name and the client
    public function addNewJob($file, $client) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        //echo 'Made it to the query';
        //print_r($_POST);
        extract($_POST);

        $qry = "INSERT INTO activities VALUES (NULL, '$actTitle', '$file', NOW(), '$actContractID', '$actLocation')";
        //echo $qry;
        $rs = $this -> db -> query($qry);

        if($rs) {

            // Now insert values to the activityusers table
            $qry = "SELECT MAX(actID) as actID FROM activities";
            $rs = $this -> db -> query($qry);
            $actIDArray = $rs -> fetch_assoc();

            //print_r($actID);
            $actID = $actIDArray['actID'];

            $rs = false;

            $qry = "INSERT INTO activityusers VALUES (NULL, '$actID', '$client')";
            //echo $qry;
            $rs = $this -> db -> query($qry);

            if($rs) {
                if($this -> db -> affected_rows > 0) {
                    //echo 'Database was updated';
                    return true;
                }
            } else {
                //echo 'Error executing query "AddNewJob"';
                return false;
            }

        } else {
            //echo 'Error executing query "addNewJob"';
            return false;
        }

    }

    // Retrieve all information regarding to clients' jobs
    public function getJobs() {

        $qry = "SELECT actUserID, actID, userID FROM activityusers";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {

                $actUsers = array();
                
                while($row = $rs -> fetch_assoc()) {
                    $actUsers[] = $row;
                }

                //print_r($actUsers);

                $activities = array();

                $inc = 1;

                foreach($actUsers as $actUser) {

                    $actQry = "SELECT actID, actTitle, actFile, actDate, actContractID, actLocation FROM activities WHERE actID = '$actUser[actID]' ORDER BY actDate DESC";

                    $actRs = $this -> db -> query($actQry);

                    $activities[$inc]['act'] = $actRs -> fetch_assoc();

                    $userQry = "SELECT userID, userName FROM users WHERE userID = '$actUser[userID]'";
                    //echo $userQry;
                    $userRs = $this -> db -> query($userQry);

                    $activities[$inc]['user'] = $userRs -> fetch_assoc();

                    $inc++;

                }

                //print_r($activities);
                return $activities;

            }
        } else {
            //echo 'Error executing initial query in "getClientJobs"';
            return false;
        }


    }

    // Retrieve all information regarding to a clients' jobs
    // Requires an ID
    public function getClientJob($actID) {

        $qry = "SELECT actUserID, actID, userID FROM activityusers WHERE actID = '$actID'";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {
                
                $actUser = $rs -> fetch_assoc();
                

                //print_r($actUser);

                $activity  = array();

                $actQry = "SELECT actID, actTitle, actFile, actDate, actContractID, actLocation FROM activities WHERE actID = '$actUser[actID]'";

                $actRs = $this -> db -> query($actQry);

                $activity['act'] = $actRs -> fetch_assoc();

                $userQry = "SELECT userID, userName FROM users WHERE userID = '$actUser[userID]'";
                //echo $userQry;
                $userRs = $this -> db -> query($userQry);

                $activity['user'] = $userRs -> fetch_assoc();


                //print_r($activity);
                return $activity;

            }
        } else {
            //echo 'Error executing initial query in "getClientJobs"';
            return false;
        }


    }

    // Retrieve all jobs for a single client
    // Requires an ID
    public function getClientJobs($userID) {

        $qry = "SELECT actUserID, actID, userID FROM activityusers WHERE userID = '$userID'";

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {

                $acts = array();
                
                while($row = $rs -> fetch_assoc()) {
                    $acts[] = $row;
                }

                //print_r($acts);

                $activities = array();

                $inc = 1;

                foreach($acts as $act) {

                    $actQry = "SELECT actID, actTitle, actFile, actDate, actContractID, actLocation FROM activities WHERE actID = '$act[actID]' ORDER BY actDate";

                    $actRs = $this -> db -> query($actQry);

                    $activities[$inc]['act'] = $actRs -> fetch_assoc();

                    $inc++;
                }

                //print_r($activities);
                return $activities;

            }
        } else {
            //echo 'Error executing initial query in "getClientJobs"';
            return false;
        }


    }

    // Retrieve a single job from the database
    // Requires an ID
    public function getJob($id) {

        $qry = "SELECT actID, actTitle, actFile, actDate, actContractID, actLocation FROM activities WHERE actID = '$id'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($rs -> num_rows > 0) {
                $job = $rs -> fetch_assoc();
                return $job;
            } else {
                //echo 'No results returned';
                return false;
            }
        } else {
            //echo 'Error executing query"getJob"';
            return false;
        }


    }

    // Delete a job from the databse
    // Requires an ID
    public function deleteJob($jobID) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }

        $qry = "DELETE FROM activities WHERE activities.actID = '$jobID'";
        $qry2 = "DELETE FROM activityusers WHERE activityusers.actID = '$jobID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            $this -> db -> query($qry2);
        }

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            echo 'Error executing query"deleteJob"';
            return false;
        }

    }

    // Updates a job on the database
    // Requires a filename
    public function updateJob($file) {

        if(!get_magic_quotes_gpc()) {
            $this -> sanitizeInput();
        }


        //print_r($_POST);
        extract($_POST);

        $qry = "UPDATE activities SET actTitle = '$actTitle', actFile = '$file', actContractID = '$actContractID', actLocation = '$actLocation' WHERE actID = '$actID'";
        //echo $qry;

        $rs = $this -> db -> query($qry);

        if($rs) {
            if($this -> db -> affected_rows > 0) {
                $result['ok'] = true;
            } else {
                $result['msg'] = 'Nothing was changed within the database';
            }
            return $result;
        } else {
            //echo 'Error executing query "updateJob"';
            return false;
        }


    }
    
    //Sanitizes the input of all data going into the database
    private function sanitizeInput() {
        
        foreach($_POST as &$post) {
            $post = $this -> db -> real_escape_string($post);
            $post = strip_tags($post);
        }
        
    }
}


?>