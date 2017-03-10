<?php
//add database and validate funcitons
require_once('../Model/database.php');
require_once('validate.php');
//set session length and start session
$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
session_set_cookie_params($lifetime, '/');
session_start();

//if session has alias, someone is logged in otherwise they are not
if (!isset($_SESSION['alias'])) {
    $logged_in = false;
    $commentview = 'hidden';
    $controls = 'hidden';
} else {
    $logged_in = true;
    $commentview = '';
    $controls = '';
}

//get action from post
$action = filter_input(INPUT_POST, 'action');

//set up default actions
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'default';
    }
}
//echo $action

// users needs to be accesed by most pages and functions
$bidders = get_bidders();

switch ($action) {
    //default action
    case 'default':
        $bidders = get_bidders();
        
        $error_message = filter_input(INPUT_GET, 'error_message');
        include('../View/login.php');  //header to log in page
        
        break; //end for default action

    //register logic
    case 'register':
       
        //if variables are not set, set them as blank
        if (!isset($firstName)) {
            $firstName = '';
        }
        if (!isset($lastName)) {
            $lastName = '';
        }
        if (!isset($alias)) {
            $alias = '';
        }
        if (!isset($password)) {
            $password = '';
        }
        if (!isset($email)) {
            $email = '';
        }
        include('../View/Register.php');
        break; //end for register

    //login logic
    case 'login':
        
        //pull alias and pw from post
        $alias = filter_input(INPUT_POST, 'alias', FILTER_DEFAULT);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

        if (login_check($alias, $password)) { //check alias and password for match

            //populate variables to show on page
            $_SESSION['alias'] = $alias;
            $profile = get_bidders_info($alias);
            $firstName = $profile[0]['firstName'];
            $lastName = $profile[0]['lastName'];
            $email = $profile[0]['email'];
            

            //test if image is set or empty, use default if it is

            $password = ''; //hide password
            //take to pasture rental page if login success
            $bidders = get_bidders();
            $logged_in = true;
            $commentview = '';
            $controls = '';
            $bid=0;
            $total_bid = 0;
            include('../View/pastureRental.php');
            break; //break for login success
        }
        
        //clear password and return to log in page if log in fails
        if (!isset($alias)) {
            $alias = '';
        }
        $password = '';
        header('Location: ../controller/index.php?error_message=Log-in failed');

        break; //end for login(fail)
        
    case 'submit_bid':
    
        if(!isset($bid)){
            $bid = 0;
        }
        
        $alias=$_SESSION['alias'];
        
        $bid = filter_input(INPUT_POST, 'bid');
        
        $total_bid = 42.5*7*$bid;
        
        include '../View/pastureRental.php';
        break;   
    case 'user_profile':
        
        

        if ($logged_in) { 

            //populate variables to show on page
            $alias = $_SESSION['alias'];
            $profile = get_user_info($alias);
            $firstName = $profile[0]['firstName'];
            $lastName = $profile[0]['lastName'];
            $email = $profile[0]['email'];
            $image = $profile[0]['image'];

            $password = ''; //hide password
            //take to user profile page if login success
            $users = get_users();
            include('../View/user_profile.php');
            break; //break for login success
        }
        
        //clear password and return to log in page if log in fails
        if (!isset($alias)) {
            $alias = '';
        }
        $password = '';
        header('Location: ../controller/index.php');

        break; //end for login(fail)
        
    //log out
    case 'log_out':
        //destroy session and header to controller to log back in
        session_destroy();
        header('Location: ../controller/index.php');
        break; //end for log out

    //attempt to register
    case 'try_register':

        //pull information from post
        $firstName = filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT);
        $lastName = filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT);
        $alias = filter_input(INPUT_POST, 'alias', FILTER_DEFAULT);
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        //validate registration information(false at end indicates it's not a profile update)
        $error_message = validate_reg_update($firstName, $lastName, $alias, $password, $email, false);

        // if an error message exists, go to the register page
        if ($error_message != '') {
            include('../View/register.php');
            exit();
        }

        $options = ['cost' => 12,];   //options for hashing password  
        $hash = password_hash($password, PASSWORD_DEFAULT, $options); //hash password
        //
        //otherwise insert into the database and display results
        insert_bidder($firstName, $lastName, $alias, $hash, $email, '../Model/images/profile_default.png');
        include('../View/Reg_Success.php');
        break; //end for try register

    //update profile
    case 'profile_update':

        //check if someone is logged in, if not, send back to log in page
        if ($logged_in === false) {
            header('Location: ../controller/index.php');
        }
        //pull information from post but use alias saved in session
        $firstName = filter_input(INPUT_POST, 'first_name', FILTER_DEFAULT);
        $lastName = filter_input(INPUT_POST, 'last_name', FILTER_DEFAULT);
        $alias = $_SESSION['alias'];
        $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

        //check if input is valid(true indicates it's an update action)
        $error_message = validate_reg_update($firstName, $lastName, $alias, $password, $email, true);

        $profile = get_user_info($alias);
        $image = $profile[0]['image'];

        // if an error message exists, return with message
        if ($error_message != '') {
            $password = ''; //don't want to show password, security and what not.
            include('../View/user_profile.php');
            exit();
        }

        
        $options = ['cost' => 12,];   //options for hashing password  
        $hash = password_hash($password, PASSWORD_DEFAULT, $options); //hash password
        //
        //otherwise insert into the database and display results
        update_user($firstName, $lastName, $alias, $hash, $email);
        //let user know it's been updated
        $error_message = "User profile updated";

        //get image if there is one to display

        $password = ''; //don't want to show password, security and what not.
        include('../View/user_profile.php');
        break; //end for profile update

    //upload image
    case 'upload_image':
        $alias = $_SESSION['alias'];
        
        
        $target_dir = "../Model/images/";
        $target_file = $target_dir . $alias . "profilepic.jpg"; //basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
        echo $imageFileType;
        
        
        
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $error_message = "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $error_message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $error_message = '';
            } else {
                $error_message = "Sorry, there was an error uploading your file.";
            }
        }

        
        $profile = get_user_info($alias);
        $firstName = $profile[0]['firstName'];
        $lastName = $profile[0]['lastName'];
        $email = $profile[0]['email'];
        $image = $profile[0]['image'];
        $password = '';
        
        if ($error_message != '') {
            include('../View/user_profile.php');
            exit();
        }

        $error_message = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        //echo $target_file;
        add_image($alias, $target_file);
        include('../View/user_profile.php');

        break; //end for upload image
        
    case 'view_profile':
        $alias = filter_input(INPUT_GET, 'alias');
        $_SESSION['profilealias'] = $alias;
            $profile = get_user_info($alias);
            $firstName = $profile[0]['firstName'];
            $lastName = $profile[0]['lastName'];
            $email = $profile[0]['email'];
            $image = $profile[0]['image'];
        $comments = get_comments($alias);
        $buttonview = '';
        $showform = "hidden";
        $error_message = filter_input(INPUT_GET, 'error_message');
        include('../View/profile_view.php');  //header to log in page
        
        break; //end for default action
    
    case 'add_comment':
        $alias = $_SESSION['profilealias'];
        $profile = get_user_info($alias);
        $firstName = $profile[0]['firstName'];
        $lastName = $profile[0]['lastName'];
        $email = $profile[0]['email'];
        $image = $profile[0]['image'];
        $comments = get_comments($alias);
        $buttonview = 'hidden';
        $showform = "show";
        include('../View/profile_view.php');  //header to log in page
        
        break; //end for default action
    
    case 'submit_comment':
        $alias = $_SESSION['profilealias'];
        if(isset($_SESSION['alias'])){
            $commenter = $_SESSION['alias'];
            date_default_timezone_set('America/Chicago');
            $commentdate = date('Y-m-d H:i');
            $comment = filter_input(INPUT_POST, 'comment', FILTER_DEFAULT);            
            $check = "";

            if ($comment === null || $comment === $check) {
                
                header('Location: ../controller/index.php?action=view_profile&error_message=Comment is required&alias='.$alias);
                exit();
            } else {
                insert_comment($alias, $comment, $commentdate, $commenter);
            }
        }
        
        $profile = get_user_info($alias);
        $firstName = $profile[0]['firstName'];
        $lastName = $profile[0]['lastName'];
        $email = $profile[0]['email'];
        $image = $profile[0]['image'];
        $comments = get_comments($alias);
        $buttonview = '';
        $showform = "hidden";

        //include('../View/profile_view.php');  
        header('Location: ../controller/index.php?action=view_profile&alias='.$alias);
        break; //end for default action 
}
?> 
<!DOCTYPE html>









<!DOCTYPE html>
<!--

-->

