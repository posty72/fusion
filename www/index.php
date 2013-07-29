<?php

//Instantiate the session
session_start();
//print_r($_SESSION);

// Include the view class
include 'views/viewClass.php';
// Include the model class
include 'classes/modelClass.php';

class PageSelector {
    
    public function run() {
        
        // Set the default page to home
        if(!$_GET['page']) {
            $_GET['page'] = 'home';
        }
        
        //Instantiate the model class
        $model = new Model;
        
        //Retrieve the page information
        $pageInfo = $model -> getPageInfo($_GET['page']);
        
        //Set the page
        switch($_GET['page']) {
            
            case 'home':
                include 'views/homeView.php';
                $view = new HomeView($pageInfo, $model);
                break;
            
            case 'news':
                include 'views/newsFeedView.php';
                $view = new NewsView($pageInfo, $model);
                break;
            
            case 'about':
                include 'views/aboutView.php';
                $view = new AboutView($pageInfo, $model);
                break;
            
            case 'contact':
                include 'views/contactView.php';
                $view = new ContactView($pageInfo, $model);
                break;
            
            case 'login':
            case 'logout':
                include 'views/logView.php';
                $view = new LogView($pageInfo, $model);
                break;

            case 'addClient':
                include 'views/addClientView.php';
                $view = new AddClientView($pageInfo, $model);
                break;

            case 'activate' :
                include 'views/activateAccountView.php';
                $view = new ActivateAccountView($pageInfo, $model);
                break;

            case 'addJob' :
                include 'views/addNewJobView.php';
                $view = new AddNewJob($pageInfo, $model);
                break;

            case 'deleteJob':
                include 'views/deleteProjectView.php';
                $view = new DeleteJobView($pageInfo, $model);
                break;

            case 'editJob':
                include 'views/editProjectView.php';
                $view = new EditJobView($pageInfo, $model);
                break;

            case 'client' :
                include 'views/clientView.php';
                $view = new ClientView($pageInfo, $model);
                break;

            case 'deactivateAcc':
                include 'views/deactivateClientView.php';
                $view = new DeactivateAccView($pageInfo, $model);
                break;

            case 'editClient':
                include 'views/editClientView.php';
                $view = new EditClientView($pageInfo, $model);
                break;

            case 'addPost':
                include 'views/addPostView.php';
                $view = new AddPostView($pageInfo, $model);
                break;

            case 'editPost':
                include 'views/editPostView.php';
                $view = new EditPostView($pageInfo, $model);
                break;

            case 'deletePost':
                include 'views/deletePostView.php';
                $view = new DeletePostView($pageInfo, $model);
                break;

            case 'addMember':
                include 'views/addMemberView.php';
                $view = new AddMemberView($pageInfo, $model);
                break;

            case 'editMember':
                include 'views/editMemberView.php';
                $view = new EditMemberView($pageInfo, $model);
                break;

            case 'deleteMember':
                include 'views/deleteMemberView.php';
                $view = new DeleteMemberView($pageInfo, $model);
                break;

            case 'newsItem':
                include 'views/newsItemView.php';
                $view = new NewsItemView($pageInfo, $model);
                break;

            case 'addFaq':
                include 'views/addFaqView.php';
                $view = new AddFaqView($pageInfo, $model);
                break;

            case 'editFaq':
                include 'views/editFaqView.php';
                $view = new EditFaqView($pageInfo, $model);
                break;

            case 'deleteFaq':
                include 'views/deleteFaqView.php';
                $view = new DeleteFaqView($pageInfo, $model);
                break;

            case 'changePassword':
                include 'views/changePasswordView.php';
                $view = new ChangePasswordView($pageInfo, $model);
                break;
                
            case 'rss':
                 include 'views/rssView.php';
                $view = new RSSView($model);
                break;
                
            case 'siteMap':
                include 'views/siteMapView.php';
                $view = new SiteMapView($pageInfo, $model);
                break;

            default:
                include 'views/404.php';
                $view = new ErrorView($pageInfo, $model);
                break;
            
        }   #switch
        
        echo $view -> displayPage();
        
    }   #run
    
    
}   #PageSelector

$pageSelect = new PageSelector();
$pageSelect -> run();



?>